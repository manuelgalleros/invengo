# Foreign Key Constraints for Invengo Database

This documentation explains how to implement foreign key constraints in the Invengo database system to prevent accidental deletion of referenced records.

## Purpose

The foreign key constraints will ensure:

1. **Brands and categories cannot be deleted** if they are being used by products.
2. **Groups cannot be deleted** if users are assigned to them.
3. **Orders can cascade delete** order items when deleted.
4. **Products cannot be deleted** if they are referenced in order items.

## Implementation Options

You have two ways to implement these constraints:

### Option 1: Using the PHP Script (Recommended)

1. Make sure your database credentials are correct in the `apply_foreign_keys.php` file.
2. Run the script by navigating to `http://localhost/invengo/apply_foreign_keys.php` in your browser.
3. You should see output indicating the successful application of constraints.

### Option 2: Using the SQL Script Directly

1. Open phpMyAdmin or your preferred MySQL client.
2. Select the `invengo` database.
3. Run the SQL commands in the `add_foreign_keys.sql` file.

## Changes Made

The implementation makes the following changes:

1. Converts `brand_id` and `category_id` columns in the `products` table from TEXT to INT.
2. Adds RESTRICT constraints on deleting brands and categories referenced by products.
3. Adds RESTRICT constraints on deleting groups referenced by users.
4. Adds CASCADE constraints for deleting order items when orders are deleted.
5. Adds RESTRICT constraints on deleting products referenced in order items.

## Operational Impact

After implementing these constraints:

- When attempting to delete a brand or category that has products, you will receive an error.
- When attempting to delete a group that has users assigned to it, you will receive an error.
- When deleting an order, all related order items will be automatically deleted.
- When attempting to delete a product that is included in order items, you will receive an error.

## Verification

To verify the constraints are working:

1. Try to delete a brand that has products - you should receive an error.
2. Try to delete a group that has users - you should receive an error.
3. Delete an order - all its order items should be automatically deleted.

## Troubleshooting

If you encounter issues:

1. Check that your MySQL user has ALTER TABLE privileges.
2. Verify that the tables already have PRIMARY KEY constraints defined.
3. If errors occur, review the PHP script output or MySQL error messages for details.

## Reverting Changes

To revert the changes and remove the foreign key constraints, run the following SQL:

```sql
ALTER TABLE `products` DROP FOREIGN KEY `fk_products_brand`;
ALTER TABLE `products` DROP FOREIGN KEY `fk_products_category`;
ALTER TABLE `user_group` DROP FOREIGN KEY `fk_user_group_user`;
ALTER TABLE `user_group` DROP FOREIGN KEY `fk_user_group_group`;
ALTER TABLE `orders_item` DROP FOREIGN KEY `fk_orders_item_order`;
ALTER TABLE `orders_item` DROP FOREIGN KEY `fk_orders_item_product`;
``` 