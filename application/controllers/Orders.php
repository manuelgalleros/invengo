<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Orders';
        $this->load->model('model_users');
		$this->load->model('model_orders');
		$this->load->model('model_products');
		$this->load->model('model_company');
		$this->load->helper('activity');
	}

	/* 
	* It only redirects to the manage order page
	*/
	public function index()
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->data['page_title'] = 'Manage Orders';
        $user_id = $this->session->userdata('id');
        $this->data['user_data'] = $this->model_users->getUserData($user_id);
		$this->render_template('orders/index', $this->data);		
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchOrdersData()
	{
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$per_page = 10;
		$search = $this->input->get('search') ? $this->input->get('search') : '';

		// Get orders with pagination
		$this->db->select('orders.*, users.firstname, users.lastname');
		$this->db->from('orders');
		$this->db->join('users', 'users.id = orders.user_id', 'left');
		
		// Check if is_archived column exists before using it
		$this->db->query("SHOW COLUMNS FROM orders LIKE 'is_archived'");
		$is_archived_exists = $this->db->affected_rows() > 0;
		
		// Only filter by is_archived if the column exists
		if ($is_archived_exists) {
			$this->db->where('orders.is_archived', 0);
		}
		
		if(!empty($search)) {
			$this->db->group_start();
			$this->db->like('orders.order_no', $search);
			$this->db->or_like('users.firstname', $search);
			$this->db->or_like('users.lastname', $search);
			$this->db->or_like('orders.payment_method', $search);
			$this->db->group_end();
		}

		// Count total rows for pagination
		$total_rows = $this->db->count_all_results('', false);
		$total_pages = ceil($total_rows / $per_page);

		// Get paginated results
		$this->db->limit($per_page, ($page - 1) * $per_page);
		$this->db->order_by('orders.id', 'DESC');
		$query = $this->db->get();
		$orders = $query->result_array();

		$data = array();
		foreach ($orders as $order) {
			$count_total_item = $this->model_orders->countOrderItem($order['id']);
			// Format date using Philippine Standard Time
			$date_time = $this->formatPhilippineDateTime($order['date_time']);
			
			// Create a user name from firstname and lastname
			$user_name = '';
			if(!empty($order['firstname']) || !empty($order['lastname'])) {
				$user_name = $order['firstname'] . ' ' . $order['lastname'];
			}

			$data[] = array(
				'id' => $order['id'],
				'order_no' => $order['order_no'],
				'date_time' => $date_time,
				'total_products' => $count_total_item,
				'net_amount' => $order['net_amount'],
				'payment_method' => $order['payment_method'] ? ucfirst(strtolower($order['payment_method'])) : '',
				'user_name' => $user_name,
				'paid_status' => $order['paid_status']
			);
		}

		// Calculate range info
		$start = ($page - 1) * $per_page + 1;
		$end = min($start + $per_page - 1, $total_rows);
		$range_info = "Showing $start to $end of $total_rows orders";

		// Generate pagination HTML
		$pagination = '';
		
		// First page button
		$first_disabled = ($page <= 1) ? 'disabled' : '';
		$pagination .= '<li class="page-item ' . $first_disabled . '">
							<a href="#" class="page-link" data-page="1">
								<i class="ti ti-chevrons-left"></i>
							</a>
						</li>';
		
		// Previous button
		$prev_disabled = ($page <= 1) ? 'disabled' : '';
		$pagination .= '<li class="page-item ' . $prev_disabled . '">
							<a href="#" class="page-link" data-page="' . ($page - 1) . '">
								Previous
							</a>
						</li>';

		// Page numbers - show only up to 5 pages
		$startPage = max(1, $page - 2);
		$endPage = min($total_pages, $page + 2);

		// Adjust for edge cases
		if ($page <= 3) {
			$endPage = min(5, $total_pages);
		}
		if ($page > $total_pages - 2) {
			$startPage = max($total_pages - 4, 1);
		}

		// Generate page number links
		for ($i = $startPage; $i <= $endPage; $i++) {
			$active = ($i == $page) ? 'active' : '';
			$pagination .= '<li class="page-item ' . $active . '">
							<a href="#" class="page-link" data-page="' . $i . '">' . $i . '</a>
						</li>';
		}

		// Next button
		$next_disabled = ($page >= $total_pages) ? 'disabled' : '';
		$pagination .= '<li class="page-item ' . $next_disabled . '">
							<a href="#" class="page-link" data-page="' . ($page + 1) . '">
								Next
							</a>
						</li>';
		
		// Last page button
		$last_disabled = ($page >= $total_pages) ? 'disabled' : '';
		$pagination .= '<li class="page-item ' . $last_disabled . '">
							<a href="#" class="page-link" data-page="' . $total_pages . '">
								<i class="ti ti-chevrons-right"></i>
							</a>
						</li>';

		$result = array(
			'data' => $data,
			'pagination' => $pagination,
			'range_info' => $range_info
		);

		echo json_encode($result);
	}

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{
		if(!in_array('createOrder', $this->permission)) {
            if($this->input->is_ajax_request()) {
                echo json_encode(array('success' => false, 'message' => 'You do not have permission to create orders'));
                return;
            }
            redirect('dashboard', 'refresh');
        }

		$this->data['page_title'] = 'Create New Order';
		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
        // Debug received data for AJAX requests
        if($this->input->is_ajax_request()) {
            $post_data = $this->input->post();
            log_message('debug', 'AJAX Order Create - POST data: ' . json_encode($post_data));
        }
        
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$order_id = $this->model_orders->create();
        	
        	if($order_id) {
                // Get the order details for logging and response
                $order_data = $this->model_orders->getOrdersData($order_id);
                
                // Log successful order creation with consistently formatted order number
                log_activity(
                    'create',
                    'Orders',
                    'Created new order #' . $order_data['order_no'],
                    true
                );
                
                // Check if this is an AJAX request
                if($this->input->is_ajax_request()) {
                    // Response already has order data
                    $response = array(
                        'success' => true,
                        'message' => 'Order successfully created',
                        'order_id' => $order_id,
                        'order_no' => $order_data['order_no'],
                        'paid_status' => $order_data['paid_status']
                    );
                    echo json_encode($response);
                    return;
                }
                
                // Standard form submission (fallback)
        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('orders/update/'.$order_id, 'refresh');
        	}
        	else {
                // Get any available information about the attempted order
                $attempted_info = "";
                if ($this->input->post('customer_name')) {
                    $attempted_info = " for customer " . $this->input->post('customer_name');
                }

                log_activity(
                    'create',
                    'Orders',
                    'Failed to create new order' . $attempted_info,
                    false
                );
                
                // Check if this is an AJAX request
                if($this->input->is_ajax_request()) {
                    $response = array(
                        'success' => false,
                        'message' => 'Error occurred while creating order'
                    );
                    echo json_encode($response);
                    return;
                }
                
                // Standard form submission (fallback)
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('orders/create/', 'refresh');
        	}
        }
        else {
            // If this is an AJAX request and there's validation error
            if($this->input->is_ajax_request()) {
                $response = array(
                    'success' => false,
                    'message' => strip_tags(validation_errors())
                );
                echo json_encode($response);
                return;
            }
            
            // Load the view
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$this->data['products'] = $this->model_products->getActiveProductData();  
            $user_id = $this->session->userdata('id');
            $this->data['user_data'] = $this->model_users->getUserData($user_id);

            $this->render_template('orders/create', $this->data);
        }	
	}

	/*
	* It gets the product id passed from the ajax method.
	* It checks retrieves the particular product data from the product id 
	* and return the data into the json format.
	*/
	public function getProductValueById()
	{
		$product_id = $this->input->post('product_id');
		if($product_id) {
			$product_data = $this->model_products->getProductData($product_id);
			echo json_encode($product_data);
		}
	}

	/*
	* It gets the all the active product inforamtion from the product table 
	* This function is used in the order page, for the product selection in the table
	* The response is return on the json format.
	*/
	public function getTableProductRow()
	{
		$products = $this->model_products->getActiveProductData();
		echo json_encode($products);
	}

	/*
	* It gets the order data by order ID and returns it in JSON format
	* This function is used in the order edit modal
	*/
	public function get_order_data()
	{
		$order_id = $this->input->post('order_id');
		
		if(!$order_id) {
			echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
			return;
		}
		
		// Get the order data
		$orders_data = $this->model_orders->getOrdersData($order_id);
		
		if(!$orders_data) {
			echo json_encode(['success' => false, 'message' => 'Order not found']);
			return;
		}
		
		// Get the order items
		$orders_item = $this->model_orders->getOrdersItemData($orders_data['id']);
		
		$result = array(
			'success' => true,
			'order' => $orders_data,
			'order_item' => $orders_item
		);
		
		echo json_encode($result);
	}

	/*
	* Fetch all order IDs that match the search criteria
	* This is used for the "select all" functionality across pages
	*/
	public function get_all_order_ids()
	{
		$search = $this->input->get('search');
		
		// Fetch all order IDs based on search criteria
		$this->db->select('id');
		$this->db->from('orders');
		
		// Check if is_archived column exists before using it
		$this->db->query("SHOW COLUMNS FROM orders LIKE 'is_archived'");
		$is_archived_exists = $this->db->affected_rows() > 0;
		
		// Only filter by is_archived if the column exists
		if ($is_archived_exists) {
			$this->db->where('is_archived', 0);
		}
		
		// Apply search filter
		if(!empty($search)) {
			$this->db->join('users', 'users.id = orders.user_id', 'left');
			$this->db->group_start();
			$this->db->like('orders.order_no', $search);
			$this->db->or_like('users.firstname', $search);
			$this->db->or_like('users.lastname', $search);
			$this->db->or_like('orders.payment_method', $search);
			$this->db->group_end();
		}
		
		$query = $this->db->get();
		$results = $query->result_array();
		
		// Extract just the IDs
		$order_ids = array_column($results, 'id');
		
		echo json_encode(['order_ids' => $order_ids]);
	}

	/*
	* Fetch all archived order IDs that match the search criteria
	* This is used for the "select all" functionality across pages in the archive view
	*/
	public function get_all_archived_order_ids()
	{
		$search = $this->input->get('search');
		
		// Check if is_archived column exists
		$this->db->query("SHOW COLUMNS FROM orders LIKE 'is_archived'");
		$is_archived_exists = $this->db->affected_rows() > 0;
		
		if (!$is_archived_exists) {
			echo json_encode(['order_ids' => []]);
			return;
		}
		
		// Fetch all archived order IDs based on search criteria
		$this->db->select('id');
		$this->db->from('orders');
		$this->db->where('is_archived', 1);
		
		// Apply search filter
		if(!empty($search)) {
			$this->db->join('users', 'users.id = orders.user_id', 'left');
			$this->db->group_start();
			$this->db->like('orders.order_no', $search);
			$this->db->or_like('users.firstname', $search);
			$this->db->or_like('users.lastname', $search);
			$this->db->or_like('orders.payment_method', $search);
			$this->db->group_end();
		}
		
		$query = $this->db->get();
		$results = $query->result_array();
		
		// Extract just the IDs
		$order_ids = array_column($results, 'id');
		
		echo json_encode(['order_ids' => $order_ids]);
	}

	/*
	* If the validation is not valid, then it redirects to the edit orders page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id)
	{
		if(!in_array('updateOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		if(!$id) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Update Order';

		$this->form_validation->set_rules('product[]', 'Product name', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	$update = $this->model_orders->update($id);
        	
        	if($update == true) {
                // Get order data for logging
                $order_data = $this->model_orders->getOrdersData($id);
                
                // Log successful order update
                log_activity(
                    'update',
                    'Orders',
                    'Updated order #' . $order_data['order_no'],
                    true
                );
                
        		$this->session->set_flashdata('success', 'Successfully updated');
        		redirect('orders/update/'.$id, 'refresh');
        	}
        	else {
                // Get order data for logging if available
                $order_data = $this->model_orders->getOrdersData($id);
                $order_identifier = isset($order_data['order_no']) ? $order_data['order_no'] : $id;
                
                // Log failed order update
                log_activity(
                    'update',
                    'Orders',
                    'Failed to update order #' . $order_identifier,
                    false
                );
                
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('orders/update/'.$id, 'refresh');
        	}
        }
        else {
            // false case
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$result = array();
        	$orders_data = $this->model_orders->getOrdersData($id);

    		$result['order'] = $orders_data;
    		$orders_item = $this->model_orders->getOrdersItemData($orders_data['id']);

    		foreach($orders_item as $k => $v) {
    			$result['order_item'][] = $v;
    		}

    		$this->data['order_data'] = $result;

        	$this->data['products'] = $this->model_products->getActiveProductData();      	

            $user_id = $this->session->userdata('id');
            $this->data['user_data'] = $this->model_users->getUserData($user_id);

            $this->render_template('orders/edit', $this->data);
        }
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		if(!in_array('deleteOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $order_id = $this->input->post('order_id');
        log_message('debug', 'Remove method called with order ID: ' . (is_array($order_id) ? json_encode($order_id) : $order_id));
        
        if($order_id) {
            // Handle both single ID and array of IDs for deletion
            if(is_array($order_id)) {
                // Multiple orders case
                $delete = $this->model_orders->remove($order_id);
                if($delete == true) {
                    // Log successful deletion of multiple orders
                    log_activity(
                        'delete',
                        'Orders',
                        'Deleted ' . count($order_id) . ' orders',
                        true
                    );
                    
                    $response['success'] = true;
                    $response['messages'] = "Successfully removed";
                    $response['order_count'] = count($order_id);
                    
                    log_message('debug', 'Deleted ' . count($order_id) . ' orders');
                }
                else {
                    // Log failed deletion
                    log_activity(
                        'delete',
                        'Orders',
                        'Failed to delete ' . count($order_id) . ' orders',
                        false
                    );
                    
                    $response['success'] = false;
                    $response['messages'] = "Error in the database while removing the orders";
                    log_message('error', 'Delete failed with database error: ' . $this->db->error()['message']);
                }
            } else {
                // Single order case
                // Get order data before deletion for logging
                $order_data = $this->model_orders->getOrdersData($order_id);
                
                // Check if order data exists
                if(!$order_data) {
                    $response['success'] = false;
                    $response['messages'] = "Order not found. It may have been deleted.";
                    log_message('error', 'Delete failed: Order ID ' . $order_id . ' not found');
                    echo json_encode($response);
                    return;
                }
                
                // Debug log the order data for troubleshooting
                log_message('debug', 'ORDER DATA for deletion: ' . json_encode($order_data));
                $order_no = isset($order_data['order_no']) ? $order_data['order_no'] : '[unknown]';
                
                $delete = $this->model_orders->remove($order_id);
                if($delete == true) {
                    // Log successful order deletion with explicit order number
                    log_message('info', "Successfully deleted order " . $order_no);
                    
                    // Make sure we're including the order number in the log description
                    $log_description = "Deleted order " . $order_no;
                    log_activity(
                        'delete',
                        'Orders',
                        $log_description,
                        true
                    );
                    
                    $response['success'] = true;
                    $response['messages'] = "Successfully removed";
                    $response['order_no'] = $order_no;
                    
                    log_message('debug', 'Deleted order: ' . $order_no);
                }
                else {
                    // Log failed order deletion
                    log_message('error', "Failed to delete order " . $order_no);
                    log_activity(
                        'delete',
                        'Orders',
                        'Failed to delete order ' . $order_no,
                        false
                    );
                    
                    $response['success'] = false;
                    $response['messages'] = "Error in the database while removing the order";
                    log_message('error', 'Delete failed with database error: ' . $this->db->error()['message']);
                }
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Please refresh the page again!!";
            log_message('error', 'Delete failed: No order ID provided');
        }

        echo json_encode($response);
	}

	/*
	* Archives the order
	*/
	public function archive()
	{
		// Check for proper permissions
		if(!in_array('deleteOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		// Log that archive method was called
		error_log("ARCHIVE METHOD CALLED");

		// Handle AJAX request
		if ($this->input->is_ajax_request()) {
			$order_id = $this->input->post('order_id');
			error_log("Order ID received: " . (is_array($order_id) ? json_encode($order_id) : $order_id));
			
			if($order_id) {
				// Handle different types of order_id input (array or single value)
				if(is_array($order_id)) {
					// Multiple orders case
					$archive = $this->model_orders->archive($order_id);
					if($archive === true) {
						// Log successful archival of multiple orders
						error_log("Archiving multiple orders: " . count($order_id) . " orders");
						log_activity(
							'archive',
							'Orders',
							'Archived ' . count($order_id) . ' orders',
							true
						);
						
						$response['success'] = true;
						$response['messages'] = "Successfully archived";
						$response['order_count'] = count($order_id);
						log_message('debug', 'Archived ' . count($order_id) . ' orders');
					}
					else if($archive === false) {
						// Log failed archival
						error_log("Failed to archive multiple orders: Column missing");
						log_activity(
							'archive',
							'Orders',
							'Failed to archive ' . count($order_id) . ' orders',
							false
						);
						
						$response['success'] = false;
						$response['messages'] = "Archive feature not available (is_archived column missing)";
						log_message('error', 'Archive failed: is_archived column is missing in orders table');
					}
					else {
						$response['success'] = false;
						$response['messages'] = "Error in the database while archiving the orders";
						log_message('error', 'Archive failed with unknown error: ' . $this->db->error()['message']);
					}
				} else {
					// Single order case
					// Get order data before archiving for logging
					$order_data = $this->model_orders->getOrdersData($order_id);
					if (!$order_data) {
						$response['success'] = false;
						$response['messages'] = "Order not found. It may have been deleted.";
						log_message('error', 'Archive failed: Order ID ' . $order_id . ' not found');
						echo json_encode($response);
						return;
					}
					
					// Debug log the order data for troubleshooting
					error_log('ORDER DATA for archiving: ' . json_encode($order_data));
					$order_no = isset($order_data['order_no']) ? $order_data['order_no'] : '[unknown]';
					
					$archive = $this->model_orders->archive($order_id);
					if ($archive === true) {
						// Log successful order archival with explicit order number
						error_log("Successfully archived order " . $order_no);
						
						// Make sure we're including the order number in the log description
						$log_description = "Archived order " . $order_no;
						log_activity(
							'archive',
							'Orders',
							$log_description,
							true
						);
						
						$response['success'] = true;
						$response['messages'] = "Successfully archived";
						$response['order_no'] = $order_no;
						log_message('debug', 'Archived order: ' . $order_no);
					}
					else if($archive === false) {
						// Log failed order archival
						error_log("Failed to archive single order " . $order_no . ": Column missing");
						log_activity(
							'archive',
							'Orders',
							'Failed to archive order ' . $order_no,
							false
						);
						
						$response['success'] = false;
						$response['messages'] = "Archive feature not available (is_archived column missing)";
						log_message('error', 'Archive failed: is_archived column is missing in orders table');
					}
					else {
						$response['success'] = false;
						$response['messages'] = "Error in the database while archiving the order";
						log_message('error', 'Archive failed with unknown error: ' . $this->db->error()['message']);
					}
				}
			}
			else {
				$response['success'] = false;
				$response['messages'] = "Please refresh the page again!!";
				log_message('error', 'Archive failed: No order ID provided');
			}

			echo json_encode($response);
			return;
		}
		
		// For GET requests, load the archived orders view
		$user_id = $this->session->userdata('id');
		$this->data['user_data'] = $this->model_users->getUserData($user_id);
		$this->data['page_title'] = 'Archived Orders';
		$this->render_template('orders/archive', $this->data);
	}

	public function restore()
	{
		if(!in_array('updateOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $order_id = $this->input->post('order_id');
        
        if($order_id) {
            // Handle both single ID and array of IDs for restoration
            if(is_array($order_id)) {
                // For multiple orders
                $restore = $this->model_orders->restore($order_id);
                if($restore === true) {
                    // Log successful restoration of multiple orders
                    log_activity(
                        'restore',
                        'Orders',
                        'Restored ' . count($order_id) . ' orders',
                        true
                    );
                    
                    $response['success'] = true;
                    $response['messages'] = "Successfully restored";
                    $response['order_count'] = count($order_id);
                    
                    log_message('debug', 'Restored ' . count($order_id) . ' orders');
                }
                else if($restore === false) {
                    // Log failed restoration
                    log_activity(
                        'restore',
                        'Orders',
                        'Failed to restore ' . count($order_id) . ' orders',
                        false
                    );
                    
                    $response['success'] = false;
                    $response['messages'] = "Restore feature not available (is_archived column missing)";
                    log_message('error', 'Restore failed: is_archived column is missing in orders table');
                }
                else {
                    $response['success'] = false;
                    $response['messages'] = "Error in the database while restoring the orders";
                    log_message('error', 'Restore failed with unknown error: ' . $this->db->error()['message']);
                }
            } else {
                // Single order case - get order data before restoration for logging
                $order_data = $this->model_orders->getOrdersData($order_id);
                
                // Check if order data exists
                if(!$order_data) {
                    $response['success'] = false;
                    $response['messages'] = "Order not found. It may have been deleted.";
                    log_message('error', 'Restore failed: Order ID ' . $order_id . ' not found');
                    echo json_encode($response);
                    return;
                }
                
                // Debug log the order data for troubleshooting
                error_log('ORDER DATA for restoring: ' . json_encode($order_data));
                $order_no = isset($order_data['order_no']) ? $order_data['order_no'] : '[unknown]';
                
                $restore = $this->model_orders->restore($order_id);
                if($restore === true) {
                    // Log successful order restoration with explicit order number
                    error_log("Successfully restored order " . $order_no);
                    
                    // Make sure we're including the order number in the log description
                    $log_description = "Restored order " . $order_no;
                    log_activity(
                        'restore',
                        'Orders',
                        $log_description,
                        true
                    );
                    
                    $response['success'] = true;
                    $response['messages'] = "Successfully restored";
                    $response['order_no'] = $order_no;
                    
                    log_message('debug', 'Restored order: ' . $order_no);
                }
                else if($restore === false) {
                    // Log failed order restoration
                    log_activity(
                        'restore',
                        'Orders',
                        'Failed to restore order ' . $order_no,
                        false
                    );
                    
                    $response['success'] = false;
                    $response['messages'] = "Restore feature not available (is_archived column missing)";
                    log_message('error', 'Restore failed: is_archived column is missing in orders table');
                }
                else {
                    $response['success'] = false;
                    $response['messages'] = "Error in the database while restoring the order";
                    log_message('error', 'Restore failed with unknown error: ' . $this->db->error()['message']);
                }
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Please refresh the page again!!";
            log_message('error', 'Restore failed: No order ID provided');
        }

        echo json_encode($response);
	}

	/*
	* It gets the product id and fetch the order data. 
	* The order print logic is done here 
	*/
	public function receipt($order_no)
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
		if($order_no) {
			// Get order ID by order_no
			$this->db->where('order_no', $order_no);
			$order_query = $this->db->get('orders');
			
			if($order_query->num_rows() == 0) {
				$this->session->set_flashdata('error', 'Order not found');
				redirect('orders', 'refresh');
			}
			
			$order_row = $order_query->row();
			$order_id = $order_row->id;
			
			$order_data = $this->model_orders->getOrdersData($order_id);
			$orders_items = $this->model_orders->getOrdersItemData($order_id);
			$company_info = $this->model_company->getCompanyData(1);

			$order_date = date('d/m/Y', $order_data['date_time']);
			$paid_status = ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";

			$html = '<!DOCTYPE html>
			<html lang="en">
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Receipt - '.$order_no.'</title>
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <link rel="shortcut icon" href="'.base_url('assets/images/FullLogo_Transparent.png').'">
			  <link rel="preconnect" href="https://fonts.googleapis.com">
			  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
			  <link rel="stylesheet" href="'.base_url('assets/css/app.min.css').'" rel="stylesheet" type="text/css">
			  
			  <style>
				body {
				  font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
				  line-height: 1.5;
				  color: #333;
				  background-color: #f8f9fa;
				  margin: 0;
				  padding: 0;
				  -webkit-print-color-adjust: exact !important;
				  print-color-adjust: exact !important;
				}
				.receipt-container {
				  max-width: 800px;
				  margin: 0 auto;
				  background: white;
				  padding: 40px;
				  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
				  border-radius: 0.5rem;
				}
				.receipt-header {
				  border-bottom: 1px solid #e9ecef;
				  margin-bottom: 30px;
				  padding-bottom: 20px;
				}
				.company-name {
				  font-weight: 700;
				  font-size: 1.75rem;
				  color: #212529;
				  margin-bottom: 5px;
				}
				.receipt-title {
				  font-size: 1.2rem;
				  font-weight: 600;
				  color: #495057;
				  margin-bottom: 20px;
				}
				.receipt-info {
				  margin-bottom: 30px;
				}
				.info-block {
				  margin-bottom: 15px;
				}
				.info-label {
				  font-weight: 600;
				  color: #6c757d;
				  margin-right: 10px;
				}
				.info-value {
				  font-weight: 500;
				  color: #212529;
				}
				.receipt-table {
				  width: 100%;
				  margin-bottom: 30px;
				  border-collapse: collapse;
				}
				.receipt-table th {
				  background-color: #f8f9fa;
				  padding: 12px 15px;
				  font-weight: 600;
				  text-align: left;
				  color: #495057;
				  border-bottom: 2px solid #dee2e6;
				}
				.receipt-table td {
				  padding: 12px 15px;
				  border-bottom: 1px solid #e9ecef;
				}
				.receipt-table tr:last-child td {
				  border-bottom: none;
				}
				.receipt-total {
				  font-weight: 700;
				  background-color: #f8f9fa;
				  border-top: 2px solid #dee2e6;
				}
				.receipt-footer {
				  margin-top: 40px;
				  text-align: center;
				  font-size: 0.875rem;
				  color: #6c757d;
				  border-top: 1px solid #e9ecef;
				  padding-top: 20px;
				}
				.badge {
				  display: inline-block;
				  padding: 0.35em 0.65em;
				  font-size: 0.75em;
				  font-weight: 600;
				  line-height: 1;
				  text-align: center;
				  white-space: nowrap;
				  vertical-align: baseline;
				  border-radius: 0.25rem;
				}
				.badge-success {
				  color: #fff;
				  background-color: #198754;
				}
				.badge-danger {
				  color: #fff;
				  background-color: #dc3545;
				}
				.totals-section {
				  margin-left: auto;
				  width: 50%;
				}
				.totals-row {
				  display: flex;
				  justify-content: space-between;
				  padding: 8px 0;
				  border-bottom: 1px solid #e9ecef;
				}
				.totals-row:last-child {
				  border-bottom: none;
				  font-weight: 700;
				}
				.total-label {
				  color: #6c757d;
				  font-weight: 600;
				}
				.total-value {
				  text-align: right;
				}
				.receipt-number {
				  font-size: 1rem;
				  font-weight: 600;
				  padding: 6px 12px;
				  background-color: #f8f9fa;
				  border-radius: 4px;
				  display: inline-block;
				  margin-bottom: 10px;
				}
				@media print {
				  body {
					padding: 0;
					background: white;
				  }
				  .receipt-container {
					box-shadow: none;
					padding: 20px;
					max-width: 100%;
				  }
				}
			  </style>
			</head>
			<body onload="window.print();">
				<div class="receipt-container">
					<div class="receipt-header">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<div class="company-logo" style="margin-bottom: 15px;">
									<img src="'.base_url('assets/images/invengo.png').'" alt="'.$company_info['company_name'].'" height="60" width="80%">
								</div>
								<div class="receipt-title">Payment Receipt</div>
							</div>
							<div class="text-end">
								<div class="receipt-number">#'.$order_data['order_no'].'</div>
								<div class="text-muted">Date: '.$order_date.'</div>
							</div>
						</div>
					</div>
					
					<div class="receipt-info row">
						<div class="col-md-6">
							<div class="info-block">
								<span class="info-label">Payment Method:</span>
								<span class="info-value">'.ucfirst($order_data['payment_method']).'</span>
							</div>
							<div class="info-block">
								<span class="info-label">Status:</span>
								<span class="info-value">
									'.($paid_status == "Paid" ? 
										'<span class="badge badge-soft-success">Paid</span>' : 
										'<span class="badge badge-danger">Unpaid</span>').'
								</span>
							</div>
						</div>
					</div>
					
					<table class="receipt-table">
						<thead>
							<tr>
								<th>Item</th>
								<th style="text-align: center;">Quantity</th>
								<th style="text-align: right;">Price</th>
								<th style="text-align: right;">Amount</th>
							</tr>
						</thead>
						<tbody>';

						foreach ($orders_items as $k => $v) {
							$product_data = $this->model_products->getProductData($v['product_id']); 
							
							// Handle case where product data is null (product might have been deleted)
							$product_name = isset($product_data['name']) ? $product_data['name'] : 'Product #' . $v['product_id'] . ' (deleted)';
							
							$html .= '<tr>
								<td>'.$product_name.'</td>
								<td style="text-align: center;">'.$v['qty'].'</td>
								<td style="text-align: right;">₱'.number_format(floatval($v['rate']), 2).'</td>
								<td style="text-align: right;">₱'.number_format(floatval($v['amount']), 2).'</td>
							</tr>';
						}
						
						$html .= '</tbody>
					</table>
					
					<div class="totals-section">
						<div class="totals-row">
							<div class="total-label">Gross Amount:</div>
							<div class="total-value">₱'.number_format(floatval($order_data['gross_amount']), 2).'</div>
						</div>';

						if($order_data['service_charge'] > 0) {
							$html .= '<div class="totals-row">
								<div class="total-label">Service Charge ('.$order_data['service_charge_rate'].'%):</div>
								<div class="total-value">₱'.number_format(floatval($order_data['service_charge']), 2).'</div>
							</div>';
						}

						if($order_data['vat_charge'] > 0) {
							$html .= '<div class="totals-row">
								<div class="total-label">VAT ('.$order_data['vat_charge_rate'].'%):</div>
								<div class="total-value">₱'.number_format(floatval($order_data['vat_charge']), 2).'</div>
							</div>';
						}
						
						$html .= '<div class="totals-row">
							<div class="total-label">Discount:</div>
							<div class="total-value">₱'.number_format(floatval($order_data['discount']), 2).'</div>
						</div>
						<div class="totals-row">
							<div class="total-label">Total Amount:</div>
							<div class="total-value">₱'.number_format(floatval($order_data['net_amount']), 2).'</div>
						</div>
					</div>
					
					<div class="receipt-footer">
						<p>Thank you for your purchase!</p>
						<p>'.$company_info['company_name'].' &copy; '.date('Y').'</p>
					</div>
				</div>
			</body>
			</html>';

			echo $html;
		}
	}

	/*
	* Fetches the archived orders data from the orders table
	* this function is called from the datatable ajax function
	*/
	public function fetchArchivedOrdersData()
	{
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$per_page = 10;
		$search = $this->input->get('search') ? $this->input->get('search') : '';

		// Check if is_archived column exists before using it
		$this->db->query("SHOW COLUMNS FROM orders LIKE 'is_archived'");
		$is_archived_exists = $this->db->affected_rows() > 0;
		
		// If column doesn't exist, display a user-friendly message
		if (!$is_archived_exists) {
			$result = array(
				'data' => array(),
				'pagination' => '',
				'range_info' => 'Archive feature not available. Please run the migration to add required columns.',
				'error' => true,
				'message' => 'is_archived column is missing from the orders table. Please run the migration.'
			);
			echo json_encode($result);
			return;
		}

		// Get archived orders with pagination
		$this->db->select('orders.*, users.firstname, users.lastname, archivers.firstname as archiver_firstname, archivers.lastname as archiver_lastname');
		$this->db->from('orders');
		$this->db->join('users', 'users.id = orders.user_id', 'left');
		$this->db->join('users as archivers', 'archivers.id = orders.archived_by', 'left');
		$this->db->where('orders.is_archived', 1); // Only get archived orders
		
		if(!empty($search)) {
			$this->db->group_start();
			$this->db->like('orders.order_no', $search);
			$this->db->or_like('users.firstname', $search);
			$this->db->or_like('users.lastname', $search);
			$this->db->or_like('orders.payment_method', $search);
			$this->db->group_end();
		}

		// Count total rows for pagination
		$total_rows = $this->db->count_all_results('', false);
		$total_pages = ceil($total_rows / $per_page);

		// Get paginated results
		$this->db->limit($per_page, ($page - 1) * $per_page);
		$this->db->order_by('orders.id', 'DESC');
		$query = $this->db->get();
		$orders = $query->result_array();

		$data = array();
		foreach ($orders as $order) {
			$count_total_item = $this->model_orders->countOrderItem($order['id']);
			// Format date using Philippine Standard Time
			$date_time = $this->formatPhilippineDateTime($order['date_time']);
			
			// Create a user name from firstname and lastname
			$user_name = '';
			if(!empty($order['firstname']) || !empty($order['lastname'])) {
				$user_name = $order['firstname'] . ' ' . $order['lastname'];
			}

			$data[] = array(
				'id' => $order['id'],
				'order_no' => $order['order_no'],
				'date_time' => $date_time,
				'total_products' => $count_total_item,
				'net_amount' => $order['net_amount'],
				'payment_method' => $order['payment_method'] ? ucfirst(strtolower($order['payment_method'])) : '',
				'user_name' => $user_name,
				'paid_status' => $order['paid_status'],
				'archived_at' => $order['archived_at'] ? $this->formatPhilippineDateTime($order['archived_at']) : 'N/A',
				'archived_by' => (!empty($order['archiver_firstname']) || !empty($order['archiver_lastname'])) 
					? $order['archiver_firstname'] . ' ' . $order['archiver_lastname']
					: 'N/A'
			);
		}

		// Calculate range info
		$start = ($page - 1) * $per_page + 1;
		$end = min($start + $per_page - 1, $total_rows);
		$range_info = "Showing $start to $end of $total_rows archived orders";

		// Generate pagination HTML
		$pagination = '';
		
		// First page button
		$first_disabled = ($page <= 1) ? 'disabled' : '';
		$pagination .= '<li class="page-item ' . $first_disabled . '">
							<a href="#" class="page-link" data-page="1">
								<i class="ti ti-chevrons-left"></i>
							</a>
						</li>';
		
		// Previous button
		$prev_disabled = ($page <= 1) ? 'disabled' : '';
		$pagination .= '<li class="page-item ' . $prev_disabled . '">
							<a href="#" class="page-link" data-page="' . ($page - 1) . '">
								Previous
							</a>
						</li>';

		// Page numbers - show only up to 5 pages
		$startPage = max(1, $page - 2);
		$endPage = min($total_pages, $page + 2);

		// Adjust for edge cases
		if ($page <= 3) {
			$endPage = min(5, $total_pages);
		}
		if ($page > $total_pages - 2) {
			$startPage = max($total_pages - 4, 1);
		}

		// Generate page number links
		for ($i = $startPage; $i <= $endPage; $i++) {
			$active = ($i == $page) ? 'active' : '';
			$pagination .= '<li class="page-item ' . $active . '">
							<a href="#" class="page-link" data-page="' . $i . '">' . $i . '</a>
						</li>';
		}

		// Next button
		$next_disabled = ($page >= $total_pages) ? 'disabled' : '';
		$pagination .= '<li class="page-item ' . $next_disabled . '">
							<a href="#" class="page-link" data-page="' . ($page + 1) . '">
								Next
							</a>
						</li>';
		
		// Last page button
		$last_disabled = ($page >= $total_pages) ? 'disabled' : '';
		$pagination .= '<li class="page-item ' . $last_disabled . '">
							<a href="#" class="page-link" data-page="' . $total_pages . '">
								<i class="ti ti-chevrons-right"></i>
							</a>
						</li>';

		$result = array(
			'data' => $data,
			'pagination' => $pagination,
			'range_info' => $range_info
		);

		echo json_encode($result);
	}

	/*
    * Format unix timestamp to Philippine Standard Time (GMT+8)
    * Returns date in format: April 17, 2025 02:05 AM
    */
    private function formatPhilippineDateTime($timestamp) {
        // If timestamp is null or empty, return N/A
        if (empty($timestamp)) {
            return 'N/A';
        }
        
        // Set timezone to Philippine Standard Time
        date_default_timezone_set('Asia/Manila');
        
        // Format the date in the required format
        return date('F j, Y h:i A', $timestamp);
    }

	/**
	 * Get product by barcode
	 * Used by the order creation page for barcode scanning
	 */
	public function getProductByBarcode()
	{
		// Check permission
		if(!in_array('createOrder', $this->permission) && !in_array('updateOrder', $this->permission)) {
			echo json_encode(array(
				'success' => false,
				'message' => 'You do not have permission to access this function'
			));
			return;
		}
		
		// Get barcode from post data
		$barcode = $this->input->post('barcode');
		
		// Log the raw input details
		log_message('debug', '--- BEGIN BARCODE LOOKUP ---');
		log_message('debug', 'Raw barcode input value: "' . $barcode . '"');
		log_message('debug', 'Barcode type: ' . gettype($barcode));
		log_message('debug', 'Barcode length: ' . strlen($barcode));
		
		// Log the first few characters for debugging
		if(strlen($barcode) > 0) {
			$first_char = substr($barcode, 0, 1);
			log_message('debug', 'First character: "' . $first_char . '", ASCII: ' . ord($first_char));
			
			if(strlen($barcode) > 1) {
				$second_char = substr($barcode, 1, 1);
				log_message('debug', 'Second character: "' . $second_char . '", ASCII: ' . ord($second_char));
			}
		}
		
		if(!$barcode) {
			log_message('debug', 'No barcode provided');
			echo json_encode(array(
				'success' => false,
				'message' => 'No barcode provided'
			));
			return;
		}
		
		// Log the lookup request
		log_message('debug', 'Orders: Looking up product by barcode: "' . $barcode . '"');
		
		// Get product by barcode
		$product = $this->model_products->getProductByBarcode($barcode);
		
		if($product) {
			// Format the image path
			if(isset($product['image']) && $product['image'] && $product['image'] != 'no-image.jpg') {
				$product['image'] = 'assets/images/product_images/' . $product['image'];
			} else {
				$product['image'] = 'assets/images/product-default.jpg';
			}
			
			// Log successful lookup
			log_message('info', 'Orders: Product found for barcode: "' . $barcode . '" (Product ID: ' . $product['id'] . ')');
			log_message('debug', '--- END BARCODE LOOKUP ---');
			
			echo json_encode(array(
				'success' => true,
				'product' => $product
			));
		} else {
			// Log failed lookup
			log_message('info', 'Orders: No product found for barcode: "' . $barcode . '"');
			
			// Query the database directly to check what barcodes exist
			$query = $this->db->query("SELECT id, name, barcode FROM products LIMIT 10");
			if($query->num_rows() > 0) {
				log_message('debug', 'Sample barcodes in database:');
				foreach($query->result_array() as $row) {
					log_message('debug', 'ID: ' . $row['id'] . ', Name: ' . $row['name'] . ', Barcode: "' . $row['barcode'] . '", Barcode Type: ' . gettype($row['barcode']));
				}
			}
			
			log_message('debug', '--- END BARCODE LOOKUP ---');
			
			echo json_encode(array(
				'success' => false,
				'message' => 'No product found with this barcode'
			));
		}
	}

}