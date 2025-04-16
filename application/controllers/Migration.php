<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Add archive columns to orders table
     */
    public function add_archive_columns() {
        // Check if the columns already exist
        $fields = $this->db->field_data('orders');
        $is_archived_exists = false;
        $archived_at_exists = false;
        $archived_by_exists = false;
        
        foreach ($fields as $field) {
            if ($field->name === 'is_archived') {
                $is_archived_exists = true;
            }
            if ($field->name === 'archived_at') {
                $archived_at_exists = true;
            }
            if ($field->name === 'archived_by') {
                $archived_by_exists = true;
            }
        }
        
        // Add is_archived column if it doesn't exist
        if (!$is_archived_exists) {
            $this->db->query("ALTER TABLE `orders` ADD COLUMN `is_archived` TINYINT(1) NOT NULL DEFAULT 0 AFTER `user_id`");
            echo "Added is_archived column to orders table<br>";
        }
        
        // Add archived_at column if it doesn't exist
        if (!$archived_at_exists) {
            $this->db->query("ALTER TABLE `orders` ADD COLUMN `archived_at` INT NULL AFTER `is_archived`");
            echo "Added archived_at column to orders table<br>";
        }
        
        // Add archived_by column if it doesn't exist
        if (!$archived_by_exists) {
            $this->db->query("ALTER TABLE `orders` ADD COLUMN `archived_by` INT NULL AFTER `archived_at`");
            echo "Added archived_by column to orders table<br>";
        }
        
        echo "Migration completed.";
    }
} 