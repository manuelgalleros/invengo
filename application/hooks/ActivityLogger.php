<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ActivityLogger Hook
 * 
 * Records user activities in the application
 */
class ActivityLogger {
    
    private $CI;

    /**
     * Log user activity
     *
     * @return void
     */
    public function log_activity() 
    {
        // Get CI instance
        $this->CI =& get_instance();
        
        // Skip logging for certain controllers/methods
        if ($this->should_skip_logging()) {
            return;
        }
        
        // Make sure we have a logged-in user and the model is loaded
        if (!$this->CI->session->userdata('id')) {
            return;
        }
        
        // Load activity model if not already loaded
        if (!$this->CI->load->is_loaded('model_logs')) {
            $this->CI->load->model('model_logs');
        }
        
        // Get controller and method
        $controller = $this->CI->router->fetch_class();
        $method = $this->CI->router->fetch_method();
        
        // Get user data
        $user_id = $this->CI->session->userdata('id');
        $username = $this->CI->session->userdata('username');
        
        // Prepare description based on controller and method
        $description = $this->generate_description($controller, $method);
        
        // Log the activity
        if (!empty($description)) {
            $this->CI->model_logs->create([
                'user_id' => $user_id,
                'description' => $description,
                'created' => time()
            ]);
        }
    }
    
    /**
     * Generate activity description based on controller and method
     *
     * @param string $controller
     * @param string $method
     * @return string
     */
    private function generate_description($controller, $method)
    {
        // Default description is empty
        $description = '';
        
        // Format controller name for readability
        $controller_name = ucfirst($controller);
        
        // Handle common methods
        switch ($method) {
            case 'create':
                $description = "Created a new {$this->singularize($controller)}";
                break;
            case 'update':
                $description = "Updated a {$this->singularize($controller)}";
                break;
            case 'delete':
            case 'remove':
                $description = "Deleted a {$this->singularize($controller)}";
                break;
            case 'archive':
                $description = "Archived a {$this->singularize($controller)}";
                break;
            case 'restore':
                $description = "Restored a {$this->singularize($controller)}";
                break;
            case 'index':
                // Don't log just viewing a page
                break;
            default:
                // Try to generate a reasonable description for other methods
                if (strpos($method, 'get') === 0) {
                    // Skip getter methods
                    break;
                }
                $method_name = ucwords(str_replace('_', ' ', $method));
                $description = "{$method_name} in {$controller_name}";
                break;
        }
        
        return $description;
    }
    
    /**
     * Convert plural controller name to singular
     *
     * @param string $word
     * @return string
     */
    private function singularize($word)
    {
        $last_char = strtolower($word[strlen($word) - 1]);
        
        if ($last_char == 's') {
            return substr($word, 0, -1);
        }
        
        return $word;
    }
    
    /**
     * Check if logging should be skipped for current request
     *
     * @return bool
     */
    private function should_skip_logging()
    {
        $controller = $this->CI->router->fetch_class();
        $method = $this->CI->router->fetch_method();
        
        // Skip these controllers completely
        $skip_controllers = array('auth', 'logs', 'dashboard');
        
        // Skip these methods for any controller
        $skip_methods = array('index', 'fetch', 'get', 'view', 'fetchlogs');
        
        if (in_array(strtolower($controller), $skip_controllers)) {
            return true;
        }
        
        if (in_array(strtolower($method), $skip_methods)) {
            return true;
        }
        
        // Skip AJAX requests that are just fetching data
        if ($this->CI->input->is_ajax_request() && 
            (strpos(strtolower($method), 'get') === 0 || 
             strpos(strtolower($method), 'fetch') === 0)) {
            return true;
        }
        
        return false;
    }
} 