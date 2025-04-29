<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model for activity logging
 */
class Model_logs extends CI_Model
{
    /**
     * Activity table name
     */
    private $_table = 'activity_logs';

    /**
     * Initialize the model
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        
        // Set Philippine timezone for all date/time operations
        date_default_timezone_set('Asia/Manila');
    }
    
    /**
     * Create new activity log
     *
     * @param array $data
     * @return boolean
     */
    public function create($data)
    {
        if ($data) {
            // Make sure all required fields are set
            if (!isset($data['username'])) {
                $data['username'] = $this->session->userdata('username') ?? 'system';
            }
            
            if (!isset($data['action'])) {
                $data['action'] = 'Other';
            }
            
            // Ensure Philippine timezone is set
            date_default_timezone_set('Asia/Manila');
            
            if (!isset($data['created_at'])) {
                $data['created_at'] = time();
            }
            
            if (!isset($data['timestamp'])) {
                $data['timestamp'] = date('Y-m-d H:i:s');
            }
            
            $this->db->insert($this->_table, $data);
            return ($this->db->affected_rows() > 0) ? true : false;
        }
        return false;
    }
    
    /**
     * Format a timestamp to Philippine date format
     * 
     * @param int $timestamp Unix timestamp
     * @param string $format Date format (default: F j, Y h:i A)
     * @return string Formatted date string
     */
    private function _format_date($timestamp, $format = 'F j, Y h:i A')
    {
        // Ensure Philippine timezone is set
        date_default_timezone_set('Asia/Manila');
        
        // Return formatted date
        return date($format, $timestamp);
    }
    
    /**
     * Get all activities with pagination
     *
     * @param int $limit
     * @param int $offset
     * @param string $search
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public function get_activities($limit = 10, $offset = 0, $search = '', $start_date = '', $end_date = '')
    {
        // Select fields
        $this->db->select('a.id, a.user_id, a.username, a.action, a.description, a.created_at, a.timestamp, u.username as user_username, u.firstname as user_firstname, u.lastname as user_lastname');
        
        // Join with users table
        $this->db->from($this->_table . ' as a');
        $this->db->join('users as u', 'u.id = a.user_id', 'left');
        
        // Apply search if provided
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('a.description', $search);
            $this->db->or_like('a.username', $search);
            $this->db->or_like('a.action', $search);
            $this->db->or_like('u.username', $search);
            $this->db->or_like('u.firstname', $search);
            $this->db->or_like('u.lastname', $search);
            $this->db->group_end();
        }
        
        // Apply date range if provided
        if (!empty($start_date)) {
            // Ensure Philippine timezone
            date_default_timezone_set('Asia/Manila');
            
            $start_timestamp = strtotime($start_date . ' 00:00:00');
            $this->db->where('a.created_at >=', $start_timestamp);
            
            if (!empty($end_date)) {
                $end_timestamp = strtotime($end_date . ' 23:59:59');
                $this->db->where('a.created_at <=', $end_timestamp);
            }
        }
        
        // Count total records for pagination
        $count_sql = "SELECT COUNT(*) as count FROM {$this->_table} a LEFT JOIN users u ON u.id = a.user_id";
        
        $where_clauses = array();
        
        // Add search conditions if provided
        if (!empty($search)) {
            $search_clauses = array();
            $search_value = $this->db->escape_like_str($search);
            $search_clauses[] = "a.description LIKE '%{$search_value}%'";
            $search_clauses[] = "a.username LIKE '%{$search_value}%'";
            $search_clauses[] = "a.action LIKE '%{$search_value}%'";
            $search_clauses[] = "u.username LIKE '%{$search_value}%'";
            $search_clauses[] = "u.firstname LIKE '%{$search_value}%'";
            $search_clauses[] = "u.lastname LIKE '%{$search_value}%'";
            
            $where_clauses[] = "(" . implode(" OR ", $search_clauses) . ")";
        }
        
        // Add date range if provided
        if (!empty($start_date)) {
            // Ensure Philippine timezone
            date_default_timezone_set('Asia/Manila');
            
            $start_timestamp = strtotime($start_date . ' 00:00:00');
            $where_clauses[] = "a.created_at >= {$start_timestamp}";
            
            if (!empty($end_date)) {
                $end_timestamp = strtotime($end_date . ' 23:59:59');
                $where_clauses[] = "a.created_at <= {$end_timestamp}";
            }
        }
        
        // Add WHERE clause if conditions exist
        if (!empty($where_clauses)) {
            $count_sql .= " WHERE " . implode(" AND ", $where_clauses);
        }
        
        $count_query = $this->db->query($count_sql);
        $count_result = $count_query->row();
        $total_records = $count_result->count;
        
        // Apply limit and offset for pagination
        $this->db->limit($limit, $offset);
        
        // Order by most recent first
        $this->db->order_by('a.created_at', 'DESC');
        
        // Execute the query
        $query = $this->db->get();
        $result = $query->result();
        
        // Format the data for display
        $activities = [];
        foreach ($result as $row) {
            $user_name = '';
            if (!empty($row->user_firstname) || !empty($row->user_lastname)) {
                $user_name = trim($row->user_firstname . ' ' . $row->user_lastname);
            } else {
                // If no user record found, use the username from activity logs
                $user_name = $row->username;
            }
            
            // Ensure Philippine timezone is set for date formatting
            date_default_timezone_set('Asia/Manila');
            
            $activities[] = [
                'id' => $row->id,
                'user' => $user_name,
                'description' => $row->description,
                'action' => $row->action,
                'date' => $this->_format_date($row->created_at),
                'timestamp' => $row->timestamp
            ];
        }
        
        return [
            'activities' => $activities,
            'count' => $total_records
        ];
    }
    
    /**
     * Clear all activity logs
     *
     * @return boolean
     */
    public function clear_all()
    {
        $this->db->truncate($this->_table);
        return true;
    }
    
} 