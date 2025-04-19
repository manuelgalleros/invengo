<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Log activity using the Logs model
 * 
 * @param string $action The action performed (Create, Update, Delete, Archive)
 * @param string $module The module where the action occurred (Products, Orders, etc.)
 * @param string $description Description of the activity
 * @param bool $success Whether the action was successful
 * @return bool True on success, false on failure
 */
function log_activity($action, $module, $description, $success = true)
{
    $CI =& get_instance();
    $CI->load->model('model_logs');
    
    // Debug original description
    error_log("ACTIVITY LOG ORIGINAL: Action: $action, Module: $module, Description: $description, Success: " . ($success ? 'true' : 'false'));
    
    // Ensure order numbers are properly formatted with # symbol for Orders module
    if ($module === 'Orders') {
        // Track if description was modified
        $original_description = $description;
        
        // First, extract any existing order numbers from the description
        $has_order_number = preg_match('/(\d+)/', $description, $matches);
        
        // If there's a number in the description but no # symbol before it
        if ($has_order_number && !preg_match('/#\d+/', $description)) {
            $order_number = $matches[1];
            // Replace "order NUMBER" with "order #NUMBER"
            $description = preg_replace('/\border\s+' . $order_number . '\b/i', 'order #' . $order_number, $description);
            // Also handle "orders NUMBER"
            $description = preg_replace('/\borders\s+' . $order_number . '\b/i', 'orders #' . $order_number, $description);
            
            error_log("ADDED # TO EXISTING NUMBER: " . $description);
        }
        // If no order number in description but order_id is available in POST
        elseif ($CI->input->post('order_id')) {
            $order_id = $CI->input->post('order_id');
            
            // Check if the description already contains an order action verb
            if (preg_match('/(Created|Updated|Deleted|Archived|Restored) (new )?order/i', $description)) {
                // Load the order model if not already loaded
                if (!class_exists('Model_orders')) {
                    $CI->load->model('model_orders');
                }
                
                // Get the order data to find the order_no
                $order_data = $CI->model_orders->getOrdersData($order_id);
                
                if ($order_data && isset($order_data['order_no'])) {
                    // Keep the original verb but add the order number
                    if (preg_match('/(Created|Updated|Deleted|Archived|Restored) (new )?order/i', $description, $matches)) {
                        $action_verb = $matches[0];
                        // Replace the action verb with same verb plus order number
                        $description = str_replace($action_verb, $action_verb . ' #' . $order_data['order_no'], $description);
                        error_log("ADDED ORDER NUMBER FROM POST DATA: " . $description);
                    }
                }
            }
        }
        
        // Force add # to any remaining "order NUMBER" patterns 
        $description = preg_replace('/\border\s+(\d+)\b/i', 'order #$1', $description);
        $description = preg_replace('/\borders\s+(\d+)\b/i', 'orders #$1', $description);
        
        // Check if description was changed
        if ($original_description !== $description) {
            error_log("FINAL FORMATTED DESCRIPTION: $description (changed from: $original_description)");
        } else {
            error_log("DESCRIPTION UNCHANGED: $description");
        }
    }
    
    // Ensure PHP timezone is set to Philippine Standard Time
    date_default_timezone_set('Asia/Manila');
    
    // Current time in Philippines
    $current_time = time();
    $current_datetime = date('Y-m-d H:i:s', $current_time);
    
    // Only log important actions when they are successful or explicitly configured to log failures
    if (!$success) {
        // For order operations, we want to capture failures as well
        if ($module === 'Orders' && (stripos($description, 'order #') !== false)) {
            // Continue logging for failed order operations that include an order number
        } else {
            return false;
        }
    }
    
    // List of actions that should be logged
    $loggable_actions = ['create', 'update', 'delete', 'archive', 'restore'];
    
    // Convert action to lowercase for comparison
    $action_lower = strtolower($action);
    
    // Only proceed if this is an action we want to log
    if (!in_array($action_lower, $loggable_actions)) {
        return false;
    }
    
    // Prepare log data
    $log_data = [
        'user_id' => $CI->session->userdata('id'),
        'username' => $CI->session->userdata('username'),
        'action' => ucfirst($action), // Capitalize first letter
        'description' => $module . ': ' . $description,
        'created_at' => $current_time,
        'timestamp' => $current_datetime
    ];
    
    // Log the full data being saved
    error_log("LOGGING ACTIVITY DATA: " . json_encode($log_data));
    
    // Add the log directly using the model
    $result = $CI->model_logs->create($log_data);
    
    // Log the result of the logging operation
    error_log("LOG CREATION RESULT: " . ($result ? "Success" : "Failed"));
    
    return $result;
} 