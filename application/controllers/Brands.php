<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Brands extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Manage Brands';
        $this->load->model('model_users');
		$this->load->model('model_brands');
		$this->load->library('logs');
	}

	/* 
	* It only redirects to the manage product page and
	*/
	public function index()
	{
		if(!in_array('viewBrand', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        $user_id = $this->session->userdata('id');
        $user_data = $this->model_users->getUserData($user_id);
        $this->data['user_data'] = $user_data;

		$result = $this->model_brands->getBrandData();

		$this->data['results'] = $result;

		$this->render_template('brands/index', $this->data);
	}

	/*
	* Fetches the brand data from the brand table 
	* this function is called from the datatable ajax function
	*/
	public function fetchBrandData()
	{
		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$per_page = 10;
		$search = $this->input->get('search') ? $this->input->get('search') : '';
		
		$result = $this->model_brands->getBrandData(null, $page, $per_page, $search);
		$brands = $result['brands'];
		$total_rows = $result['total_rows'];

		$data = array();
		foreach ($brands as $key => $value) {
			$status = ($value['active'] == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
			
			$temp = array();
			$temp['id'] = $value['id'];
			$temp['name'] = $value['name'];
			$temp['active'] = $value['active'];
			$temp['status'] = $status;
			
			$data[] = $temp;
		}

		$response = array(
			'data' => $data,
			'total_rows' => $total_rows,
			'per_page' => $per_page
		);

		echo json_encode($response);
	}

	/*
	* It checks if it gets the brand id and retreives
	* the brand information from the brand model and 
	* returns the data into json format. 
	* This function is invoked from the view page.
	*/
	public function fetchBrandDataById()
	{
		$brand_id = $this->input->post('brand_id');
		if($brand_id) {
			$data = $this->model_brands->getBrandData($brand_id);
			echo json_encode($data);
		}
	}

	/*
	* Its checks the brand form validation 
	* and if the validation is successfully then it inserts the data into the database 
	* and returns the json format operation messages
	*/
	public function create()
	{
		if(!in_array('createBrand', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('brand_name', 'Brand name', 'trim|required');
		$this->form_validation->set_rules('active', 'Active', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		'name' => $this->input->post('brand_name'),
        		'active' => $this->input->post('active'),	
        	);

        	$create = $this->model_brands->create($data);
        	if($create == true) {
                // Get the newly inserted brand ID
                $brand_id = $this->db->insert_id();
                
                // Log successful brand creation with brand ID
                $this->logs->logActivity(
                    'create',
                    'Brands',
                    'Created new brand: ' . $data['name'] . ' (ID: ' . $brand_id . ')',
                    true
                );
                
        		$response['success'] = true;
        		$response['messages'] = 'Successfully created';
        	}
        	else {
                // Log failed brand creation
                $this->logs->logActivity(
                    'create',
                    'Brands',
                    'Failed to create brand: ' . $data['name'],
                    false
                );
                
        		$response['success'] = false;
        		$response['messages'] = 'Error in the database while creating the brand information';			
        	}
        }
        else {
        	$response['success'] = false;
        	foreach ($_POST as $key => $value) {
        		$response['messages'][$key] = form_error($key);
        	}
        }

        echo json_encode($response);
	}

	/*
	* Its checks the brand form validation 
	* and if the validation is successfully then it updates the data into the database 
	* and returns the json format operation messages
	*/
	public function update()
	{
		if(!in_array('updateBrand', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();
		$brand_id = $this->input->post('brand_id');

		if($brand_id) {
			$this->form_validation->set_rules('brand_name', 'Brand name', 'trim|required');
			$this->form_validation->set_rules('active', 'Active', 'trim|required');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        		'name' => $this->input->post('brand_name'),
	        		'active' => $this->input->post('active'),	
	        	);

	        	$update = $this->model_brands->update($data, $brand_id);
	        	if($update == true) {
                    // Log successful brand update with brand ID
                    $this->logs->logActivity(
                        'update',
                        'Brands',
                        'Updated brand: ' . $data['name'] . ' (ID: ' . $brand_id . ')',
                        true
                    );
                    
	        		$response['success'] = true;
	        		$response['messages'] = 'Successfully updated';
	        	}
	        	else {
                    // Log failed brand update with brand ID
                    $this->logs->logActivity(
                        'update',
                        'Brands',
                        'Failed to update brand: ' . $data['name'] . ' (ID: ' . $brand_id . ')',
                        false
                    );
                    
	        		$response['success'] = false;
	        		$response['messages'] = 'Error in the database while updating the brand information';			
	        	}
	        }
	        else {
	        	$response['success'] = false;
	        	foreach ($_POST as $key => $value) {
	        		$response['messages'][$key] = form_error($key);
	        	}
	        }
		}
		else {
			$response['success'] = false;
    		$response['messages'] = 'Error please refresh the page again!!';
		}

		echo json_encode($response);
	}

	/*
	* It removes the brand information from the database 
	* and returns the json format operation messages
	*/
	public function remove()
	{
		if(!in_array('deleteBrand', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$brand_id = $this->input->post('brand_id');
		$brand_ids = $this->input->post('brand_ids');
		$response = array();
		
		// Debug the input values
		log_message('debug', 'Brand remove - brand_id: ' . (is_array($brand_id) ? 'ARRAY' : $brand_id));
		log_message('debug', 'Brand remove - brand_ids: ' . (is_array($brand_ids) ? json_encode($brand_ids) : $brand_ids));
		
		// Handle bulk deletion with brand_ids
		if ($brand_ids) {
		    // Convert to array if it's a JSON string
		    if (!is_array($brand_ids) && is_string($brand_ids)) {
		        $brand_ids = json_decode($brand_ids, true);
		    }
		    
		    if (is_array($brand_ids)) {
		        $deleted_brands = [];
		        $failed_brands = [];
		        $constraint_brands = [];
		        
		        foreach ($brand_ids as $id) {
                    // Get brand data before deletion for logging
                    $brand_data = $this->model_brands->getBrandData($id);
                    $brand_name = (is_array($brand_data) && isset($brand_data['name'])) ? $brand_data['name'] : 'Unknown';
                    
                    try {
                        $delete = $this->model_brands->remove($id);
                        
                        if ($delete) {
                            $deleted_brands[] = $brand_name;
                            
                            // Log each successful deletion
                            $this->logs->logActivity(
                                'delete',
                                'Brands',
                                'Deleted brand: ' . $brand_name . ' (ID: ' . $id . ')',
                                true
                            );
                        } else {
                            $failed_brands[] = $brand_name;
                            
                            // Log each failed deletion
                            $this->logs->logActivity(
                                'delete',
                                'Brands',
                                'Failed to delete brand: ' . $brand_name . ' (ID: ' . $id . ')',
                                false
                            );
                        }
                    } catch (Exception $e) {
                        // Check if this is a foreign key constraint violation
                        if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
                            $constraint_brands[] = $brand_name;
                            
                            // Log constraint error
                            $this->logs->logActivity(
                                'delete',
                                'Brands',
                                'Cannot delete brand: ' . $brand_name . ' (ID: ' . $id . ') because it has assigned products',
                                false
                            );
                        } else {
                            $failed_brands[] = $brand_name;
                            
                            // Log other errors
                            $this->logs->logActivity(
                                'delete',
                                'Brands',
                                'Error deleting brand: ' . $brand_name . ' (ID: ' . $id . ') - ' . $e->getMessage(),
                                false
                            );
                        }
                    }
		        }
		        
		        // Set response based on results
		        if (count($deleted_brands) > 0) {
		            $response['success'] = true;
		            $response['messages'] = count($deleted_brands) . " brand(s) successfully removed";
		            
		            if (count($constraint_brands) > 0) {
		                $response['messages'] .= ", " . count($constraint_brands) . " brand(s) could not be deleted because they have products assigned";
		            }
		            
		            if (count($failed_brands) > 0) {
		                $response['messages'] .= ", " . count($failed_brands) . " failed due to other errors";
		            }
		        } else if (count($constraint_brands) > 0) {
		            $response['success'] = false;
		            $response['messages'] = count($constraint_brands) . " brand(s) could not be deleted because they have products assigned";
		        } else {
		            $response['success'] = false;
		            $response['messages'] = "Failed to remove brands";
		        }
		    } else {
		        $response['success'] = false;
		        $response['messages'] = "Invalid brand IDs format";
		    }
		}
		// Handle single brand deletion
		else if($brand_id) {
		    // If brand_id is somehow an array, use just the first element
		    if (is_array($brand_id)) {
		        $brand_id = $brand_id[0];
		    }
		    
            // Get brand data before deletion for logging
            $brand_data = $this->model_brands->getBrandData($brand_id);
            $brand_name = (is_array($brand_data) && isset($brand_data['name'])) ? $brand_data['name'] : 'Unknown';
            
            try {
                $delete = $this->model_brands->remove($brand_id);
    
                if($delete == true) {
                    // Log successful brand deletion
                    $this->logs->logActivity(
                        'delete',
                        'Brands',
                        'Deleted brand: ' . $brand_name . ' (ID: ' . $brand_id . ')',
                        true
                    );
                    
                    $response['success'] = true;
                    $response['messages'] = "Successfully removed";	
                }
                else {
                    // Log failed brand deletion
                    $this->logs->logActivity(
                        'delete',
                        'Brands',
                        'Failed to delete brand: ' . $brand_name . ' (ID: ' . $brand_id . ')',
                        false
                    );
                    
                    $response['success'] = false;
                    $response['messages'] = 'Error in the database while removing the brand information';
                }
            } catch (Exception $e) {
                // Check if this is a foreign key constraint violation
                if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
                    // Log constraint error
                    $this->logs->logActivity(
                        'delete',
                        'Brands',
                        'Cannot delete brand: ' . $brand_name . ' (ID: ' . $brand_id . ') because it has assigned products',
                        false
                    );
                    
                    $response['success'] = false;
                    $response['messages'] = 'Cannot delete this brand because it has products assigned to it. Please reassign or delete the products first.';
                } else {
                    // Log other errors
                    $this->logs->logActivity(
                        'delete',
                        'Brands',
                        'Error deleting brand: ' . $brand_name . ' (ID: ' . $brand_id . ') - ' . $e->getMessage(),
                        false
                    );
                    
                    $response['success'] = false;
                    $response['messages'] = 'Error deleting brand: ' . $e->getMessage();
                }
            }
		}
		else {
			$response['success'] = false;
			$response['messages'] = "No brands selected for deletion";
		}

		echo json_encode($response);
	}
} 