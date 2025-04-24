<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Category';

		$this->load->model('model_category');
		$this->load->model('model_users');
		$this->load->library('logs');
	}

	/* 
	* It only redirects to the manage category page
	*/
	public function index()
	{

		if(!in_array('viewCategory', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$user_id = $this->session->userdata('id');
        $this->data['user_data'] = $this->model_users->getUserData($user_id);
		$this->render_template('category/index', $this->data);	

	}	

	/*
	* It checks if it gets the category id and retreives
	* the category information from the category model and 
	* returns the data into json format. 
	* This function is invoked from the view page.
	*/
	public function fetchCategoryDataById() 
	{
		$category_id = $this->input->post('category_id');
		
		if($category_id) {
			$data = $this->model_category->getCategoryData($category_id);
			echo json_encode($data);
		}
	}

	/*
	* Fetches the category value from the category table 
	* this function is called from the datatable ajax function
	*/
	public function fetchCategoryData()
	{
		$result = array('data' => array());

		$page = $this->input->get('page') ? $this->input->get('page') : 1;
		$search = $this->input->get('search') ? $this->input->get('search') : '';

		$data = $this->model_category->getCategoryData(null, $page, $search);

		foreach ($data['data'] as $key => $value) {
			// button
			$buttons = '';

			if(in_array('updateCategory', $this->permission)) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
			}

			if(in_array('deleteCategory', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}
				
			$status = ($value['active'] == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';

			$result['data'][$key] = array(
				'id' => $value['id'],
				'name' => $value['name'],
				'active' => $value['active'],
				'status' => $status
			);
		} // /foreach

		$result['pagination'] = array(
			'total_records' => $data['total_records'],
			'total_pages' => $data['total_pages'],
			'current_page' => $data['current_page'],
			'limit' => $data['limit']
		);

		echo json_encode($result);
	}

	/*
	* Its checks the category form validation 
	* and if the validation is successfully then it inserts the data into the database 
	* and returns the json format operation messages
	*/
	public function create()
	{
		if(!in_array('createCategory', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('category_name', 'Category name', 'trim|required');
		$this->form_validation->set_rules('active', 'Active', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
            // Get category name for success message
            $category_name = $this->input->post('category_name');
            
        	$data = array(
        		'name' => $category_name,
        		'active' => $this->input->post('active'),	
        	);

        	$create = $this->model_category->create($data);
        	if($create == true) {
                // Get the newly inserted category ID
                $category_id = $this->db->insert_id();
                
                // Log successful category creation with category ID
                $this->logs->logActivity(
                    'create',
                    'Categories',
                    'Created new category: ' . $category_name . ' (ID: ' . $category_id . ')',
                    true
                );
                
        		$response['success'] = true;
        		$response['messages'] = "Category '" . $category_name . "' successfully created.";
        	}
        	else {
                // Log failed category creation
                $this->logs->logActivity(
                    'create',
                    'Categories',
                    'Failed to create category: ' . $category_name,
                    false
                );
                
        		$response['success'] = false;
        		$response['messages'] = 'Error in the database while creating the category information.';			
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
	* Its checks the category form validation 
	* and if the validation is successfully then it updates the data into the database 
	* and returns the json format operation messages
	*/
	public function update()
	{
		if(!in_array('updateCategory', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();
		$category_id = $this->input->post('category_id');

		if($category_id) {
			$this->form_validation->set_rules('category_name', 'Category name', 'trim|required');
			$this->form_validation->set_rules('active', 'Active', 'trim|required');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	// Get category name for success message
	        	$category_name = $this->input->post('category_name');
	        	
	        	$data = array(
	        		'name' => $category_name,
	        		'active' => $this->input->post('active'),	
	        	);

	        	$update = $this->model_category->update($data, $category_id);
	        	if($update == true) {
                    // Log successful category update with category ID
                    $this->logs->logActivity(
                        'update',
                        'Categories',
                        'Updated category: ' . $category_name . ' (ID: ' . $category_id . ')',
                        true
                    );
                    
	        		$response['success'] = true;
	        		$response['messages'] = "Category '" . $category_name . "' successfully updated.";
	        	}
	        	else {
                    // Log failed category update
                    $this->logs->logActivity(
                        'update',
                        'Categories',
                        'Failed to update category: ' . $category_name,
                        false
                    );
                    
	        		$response['success'] = false;
	        		$response['messages'] = 'Error in the database while updating the category information';			
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
	* It removes the category information from the database 
	* and returns the json format operation messages
	*/
	public function remove()
	{
		if(!in_array('deleteCategory', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$category_id = $this->input->post('category_id');

		// Debug the input value
		log_message('debug', 'Category remove - category_id: ' . (is_array($category_id) ? json_encode($category_id) : $category_id));
        
        // Handle if category_id is an array
        if (is_array($category_id)) {
            $category_id = isset($category_id[0]) ? $category_id[0] : null;
            log_message('debug', 'Category remove - converted category_id to: ' . $category_id);
        }

		// Get category data before deletion for logging
        $category_data = $this->model_category->getCategoryData($category_id);
        
        // Check if category data exists and properly extract the name
        $category_name = 'Unknown';
        if ($category_data) {
            if (is_array($category_data) && isset($category_data['name'])) {
                $category_name = $category_data['name'];
            } elseif (is_array($category_data) && isset($category_data[0]['name'])) {
                // If we got back an array of categories, use the first one
                $category_name = $category_data[0]['name'];
            }
        }

		$response = array();
		if($category_id) {
			try {
				$delete = $this->model_category->remove($category_id);
				if($delete == true) {
					// Log successful category deletion with category ID and name
					$this->logs->logActivity(
						'delete',
						'Categories',
						'Deleted category: ' . $category_name . ' (ID: ' . (is_array($category_id) ? json_encode($category_id) : $category_id) . ')',
						true
					);
					
					$response['success'] = true;
					$response['messages'] = "Successfully removed";	
				}
				else {
					// Log failed category deletion
					$this->logs->logActivity(
						'delete',
						'Categories',
						'Failed to delete category: ' . $category_name . ' (ID: ' . (is_array($category_id) ? json_encode($category_id) : $category_id) . ')',
						false
					);
					
					$response['success'] = false;
					$response['messages'] = "Error in the database while removing the category information";
				}
			} catch (Exception $e) {
				// Check if this is a foreign key constraint violation
				if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
					// Log constraint error
					$this->logs->logActivity(
						'delete',
						'Categories',
						'Cannot delete category: ' . $category_name . ' (ID: ' . $category_id . ') because it has assigned products',
						false
					);
					
					$response['success'] = false;
					$response['messages'] = 'Cannot delete this category because it has products assigned to it. Please reassign or delete the products first.';
				} else {
					// Log other errors
					$this->logs->logActivity(
						'delete',
						'Categories',
						'Error deleting category: ' . $category_name . ' (ID: ' . $category_id . ') - ' . $e->getMessage(),
						false
					);
					
					$response['success'] = false;
					$response['messages'] = 'Error deleting category: ' . $e->getMessage();
				}
			}
		}
		else {
			$response['success'] = false;
			$response['messages'] = "Refresh the page again!!";
		}

		echo json_encode($response);
	}

}