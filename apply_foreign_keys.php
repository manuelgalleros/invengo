<?php

/**
 * Script to apply foreign key constraints to the invengo database
 * 
 * This script needs to be run from the command line or browser.
 * It will add foreign key constraints to prevent deletion of:
 * - Brands and categories when products are using them
 * - Groups when users are assigned to them
 */

// Include database configuration
$host = "localhost";
$username = "root"; 
$password = "@Password123"; // Set your MySQL password here, usually empty for XAMPP
$database = "invengo";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully. Starting constraint application...\n";
    
    // First, check if any existing foreign key constraints need to be removed
    echo "Checking for existing foreign key constraints...\n";
    
    // Get existing constraints on products table
    $stmt = $pdo->query("SHOW CREATE TABLE `products`");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $createTableSql = $result['Create Table'];
    
    if (strpos($createTableSql, 'FOREIGN KEY') !== false) {
        echo "Removing existing foreign key constraints from products table...\n";
        
        $stmt = $pdo->query("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = '$database'
            AND TABLE_NAME = 'products'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $constraintName = $row['CONSTRAINT_NAME'];
            $pdo->exec("ALTER TABLE `products` DROP FOREIGN KEY `$constraintName`");
            echo "Dropped constraint: $constraintName\n";
        }
    }
    
    // Get existing constraints on user_group table
    $stmt = $pdo->query("SHOW CREATE TABLE `user_group`");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $createTableSql = $result['Create Table'];
    
    if (strpos($createTableSql, 'FOREIGN KEY') !== false) {
        echo "Removing existing foreign key constraints from user_group table...\n";
        
        $stmt = $pdo->query("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = '$database'
            AND TABLE_NAME = 'user_group'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $constraintName = $row['CONSTRAINT_NAME'];
            $pdo->exec("ALTER TABLE `user_group` DROP FOREIGN KEY `$constraintName`");
            echo "Dropped constraint: $constraintName\n";
        }
    }
    
    // Get existing constraints on orders_item table
    $stmt = $pdo->query("SHOW CREATE TABLE `orders_item`");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $createTableSql = $result['Create Table'];
    
    if (strpos($createTableSql, 'FOREIGN KEY') !== false) {
        echo "Removing existing foreign key constraints from orders_item table...\n";
        
        $stmt = $pdo->query("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = '$database'
            AND TABLE_NAME = 'orders_item'
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $constraintName = $row['CONSTRAINT_NAME'];
            $pdo->exec("ALTER TABLE `orders_item` DROP FOREIGN KEY `$constraintName`");
            echo "Dropped constraint: $constraintName\n";
        }
    }
    
    // Modify products table to convert text columns to int
    echo "Modifying products table columns...\n";
    $pdo->exec("ALTER TABLE `products` 
                MODIFY COLUMN `brand_id` INT NOT NULL,
                MODIFY COLUMN `category_id` INT NOT NULL");
    
    // Add FOREIGN KEY constraints to products table
    echo "Adding foreign key constraints to products table...\n";
    $pdo->exec("ALTER TABLE `products` 
                ADD CONSTRAINT `fk_products_brand` 
                FOREIGN KEY (`brand_id`) REFERENCES `brands`(`id`) 
                ON DELETE RESTRICT 
                ON UPDATE CASCADE");
    
    $pdo->exec("ALTER TABLE `products` 
                ADD CONSTRAINT `fk_products_category` 
                FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) 
                ON DELETE RESTRICT 
                ON UPDATE CASCADE");
    
    // Add FOREIGN KEY constraints to user_group table
    echo "Adding foreign key constraints to user_group table...\n";
    $pdo->exec("ALTER TABLE `user_group` 
                ADD CONSTRAINT `fk_user_group_user` 
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) 
                ON DELETE CASCADE 
                ON UPDATE CASCADE");
    
    $pdo->exec("ALTER TABLE `user_group` 
                ADD CONSTRAINT `fk_user_group_group` 
                FOREIGN KEY (`group_id`) REFERENCES `groups`(`id`) 
                ON DELETE RESTRICT 
                ON UPDATE CASCADE");
    
    // Add FOREIGN KEY constraints to orders_item table
    echo "Adding foreign key constraints to orders_item table...\n";
    $pdo->exec("ALTER TABLE `orders_item` 
                ADD CONSTRAINT `fk_orders_item_order` 
                FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) 
                ON DELETE CASCADE 
                ON UPDATE CASCADE");
    
    $pdo->exec("ALTER TABLE `orders_item` 
                ADD CONSTRAINT `fk_orders_item_product` 
                FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) 
                ON DELETE RESTRICT 
                ON UPDATE CASCADE");
    
    echo "Foreign key constraints have been successfully applied!\n";
    echo "Now brands and categories cannot be deleted if products are using them.\n";
    echo "Groups cannot be deleted if users are assigned to them.\n";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 