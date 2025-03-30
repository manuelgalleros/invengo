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

		$this->render_template('brand/index', $this->data);
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

		$response = array(
			'data' => array(),
			'total_rows' => $total_rows,
			'per_page' => $per_page
		);

		foreach ($brands as $key => $value) {
			$status = ($value['active'] == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
			
			$response['data'][$key] = array(
				'id' => $value['id'],
				'name' => $value['name'],
				'active' => $value['active'],
				'status' => $status
			);
		}

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
        		$response['success'] = true;
        		$response['messages'] = 'Succesfully created';
        	}
        	else {
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
                // Get brand name
                $brand_name = $this->input->post('brand_name');
                
	        	$data = array(
	        		'name' => $brand_name,
	        		'active' => $this->input->post('active'),	
	        	);

	        	$update = $this->model_brands->update($data, $brand_id);
	        	if($update == true) {
	        		$response['success'] = true;
	        		$response['messages'] = "Brand '" . $brand_name . "' successfully updated";
	        	}
	        	else {
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
		$response = array();
		if($brand_id) {
			// Get brand name(s) before deletion
			$brand_names = [];
			if(is_array($brand_id)) {
				foreach($brand_id as $id) {
					$brand_data = $this->model_brands->getBrandData($id);
					if($brand_data) {
						$brand_names[] = $brand_data['name'];
					}
				}
			} else {
				$brand_data = $this->model_brands->getBrandData($brand_id);
				if($brand_data) {
					$brand_names[] = $brand_data['name'];
				}
			}
			
			// Delete the brand(s)
			$delete = $this->model_brands->remove($brand_id);

			if($delete == true) {
				$response['success'] = true;
				if(count($brand_names) == 1) {
					$response['messages'] = "Brand '" . $brand_names[0] . "' successfully removed";
				} else {
					$response['messages'] = count($brand_names) . " brands successfully removed: " . implode(", ", $brand_names);
				}
			}
			else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the brand information";
			}
		}
		else {
			$response['success'] = false;
			$response['messages'] = "Refresh the page again!!";
		}

		echo json_encode($response);
	}

}