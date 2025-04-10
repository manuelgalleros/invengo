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
			$date = date('d-m-Y', $order['date_time']);
			$time = date('h:i a', $order['date_time']);
			$date_time = $date . ' ' . $time;
			
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
                // Check if this is an AJAX request
                if($this->input->is_ajax_request()) {
                    // Get the order details to include in the response
                    $order_data = $this->model_orders->getOrdersData($order_id);
                    
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
        		$this->session->set_flashdata('success', 'Successfully updated');
        		redirect('orders/update/'.$id, 'refresh');
        	}
        	else {
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

        $response = array();
        if($order_id) {
            // Handle both single and multiple deletions
            $order_ids = is_array($order_id) ? $order_id : array($order_id);
            
            $success = true;
            $deleted_orders = array();
            
            foreach($order_ids as $id) {
                // Get order details before deletion
                $order_data = $this->model_orders->getOrdersData($id);
                if($order_data) {
                    $deleted_orders[] = array(
                        'id' => $id,
                        'order_no' => $order_data['order_no']
                    );
                }
                
                $delete = $this->model_orders->remove($id);
                if(!$delete) {
                    $success = false;
                    break;
                }
            }
            
            if($success) {
                $response['success'] = true;
                
                if(count($order_ids) == 1) {
                    // Single order deletion
                    $response['messages'] = "Successfully removed order " . $deleted_orders[0]['order_no'];
                    $response['order_no'] = $deleted_orders[0]['order_no'];
                } else {
                    // Multiple order deletion
                    $response['messages'] = "Successfully removed " . count($order_ids) . " orders";
                    $response['order_count'] = count($order_ids);
                }
                
                $response['deleted_orders'] = $deleted_orders;
            } else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the order(s)";
            }
        } else {
            $response['success'] = false;
            $response['messages'] = "Please select orders to delete";
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
							
							$html .= '<tr>
								<td>'.$product_data['name'].'</td>
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
    * Update order payment method and paid status via AJAX
    */
    public function update_ajax()
    {
        // Check permission
        if(!in_array('updateOrder', $this->permission)) {
            $response['success'] = false;
            $response['messages'] = 'You do not have permission to update orders';
            echo json_encode($response);
            return;
        }
        
        $order_id = $this->input->post('edit_order_id');
        
        if($order_id) {
            $user_id = $this->session->userdata('id');
            
            // Update only the payment method and paid status
            $data = array(
                'payment_method' => $this->input->post('edit_payment_method'),
                'paid_status' => $this->input->post('edit_paid_status'),
                'user_id' => $user_id
            );
            
            $this->db->where('id', $order_id);
            $update = $this->db->update('orders', $data);
            
            if($update) {
                $response['success'] = true;
                $response['messages'] = 'Order successfully updated';
            } else {
                $response['success'] = false;
                $response['messages'] = 'Error occurred while updating order';
            }
        } else {
            $response['success'] = false;
            $response['messages'] = 'Order ID is required';
        }
        
        echo json_encode($response);
    }

    /*
    * Fetch single order details for editing
    */
    public function get_order()
    {
        // Check permission
        if(!in_array('updateOrder', $this->permission)) {
            $response['success'] = false;
            $response['messages'] = 'You do not have permission to update orders';
            echo json_encode($response);
            return;
        }
        
        $order_id = $this->input->post('order_id');
        if($order_id) {
            $order_data = $this->model_orders->getOrdersData($order_id);
            
            if($order_data) {
                $response['success'] = true;
                $response['data'] = array(
                    'id' => $order_data['id'],
                    'payment_method' => $order_data['payment_method'],
                    'paid_status' => $order_data['paid_status']
                );
            } else {
                $response['success'] = false;
                $response['messages'] = 'Order not found';
            }
        } else {
            $response['success'] = false;
            $response['messages'] = 'Order ID is required';
        }
        
        echo json_encode($response);
    }

}