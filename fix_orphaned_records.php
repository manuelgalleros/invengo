<?php

/**
 * Script to fix orphaned records in the user_group table
 * 
 * This script identifies and removes orphaned user_group records
 * that reference non-existent users or groups before applying
 * foreign key constraints.
 */

// Include database configuration
$host = "localhost";
$username = "root"; 
$password = "@Password123"; // Update with your actual MySQL password
$database = "invengo";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>Database Referential Integrity Fix</h1>";
    echo "<pre>Connected to database successfully. Starting orphaned record cleanup...\n\n";
    
    // 1. Find orphaned records in user_group (users that don't exist)
    echo "Step 1: Finding user_group records with non-existent users...\n";
    $sql = "SELECT ug.id, ug.user_id 
            FROM user_group ug 
            LEFT JOIN users u ON ug.user_id = u.id 
            WHERE u.id IS NULL";
    
    $stmt = $pdo->query($sql);
    $orphanedUserRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($orphanedUserRecords) > 0) {
        echo "Found " . count($orphanedUserRecords) . " user_group records with non-existent users.\n";
        
        // Delete these orphaned records
        $orphanedIds = array_column($orphanedUserRecords, 'id');
        $placeholders = implode(',', array_fill(0, count($orphanedIds), '?'));
        
        $sql = "DELETE FROM user_group WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        
        $idx = 1;
        foreach ($orphanedIds as $id) {
            $stmt->bindValue($idx++, $id);
        }
        
        $stmt->execute();
        echo "Deleted " . $stmt->rowCount() . " orphaned user_group records with non-existent users.\n\n";
    } else {
        echo "No orphaned user records found in user_group table. Good!\n\n";
    }
    
    // 2. Find orphaned records in user_group (groups that don't exist)
    echo "Step 2: Finding user_group records with non-existent groups...\n";
    $sql = "SELECT ug.id, ug.group_id 
            FROM user_group ug 
            LEFT JOIN groups g ON ug.group_id = g.id 
            WHERE g.id IS NULL";
    
    $stmt = $pdo->query($sql);
    $orphanedGroupRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($orphanedGroupRecords) > 0) {
        echo "Found " . count($orphanedGroupRecords) . " user_group records with non-existent groups.\n";
        
        // Delete these orphaned records
        $orphanedIds = array_column($orphanedGroupRecords, 'id');
        $placeholders = implode(',', array_fill(0, count($orphanedIds), '?'));
        
        $sql = "DELETE FROM user_group WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        
        $idx = 1;
        foreach ($orphanedIds as $id) {
            $stmt->bindValue($idx++, $id);
        }
        
        $stmt->execute();
        echo "Deleted " . $stmt->rowCount() . " orphaned user_group records with non-existent groups.\n\n";
    } else {
        echo "No orphaned group records found in user_group table. Good!\n\n";
    }
    
    // 3. Check products table for non-existent brands and categories
    echo "Step 3: Finding products with non-existent brands...\n";
    $sql = "SELECT p.id, p.name, p.brand_id
            FROM products p
            LEFT JOIN brands b ON p.brand_id = b.id
            WHERE b.id IS NULL AND p.brand_id != 0 AND p.brand_id != ''";
    
    $stmt = $pdo->query($sql);
    $orphanedBrandProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($orphanedBrandProducts) > 0) {
        echo "Found " . count($orphanedBrandProducts) . " products with non-existent brands:\n";
        foreach ($orphanedBrandProducts as $product) {
            echo "- Product ID: {$product['id']}, Name: {$product['name']}, Invalid Brand ID: {$product['brand_id']}\n";
        }
        
        echo "\nUpdating these products to use a default brand...\n";
        
        // First ensure we have at least one active brand to use as default
        $sql = "SELECT id FROM brands WHERE active = 1 LIMIT 1";
        $stmt = $pdo->query($sql);
        $defaultBrand = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$defaultBrand) {
            // Create a default brand if none exists
            $sql = "INSERT INTO brands (name, active) VALUES ('Default Brand', 1)";
            $pdo->exec($sql);
            $defaultBrandId = $pdo->lastInsertId();
            echo "Created default brand with ID $defaultBrandId\n";
        } else {
            $defaultBrandId = $defaultBrand['id'];
            echo "Using existing brand with ID $defaultBrandId as default\n";
        }
        
        // Update the orphaned products to use the default brand
        $orphanedIds = array_column($orphanedBrandProducts, 'id');
        $placeholders = implode(',', array_fill(0, count($orphanedIds), '?'));
        
        $sql = "UPDATE products SET brand_id = ? WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindValue(1, $defaultBrandId);
        $idx = 2;
        foreach ($orphanedIds as $id) {
            $stmt->bindValue($idx++, $id);
        }
        
        $stmt->execute();
        echo "Updated " . $stmt->rowCount() . " products to use the default brand.\n\n";
    } else {
        echo "No products with non-existent brands found. Good!\n\n";
    }
    
    // 4. Check products for non-existent categories
    echo "Step 4: Finding products with non-existent categories...\n";
    $sql = "SELECT p.id, p.name, p.category_id
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE c.id IS NULL AND p.category_id != 0 AND p.category_id != ''";
    
    $stmt = $pdo->query($sql);
    $orphanedCategoryProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($orphanedCategoryProducts) > 0) {
        echo "Found " . count($orphanedCategoryProducts) . " products with non-existent categories:\n";
        foreach ($orphanedCategoryProducts as $product) {
            echo "- Product ID: {$product['id']}, Name: {$product['name']}, Invalid Category ID: {$product['category_id']}\n";
        }
        
        echo "\nUpdating these products to use a default category...\n";
        
        // First ensure we have at least one active category to use as default
        $sql = "SELECT id FROM categories WHERE active = 1 LIMIT 1";
        $stmt = $pdo->query($sql);
        $defaultCategory = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$defaultCategory) {
            // Create a default category if none exists
            $sql = "INSERT INTO categories (name, active) VALUES ('Default Category', 1)";
            $pdo->exec($sql);
            $defaultCategoryId = $pdo->lastInsertId();
            echo "Created default category with ID $defaultCategoryId\n";
        } else {
            $defaultCategoryId = $defaultCategory['id'];
            echo "Using existing category with ID $defaultCategoryId as default\n";
        }
        
        // Update the orphaned products to use the default category
        $orphanedIds = array_column($orphanedCategoryProducts, 'id');
        $placeholders = implode(',', array_fill(0, count($orphanedIds), '?'));
        
        $sql = "UPDATE products SET category_id = ? WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindValue(1, $defaultCategoryId);
        $idx = 2;
        foreach ($orphanedIds as $id) {
            $stmt->bindValue($idx++, $id);
        }
        
        $stmt->execute();
        echo "Updated " . $stmt->rowCount() . " products to use the default category.\n\n";
    } else {
        echo "No products with non-existent categories found. Good!\n\n";
    }

    // 5. Check orders_item for non-existent orders
    echo "Step 5: Finding order items with non-existent orders...\n";
    $sql = "SELECT oi.id, oi.order_id 
            FROM orders_item oi 
            LEFT JOIN orders o ON oi.order_id = o.id 
            WHERE o.id IS NULL";
    
    $stmt = $pdo->query($sql);
    $orphanedOrderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($orphanedOrderItems) > 0) {
        echo "Found " . count($orphanedOrderItems) . " order items with non-existent orders.\n";
        
        // Delete these orphaned records
        $orphanedIds = array_column($orphanedOrderItems, 'id');
        $placeholders = implode(',', array_fill(0, count($orphanedIds), '?'));
        
        $sql = "DELETE FROM orders_item WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        
        $idx = 1;
        foreach ($orphanedIds as $id) {
            $stmt->bindValue($idx++, $id);
        }
        
        $stmt->execute();
        echo "Deleted " . $stmt->rowCount() . " orphaned order items with non-existent orders.\n\n";
    } else {
        echo "No orphaned order items found. Good!\n\n";
    }
    
    // 6. Check orders_item for non-existent products
    echo "Step 6: Finding order items with non-existent products...\n";
    $sql = "SELECT oi.id, oi.product_id 
            FROM orders_item oi 
            LEFT JOIN products p ON oi.product_id = p.id 
            WHERE p.id IS NULL";
    
    $stmt = $pdo->query($sql);
    $orphanedProductItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($orphanedProductItems) > 0) {
        echo "Found " . count($orphanedProductItems) . " order items with non-existent products.\n";
        
        // Delete these orphaned records
        $orphanedIds = array_column($orphanedProductItems, 'id');
        $placeholders = implode(',', array_fill(0, count($orphanedIds), '?'));
        
        $sql = "DELETE FROM orders_item WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        
        $idx = 1;
        foreach ($orphanedIds as $id) {
            $stmt->bindValue($idx++, $id);
        }
        
        $stmt->execute();
        echo "Deleted " . $stmt->rowCount() . " orphaned order items with non-existent products.\n\n";
    } else {
        echo "No orphaned product items found. Good!\n\n";
    }
    
    echo "Cleanup completed! The database is now ready for foreign key constraints.\n";
    echo "You can now run the apply_foreign_keys.php script to apply the constraints.</pre>";
    
    echo "<p><a href='apply_foreign_keys.php' class='btn btn-primary'>Proceed to Apply Foreign Key Constraints</a></p>";
    
} catch(PDOException $e) {
    echo "<pre>Error: " . $e->getMessage() . "</pre>";
}
?> 