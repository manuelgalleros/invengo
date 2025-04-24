-- SQL Script to add FOREIGN KEY constraints to invengo database

-- First, modify products table to change text columns to INT
ALTER TABLE `products` 
  MODIFY COLUMN `brand_id` INT NOT NULL,
  MODIFY COLUMN `category_id` INT NOT NULL;

-- Add FOREIGN KEY constraints to products table
ALTER TABLE `products` 
  ADD CONSTRAINT `fk_products_brand` 
  FOREIGN KEY (`brand_id`) REFERENCES `brands`(`id`) 
  ON DELETE RESTRICT 
  ON UPDATE CASCADE;

ALTER TABLE `products` 
  ADD CONSTRAINT `fk_products_category` 
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) 
  ON DELETE RESTRICT 
  ON UPDATE CASCADE;

-- Add FOREIGN KEY constraints to user_group table
ALTER TABLE `user_group` 
  ADD CONSTRAINT `fk_user_group_user` 
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) 
  ON DELETE CASCADE 
  ON UPDATE CASCADE;

ALTER TABLE `user_group` 
  ADD CONSTRAINT `fk_user_group_group` 
  FOREIGN KEY (`group_id`) REFERENCES `groups`(`id`) 
  ON DELETE RESTRICT 
  ON UPDATE CASCADE;

-- Add FOREIGN KEY constraints to orders_item table
ALTER TABLE `orders_item` 
  ADD CONSTRAINT `fk_orders_item_order` 
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) 
  ON DELETE CASCADE 
  ON UPDATE CASCADE;

ALTER TABLE `orders_item` 
  ADD CONSTRAINT `fk_orders_item_product` 
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) 
  ON DELETE RESTRICT 
  ON UPDATE CASCADE; 