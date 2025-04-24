<?php

/**
 * Script to remove the foreign key constraint that prevents products from being deleted
 * when they have orders associated with them.
 */

// Include database configuration
$host = "localhost";
$username = "root"; 
$password = "@Password123"; // Set your MySQL password here if needed
$database = "invengo";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Foreign Key Constraint Removal Tool</h2>";
    echo "<p>Connected to database successfully.</p>";
    
    // Check if the constraint exists on orders_item table
    $stmt = $pdo->query("
        SELECT CONSTRAINT_NAME
        FROM information_schema.TABLE_CONSTRAINTS
        WHERE TABLE_SCHEMA = '$database'
        AND TABLE_NAME = 'orders_item'
        AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        AND CONSTRAINT_NAME = 'fk_orders_item_product'
    ");
    
    $constraintExists = $stmt->rowCount() > 0;
    
    if ($constraintExists) {
        echo "<p>Found the foreign key constraint 'fk_orders_item_product' that prevents products from being deleted when they have orders.</p>";
        
        // Remove the constraint
        $pdo->exec("ALTER TABLE `orders_item` DROP FOREIGN KEY `fk_orders_item_product`");
        
        echo "<p style='color: green;'>Successfully removed the constraint. Products can now be deleted even if they have orders associated with them.</p>";
        
        // Add a SET NULL constraint instead (optional)
        $stmt = $pdo->query("SHOW COLUMNS FROM `orders_item` WHERE Field = 'product_id'");
        $column = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if the column is nullable
        if (strpos($column['Null'], 'YES') === false) {
            // Make the column nullable
            $pdo->exec("ALTER TABLE `orders_item` MODIFY `product_id` INT NULL");
            echo "<p>Modified 'product_id' column to allow NULL values.</p>";
        }
        
        // Add a new constraint that sets product_id to NULL when a product is deleted
        $pdo->exec("ALTER TABLE `orders_item` 
                    ADD CONSTRAINT `fk_orders_item_product` 
                    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) 
                    ON DELETE SET NULL 
                    ON UPDATE CASCADE");
        
        echo "<p>Added a new constraint that sets order items' product_id to NULL when a product is deleted.</p>";
        echo "<p>This will preserve order history while allowing products to be deleted.</p>";
    } else {
        echo "<p>The constraint 'fk_orders_item_product' was not found. It may have already been removed or never existed.</p>";
        
        // Check if any other foreign key constraints exist on the product_id column
        $stmt = $pdo->query("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = '$database'
            AND TABLE_NAME = 'orders_item'
            AND COLUMN_NAME = 'product_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $constraintName = $row['CONSTRAINT_NAME'];
                echo "<p>Found another constraint on product_id: $constraintName. Removing it...</p>";
                $pdo->exec("ALTER TABLE `orders_item` DROP FOREIGN KEY `$constraintName`");
                echo "<p>Removed constraint: $constraintName</p>";
            }
            
            // Make the column nullable
            $pdo->exec("ALTER TABLE `orders_item` MODIFY `product_id` INT NULL");
            echo "<p>Modified 'product_id' column to allow NULL values.</p>";
            
            // Add SET NULL constraint
            $pdo->exec("ALTER TABLE `orders_item` 
                        ADD CONSTRAINT `fk_orders_item_product` 
                        FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) 
                        ON DELETE SET NULL 
                        ON UPDATE CASCADE");
            
            echo "<p>Added a new constraint that sets order items' product_id to NULL when a product is deleted.</p>";
        } else {
            echo "<p>No foreign key constraints found on the product_id column. Products should be deletable without issues.</p>";
        }
    }
    
    echo "<p><a href='index.php'>Return to application</a></p>";
    
} catch(PDOException $e) {
    echo "<h2>Error Occurred</h2>";
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p><a href='index.php'>Return to application</a></p>";
}
?> 