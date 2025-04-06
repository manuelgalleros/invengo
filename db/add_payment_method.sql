-- Add payment_method column to orders table
ALTER TABLE `orders` ADD `payment_method` VARCHAR(50) NULL DEFAULT 'cash' AFTER `customer_phone`;

-- Update existing orders to use 'cash' as the default payment method
UPDATE `orders` SET `payment_method` = 'cash' WHERE `payment_method` IS NULL; 