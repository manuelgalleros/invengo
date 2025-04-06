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
		
		// Previous button
		$prev_disabled = ($page <= 1) ? 'disabled' : '';
		$pagination .= '<li class="page-item ' . $prev_disabled . '">
							<a href="#" class="page-link" data-page="' . ($page - 1) . '">
								<i class="ti ti-chevrons-left"></i>
							</a>
						</li>';

		// Page numbers
		for ($i = 1; $i <= $total_pages; $i++) {
			$active = ($i == $page) ? 'active' : '';
			$pagination .= '<li class="page-item ' . $active . '">
							<a href="#" class="page-link" data-page="' . $i . '">' . $i . '</a>
						</li>';
		}

		// Next button
		$next_disabled = ($page >= $total_pages) ? 'disabled' : '';
		$pagination .= '<li class="page-item ' . $next_disabled . '">
							<a href="#" class="page-link" data-page="' . ($page + 1) . '">
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
            foreach($order_ids as $id) {
                $delete = $this->model_orders->remove($id);
                if(!$delete) {
                    $success = false;
                    break;
                }
            }
            
            if($success) {
                $response['success'] = true;
                $response['messages'] = count($order_ids) > 1 ? 
                    "Successfully removed ".count($order_ids)." orders" : 
                    "Successfully removed";
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
	public function printDiv($id)
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
		if($id) {
			$order_data = $this->model_orders->getOrdersData($id);
			$orders_items = $this->model_orders->getOrdersItemData($id);
			$company_info = $this->model_company->getCompanyData(1);

			$order_date = date('d/m/Y', $order_data['date_time']);
			$paid_status = ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";

			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>AdminLTE 2 | Invoice</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
			        <h2 class="page-header">
			          '.$company_info['company_name'].'
			          <small class="pull-right">Date: '.$order_date.'</small>
			        </h2>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- info row -->
			    <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			        
			        <b>Order No:</b> '.$order_data['order_no'].'<br>
			        <b>Payment Method:</b> '.ucfirst($order_data['payment_method']).'
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <!-- Table row -->
			    <div class="row">
			      <div class="col-xs-12 table-responsive">
			        <table class="table table-striped">
			          <thead>
			          <tr>
			            <th>Product name</th>
			            <th>Price</th>
			            <th>Qty</th>
			            <th>Amount</th>
			          </tr>
			          </thead>
			          <tbody>'; 

			          foreach ($orders_items as $k => $v) {

			          	$product_data = $this->model_products->getProductData($v['product_id']); 
			          	
			          	$html .= '<tr>
				            <td>'.$product_data['name'].'</td>
				            <td>'.$v['rate'].'</td>
				            <td>'.$v['qty'].'</td>
				            <td>'.$v['amount'].'</td>
			          	</tr>';
			          }
			          
			          $html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <div class="row">
			      
			      <div class="col-xs-6 pull pull-right">

			        <div class="table-responsive">
			          <table class="table">
			            <tr>
			              <th style="width:50%">Gross Amount:</th>
			              <td>'.$order_data['gross_amount'].'</td>
			            </tr>';

			            if($order_data['service_charge'] > 0) {
			            	$html .= '<tr>
				              <th>Service Charge ('.$order_data['service_charge_rate'].'%)</th>
				              <td>'.$order_data['service_charge'].'</td>
				            </tr>';
			            }

			            if($order_data['vat_charge'] > 0) {
			            	$html .= '<tr>
				              <th>Vat Charge ('.$order_data['vat_charge_rate'].'%)</th>
				              <td>'.$order_data['vat_charge'].'</td>
				            </tr>';
			            }
			            
			            
			            $html .=' <tr>
			              <th>Discount:</th>
			              <td>'.$order_data['discount'].'</td>
			            </tr>
			            <tr>
			              <th>Net Amount:</th>
			              <td>'.$order_data['net_amount'].'</td>
			            </tr>
			            <tr>
			              <th>Paid Status:</th>
			              <td>'.$paid_status.'</td>
			            </tr>
			          </table>
			        </div>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
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