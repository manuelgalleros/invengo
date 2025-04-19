<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Logs Library
 * 
 * Handles activity logging for the application
 */
class Logs
{
    protected $CI;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Get the CI instance
        $this->CI =& get_instance();
        
        // Load required models
        $this->CI->load->model('model_logs');
    }
    
    /**
     * Log activity
     * 
     * @param string $action The action performed (Create, Update, Delete, Archive)
     * @param string $module The module where the action occurred (Products, Orders, etc.)
     * @param string $description Description of the activity
     * @param bool $success Whether the action was successful
     * @return bool True on success, false on failure
     */
    public function logActivity($action, $module, $description, $success = true)
    {
        // Only log if the action was successful or explicitly configured to log failures
        if (!$success) {
            // For order operations, we want to capture failures as well if they include an order number
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
        
        // For single order operations, ensure archive and restore include the order number
        if (($action_lower === 'archive' || $action_lower === 'restore') && 
            ($module === 'Orders') && 
            !preg_match('/orders/', $description) && 
            !preg_match('/order #/', $description)) {
            
            // If description doesn't include order number, log an error
            log_message('error', 'Logging failed: Single order ' . $action_lower . ' action must include order number');
            return false;
        }
        
        // Ensure Philippine timezone is set
        date_default_timezone_set('Asia/Manila');
        
        // Get current time in Philippines
        $current_time = time();
        $current_datetime = date('Y-m-d H:i:s', $current_time);
        
        // Prepare log data
        $log_data = [
            'user_id' => $this->CI->session->userdata('id'),
            'username' => $this->CI->session->userdata('username'),
            'action' => ucfirst($action), // Capitalize first letter
            'description' => $module . ': ' . $description,
            'created_at' => $current_time,
            'timestamp' => $current_datetime
        ];
        
        // Add the log
        return $this->CI->model_logs->create($log_data);
    }
} 