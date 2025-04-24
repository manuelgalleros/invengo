<?php 

class Groups extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Manage Groups';
		

		$this->load->model('model_groups');
		$this->load->model('model_users');
		$this->load->library('logs');
	}

	/* 
	* It redirects to the manage group page
	* As well as the group data is also been passed to display on the view page
	*/
	public function index()
	{

		if(!in_array('viewGroup', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$groups_data = $this->model_groups->getGroupData();
		$user_id = $this->session->userdata('id');
		$this->data['groups_data'] = $groups_data;
        $this->data['user_data'] = $this->model_users->getUserData($user_id);
		$this->render_template('groups/index', $this->data);
	}	

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation is for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{
		if(!in_array('createGroup', $this->permission)) {
			if($this->input->is_ajax_request()) {
				echo json_encode(['success' => false, 'message' => 'You do not have permission to create a group']);
				return;
			}
			redirect('dashboard', 'refresh');
		}

		$this->form_validation->set_rules('group_name', 'Group name', 'required');

        if ($this->form_validation->run() == TRUE) {
            // true case
            $permission = serialize($this->input->post('permission'));
            
        	$data = array(
        		'group_name' => $this->input->post('group_name'),
        		'permission' => $permission
        	);

        	$create = $this->model_groups->create($data);
        	if($create == true) {
                // Log successful group creation
                $this->logs->logActivity(
                    'create',
                    'Groups',
                    'Created new group: ' . $data['group_name'],
                    true
                );
                
        		if($this->input->is_ajax_request()) {
        			// Clear any existing flash messages
        			$this->session->unset_userdata('success');
        			$this->session->unset_userdata('error');
        			
        			echo json_encode([
        				'success' => true, 
        				'message' => 'Group ' . $this->input->post('group_name') . ' was successfully created',
        				'group_name' => $this->input->post('group_name')
        			]);
        			return;
        		}
        		
        		// Set flash data
        		$this->session->set_flashdata('success', 'Group ' . $this->input->post('group_name') . ' was successfully created');
        		redirect('groups/', 'refresh');
        	}
        	else {
                // Log failed group creation
                $this->logs->logActivity(
                    'create',
                    'Groups',
                    'Failed to create group: ' . $data['group_name'],
                    false
                );
                
        		if($this->input->is_ajax_request()) {
        			// Clear any existing flash messages
        			$this->session->unset_userdata('success');
        			$this->session->unset_userdata('error');
        			
        			echo json_encode(['success' => false, 'message' => 'Error occurred!!']);
        			return;
        		}
        		
        		$this->session->set_flashdata('error', 'Error occurred!!');
        		redirect('groups/create', 'refresh');
        	}
        }
        else {
            // false case
            if($this->input->is_ajax_request()) {
            	// Clear any existing flash messages to prevent them from showing on page refresh
            	$this->session->unset_userdata('success');
            	$this->session->unset_userdata('error');
            	
            	echo json_encode(['success' => false, 'message' => validation_errors()]);
            	return;
            }
			$user_id = $this->session->userdata('id');
            $this->data['user_data'] = $this->model_users->getUserData($user_id);
            $this->render_template('groups/create', $this->data);
        }	
	}

	/*
	* If the validation is not valid, then it redirects to the edit group page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function edit($id = null)
	{

		if(!in_array('updateGroup', $this->permission)) {
			if($this->input->is_ajax_request()) {
				echo json_encode(['success' => false, 'message' => 'You do not have permission to update a group']);
				return;
			}
			redirect('dashboard', 'refresh');
		}

		if($id) {

			$this->form_validation->set_rules('group_name', 'Group name', 'required');

			if ($this->form_validation->run() == TRUE) {
	            // true case
	            $permission = serialize($this->input->post('permission'));
	            
	        	$data = array(
	        		'group_name' => $this->input->post('group_name'),
	        		'permission' => $permission
	        	);

	        	$update = $this->model_groups->edit($data, $id);
	        	if($update == true) {
                    // Log successful group update
                    $this->logs->logActivity(
                        'update',
                        'Groups',
                        'Updated group: ' . $data['group_name'],
                        true
                    );
                    
	        		if($this->input->is_ajax_request()) {
	        			// Clear any existing flash messages to prevent them from showing on page refresh
	        			$this->session->unset_userdata('success');
	        			$this->session->unset_userdata('error');
	        			
	        			echo json_encode([
	        				'success' => true, 
	        				'message' => 'Successfully updated',
	        				'group_name' => $this->input->post('group_name')
	        			]);
	        			return;
	        		}
	        		
	        		$this->session->set_flashdata('success', 'Successfully updated');
	        		redirect('groups/', 'refresh');
	        	}
	        	else {
                    // Log failed group update
                    $this->logs->logActivity(
                        'update',
                        'Groups',
                        'Failed to update group: ' . $data['group_name'],
                        false
                    );
                    
	        		if($this->input->is_ajax_request()) {
	        			// Clear any existing flash messages to prevent them from showing on page refresh
	        			$this->session->unset_userdata('success');
	        			$this->session->unset_userdata('error');
	        			
	        			echo json_encode(['success' => false, 'message' => 'Error occurred!!']);
	        			return;
	        		}
	        		
	        		$this->session->set_flashdata('errors', 'Error occurred!!');
	        		redirect('groups/edit/'.$id, 'refresh');
	        	}
	        }
	        else {
	            // false case
	            if($this->input->is_ajax_request()) {
	            	// Clear any existing flash messages to prevent them from showing on page refresh
	            	$this->session->unset_userdata('success');
	            	$this->session->unset_userdata('error');
	            	
	            	echo json_encode(['success' => false, 'message' => validation_errors()]);
	            	return;
	            }
	            
	            $group_data = $this->model_groups->getGroupData($id);
				$this->data['group_data'] = $group_data;
				$this->render_template('groups/edit', $this->data);	
	        }	
		}
	}

	/*
	* It removes the removes information from the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function delete($id)
	{
		if(!in_array('deleteGroup', $this->permission)) {
			if($this->input->is_ajax_request()) {
				echo json_encode(['success' => false, 'message' => 'You do not have permission to delete a group']);
				return;
			}
			redirect('dashboard', 'refresh');
		}

		if($id) {
			// Get group data before deletion for logging
			$group_data = $this->model_groups->getGroupData($id);
			if (!$group_data) {
				if($this->input->is_ajax_request()) {
					echo json_encode(['success' => false, 'message' => 'Group not found']);
					return;
				}
				$this->session->set_flashdata('error', 'Group not found');
				redirect('groups/', 'refresh');
			}
			
			try {
				$delete = $this->model_groups->delete($id);
				if($delete == true) {
					// Log successful group deletion
					$this->logs->logActivity(
						'delete',
						'Groups',
						'Deleted group: ' . $group_data['group_name'],
						true
					);
					
					if($this->input->is_ajax_request()) {
						echo json_encode(['success' => true, 'message' => 'Successfully deleted']);
						return;
					}
					$this->session->set_flashdata('success', 'Successfully removed');
					redirect('groups/', 'refresh');
				}
				else {
					// Log failed group deletion
					$this->logs->logActivity(
						'delete',
						'Groups',
						'Failed to delete group: ' . $group_data['group_name'],
						false
					);
					
					if($this->input->is_ajax_request()) {
						echo json_encode(['success' => false, 'message' => 'Error occurred!!']);
						return;
					}
					$this->session->set_flashdata('error', 'Error occurred!!');
					redirect('groups/', 'refresh');
				}
			} catch (Exception $e) {
				// Check if this is a foreign key constraint violation
				if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
					// Log constraint error
					$this->logs->logActivity(
						'delete',
						'Groups',
						'Cannot delete group: ' . $group_data['group_name'] . ' because it has assigned users',
						false
					);
					
					if($this->input->is_ajax_request()) {
						echo json_encode(['success' => false, 'message' => 'Cannot delete this group because it has users assigned to it. Please reassign the users first.']);
						return;
					}
					$this->session->set_flashdata('error', 'Cannot delete this group because it has users assigned to it. Please reassign the users first.');
					redirect('groups/', 'refresh');
				} else {
					// Log other errors
					$this->logs->logActivity(
						'delete',
						'Groups',
						'Error deleting group: ' . $group_data['group_name'] . ' - ' . $e->getMessage(),
						false
					);
					
					if($this->input->is_ajax_request()) {
						echo json_encode(['success' => false, 'message' => 'Error deleting group: ' . $e->getMessage()]);
						return;
					}
					$this->session->set_flashdata('error', 'Error deleting group: ' . $e->getMessage());
					redirect('groups/', 'refresh');
				}
			}
		}
	}

	/*
	* Get group permissions via AJAX
	* Returns JSON data of the group permissions
	*/
	public function get_group_permissions()
	{
		// Check if this is an AJAX request
		if (!$this->input->is_ajax_request()) {
			redirect('dashboard', 'refresh');
		}

		// Check if group_id is provided
		$group_id = $this->input->post('group_id');
		if (!$group_id) {
			$response = array(
				'success' => false,
				'message' => 'No group ID provided'
			);
			echo json_encode($response);
			return;
		}

		// Get group data
		$group_data = $this->model_groups->getGroupData($group_id);
		if (!$group_data) {
			$response = array(
				'success' => false,
				'message' => 'Group not found'
			);
			echo json_encode($response);
			return;
		}

		// Unserialize permissions - ensure it's an array
		$permissions = unserialize($group_data['permission']);
		$permissions = is_array($permissions) ? $permissions : array();

		// Return permissions
		$response = array(
			'success' => true,
			'group_name' => $group_data['group_name'],
			'permissions' => $permissions
		);

		echo json_encode($response);
	}
	
	/*
	* Get groups via AJAX with pagination and search functionality
	* Returns JSON data of groups
	*/
	public function getGroups()
	{
		// Check if this is an AJAX request
		if (!$this->input->is_ajax_request()) {
			redirect('dashboard', 'refresh');
		}
		
		// Get pagination parameters
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		$search = $this->input->get('search') ? $this->input->get('search') : '';
		$limit = 10; // Items per page
		$offset = ($page - 1) * $limit;
		
		// Get groups with pagination
		$groups = $this->model_groups->getGroupDataWithPagination($limit, $offset, $search);
		$total_groups = $this->model_groups->getTotalGroups($search);
		
		// Format response
		$response = array(
			'groups' => $groups,
			'total_groups' => $total_groups,
			'page' => $page,
			'limit' => $limit
		);
		
		echo json_encode($response);
	}

	/*
	* Delete multiple groups at once via AJAX
	*/
	public function delete_multiple()
	{
		if(!in_array('deleteGroup', $this->permission)) {
			echo json_encode(['success' => false, 'message' => 'You do not have permission to delete groups']);
			return;
		}

		// Get group IDs
		$group_ids = $this->input->post('group_ids');
		
		if(!$group_ids || !is_array($group_ids)) {
			echo json_encode(['success' => false, 'message' => 'No groups selected']);
			return;
		}
		
		$success_count = 0;
		$error_count = 0;
		$constraint_count = 0;
		$deleted_names = [];
		
		// Process each group
		foreach($group_ids as $id) {
			// Get group name before deletion
			$group_data = $this->model_groups->getGroupData($id);
			if (!$group_data) {
				$error_count++;
				continue;
			}
			
			try {
				// Delete group
				$delete = $this->model_groups->delete($id);
				if($delete) {
					$success_count++;
					$deleted_names[] = $group_data['group_name'];
					
					// Log successful deletion
					$this->logs->logActivity(
						'delete',
						'Groups',
						'Deleted group: ' . $group_data['group_name'],
						true
					);
				} else {
					$error_count++;
					
					// Log failed deletion
					$this->logs->logActivity(
						'delete',
						'Groups',
						'Failed to delete group: ' . $group_data['group_name'],
						false
					);
				}
			} catch (Exception $e) {
				// Check if this is a foreign key constraint violation
				if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
					$constraint_count++;
					
					// Log constraint error
					$this->logs->logActivity(
						'delete',
						'Groups',
						'Cannot delete group: ' . $group_data['group_name'] . ' because it has assigned users',
						false
					);
				} else {
					$error_count++;
					
					// Log other errors
					$this->logs->logActivity(
						'delete',
						'Groups',
						'Error deleting group: ' . $group_data['group_name'] . ' - ' . $e->getMessage(),
						false
					);
				}
			}
		}
		
		// Build response
		$message = '';
		if($success_count > 0) {
			$message .= $success_count . ' group(s) successfully deleted.';
		}
		if($constraint_count > 0) {
			$message .= ($message ? ' ' : '') . $constraint_count . ' group(s) could not be deleted because they have users assigned.';
		}
		if($error_count > 0) {
			$message .= ($message ? ' ' : '') . $error_count . ' group(s) failed to delete due to errors.';
		}
		
		echo json_encode([
			'success' => ($success_count > 0),
			'message' => $message ?: 'No groups were deleted',
			'deleted_count' => $success_count,
			'constraint_count' => $constraint_count,
			'error_count' => $error_count
		]);
	}

}