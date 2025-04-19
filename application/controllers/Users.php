<?php 

class Users extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();
		
		$this->data['page_title'] = 'Manage Users';
		

		$this->load->model('model_users');
		$this->load->model('model_groups');
		$this->load->library('logs');
	}

	
	public function index()
	{
		if(!in_array('viewUser', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$user_data = $this->model_users->getUserData();

		$result = array();
		foreach ($user_data as $k => $v) {

			$result[$k]['user_info'] = $v;

			$group = $this->model_users->getUserGroup($v['id']);
			$result[$k]['user_group'] = $group;
		}

		$this->data['all_users'] = $result;
		$user_id = $this->session->userdata('id');
        $this->data['user_data'] = $this->model_users->getUserData($user_id);
        
        // Add group data for the select dropdowns
        $group_data = $this->model_groups->getGroupData();
        $this->data['group_data'] = $group_data;

		$this->render_template('users/index', $this->data);
	}

	public function create()
	{
		if(!in_array('createUser', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|is_unique[users.username]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique[users.email]|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('fname', 'First name', 'trim|required');
		$this->form_validation->set_rules('gender', 'Gender', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            // true case
            $password = $this->password_hash($this->input->post('password'));
            
            // Log debug information about the submission
            log_message('debug', 'User create - form validation passed');
            log_message('debug', 'User create - username: ' . $this->input->post('username'));
            log_message('debug', 'User create - email: ' . $this->input->post('email'));
            log_message('debug', 'User create - groups: ' . json_encode($this->input->post('groups')));
            
            // Handle image upload
            $profile_image = 'default.jpg'; // Default image
            if(!empty($_FILES['profile_image']['name'])) {
                // Get file extension
                $file_ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                
                // Create unique filename using username and timestamp
                $unique_filename = 'user_' . 
                                 preg_replace('/[^A-Za-z0-9]/', '', $this->input->post('username')) . 
                                 '_' . 
                                 time() . 
                                 '_' . 
                                 uniqid() . 
                                 '.' . $file_ext;
                
                // Set upload configuration
                $config['upload_path'] = './assets/images/users/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 10240; // 10MB max-size
                $config['file_name'] = $unique_filename;
                
                $this->load->library('upload', $config);
                
                if($this->upload->do_upload('profile_image')) {
                    $upload_data = $this->upload->data();
                    $profile_image = $upload_data['file_name'];
                    log_message('debug', 'User create - profile image uploaded: ' . $profile_image);
                } else {
                    log_message('error', 'User create - profile image upload failed: ' . $this->upload->display_errors());
                    if($this->input->is_ajax_request()) {
                        echo json_encode(['success' => false, 'errors' => $this->upload->display_errors()]);
                        return;
                    }
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('users/create', 'refresh');
                    return;
                }
            }
            
        	$data = array(
        		'username' => $this->input->post('username'),
        		'password' => $password,
        		'email' => $this->input->post('email'),
        		'firstname' => $this->input->post('fname'),
        		'lastname' => $this->input->post('lname'),
        		'phone' => $this->input->post('phone'),
        		'gender' => $this->input->post('gender'),
                'profile_image' => $profile_image
        	);

            // Log the data being sent to the model
            log_message('debug', 'User create - data being sent to model: ' . json_encode($data));
            log_message('debug', 'User create - groups being sent to model: ' . json_encode($this->input->post('groups')));
            
            try {
                $create = $this->model_users->create($data, $this->input->post('groups'));
                
                if($create == true) {
                    // Log successful user creation
                    log_message('debug', 'User create - user created successfully');
                    $this->logs->logActivity(
                        'create',
                        'Users',
                        'Created new user: ' . $data['username'] . ' (' . $data['email'] . ')',
                        true
                    );
                    
                    if($this->input->is_ajax_request()) {
                        echo json_encode([
                            'success' => true, 
                            'message' => 'Successfully created user "' . $this->input->post('username') . '"',
                            'redirect' => base_url('users/')
                        ]);
                        return;
                    }
                    $this->session->set_flashdata('success', 'Successfully created');
                    redirect('users/', 'refresh');
                }
                else {
                    // If user creation fails, delete the uploaded image
                    if($profile_image != 'default.jpg') {
                        unlink('./assets/images/users/' . $profile_image);
                    }
                    
                    // Log failed user creation
                    log_message('error', 'User create - failed to create user in model');
                    $this->logs->logActivity(
                        'create',
                        'Users',
                        'Failed to create user: ' . $data['username'] . ' (' . $data['email'] . ')',
                        false
                    );
                    
                    if($this->input->is_ajax_request()) {
                        echo json_encode(['success' => false, 'errors' => 'Error occurred while creating user']);
                        return;
                    }
                    $this->session->set_flashdata('error', 'Error occurred!!');
                    redirect('users/create', 'refresh');
                }
            } catch (Exception $e) {
                // If an exception occurs, delete the uploaded image
                if($profile_image != 'default.jpg' && file_exists('./assets/images/users/' . $profile_image)) {
                    unlink('./assets/images/users/' . $profile_image);
                }
                
                // Log the exception
                log_message('error', 'User create - exception: ' . $e->getMessage());
                $this->logs->logActivity(
                    'create',
                    'Users',
                    'Exception while creating user: ' . $data['username'] . ' - ' . $e->getMessage(),
                    false
                );
                
                if($this->input->is_ajax_request()) {
                    echo json_encode(['success' => false, 'errors' => 'Exception occurred: ' . $e->getMessage()]);
                    return;
                }
                $this->session->set_flashdata('error', 'Exception occurred: ' . $e->getMessage());
                redirect('users/create', 'refresh');
            }
        }
        else {
            // false case - validation failed
            if($this->input->is_ajax_request()) {
                // Get all validation errors
                $errors = array();
                
                // Log validation errors
                log_message('debug', 'User create - validation failed');
                
                // Add form validation errors
                if(form_error('groups')) {
                    $errors[] = strip_tags(form_error('groups'));
                    log_message('debug', 'User create - groups validation error: ' . strip_tags(form_error('groups')));
                }
                if(form_error('username')) {
                    $errors[] = strip_tags(form_error('username'));
                    log_message('debug', 'User create - username validation error: ' . strip_tags(form_error('username')));
                }
                if(form_error('email')) {
                    $errors[] = strip_tags(form_error('email'));
                    log_message('debug', 'User create - email validation error: ' . strip_tags(form_error('email')));
                }
                if(form_error('password')) {
                    $errors[] = strip_tags(form_error('password'));
                    log_message('debug', 'User create - password validation error: ' . strip_tags(form_error('password')));
                }
                if(form_error('cpassword')) {
                    $errors[] = strip_tags(form_error('cpassword'));
                    log_message('debug', 'User create - cpassword validation error: ' . strip_tags(form_error('cpassword')));
                }
                if(form_error('fname')) {
                    $errors[] = strip_tags(form_error('fname'));
                    log_message('debug', 'User create - fname validation error: ' . strip_tags(form_error('fname')));
                }
                
                echo json_encode(['success' => false, 'errors' => $errors]);
                return;
            }
            
            // Regular form submission
			$this->data['page_title'] = 'Create New User';
        	$group_data = $this->model_groups->getGroupData();
        	$this->data['group_data'] = $group_data;
			$user_id = $this->session->userdata('id');
			$this->data['user_data'] = $this->model_users->getUserData($user_id);

            $this->render_template('users/create', $this->data);
        }
	}

	public function password_hash($pass = '')
	{
		if($pass) {
			$password = password_hash($pass, PASSWORD_DEFAULT);
			return $password;
		}
	}

	public function edit($id = null)
	{
		if(!in_array('updateUser', $this->permission)) {
			if($this->input->is_ajax_request()) {
				echo json_encode(['success' => false, 'errors' => 'Permission denied']);
				return;
			}
			redirect('dashboard', 'refresh');
		}

		if($id) {
			// Handle GET request for fetching user data
			if($this->input->server('REQUEST_METHOD') === 'GET') {
				if($this->input->is_ajax_request()) {
					$user_data = $this->model_users->getUserData($id);
					$groups = $this->model_users->getUserGroup($id);
					
					if($user_data && $groups) {
						// Merge user data with group info
						$response = array_merge($user_data, [
							'group_id' => $groups['group_id'],
							'group_name' => $groups['group_name']
						]);
						echo json_encode($response);
					} else {
						echo json_encode(['success' => false, 'errors' => 'User not found']);
					}
					return;
				}
			}

			$this->form_validation->set_rules('groups', 'Group', 'required');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required');
			$this->form_validation->set_rules('fname', 'First name', 'trim|required');
			$this->form_validation->set_rules('gender', 'Gender', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
	            // true case
		        if(empty($this->input->post('password')) && empty($this->input->post('cpassword'))) {
		        	$data = array(
		        		'username' => $this->input->post('username'),
		        		'email' => $this->input->post('email'),
		        		'firstname' => $this->input->post('fname'),
		        		'lastname' => $this->input->post('lname'),
		        		'phone' => $this->input->post('phone'),
		        		'gender' => $this->input->post('gender'),
		        	);

                    // Handle profile image upload
                    if(!empty($_FILES['profile_image']['name'])) {
                        // Get user's current image
                        $user_data = $this->model_users->getUserData($id);
                        $current_image = $user_data['profile_image'];
                        
                        // Get file extension
                        $file_ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                        
                        // Create unique filename using username and timestamp
                        $unique_filename = 'user_' . 
                                        preg_replace('/[^A-Za-z0-9]/', '', $this->input->post('username')) . 
                                        '_' . 
                                        time() . 
                                        '_' . 
                                        uniqid() . 
                                        '.' . $file_ext;
                        
                        // Set upload configuration
                        $config['upload_path'] = './assets/images/users/';
                        $config['allowed_types'] = 'gif|jpg|jpeg|png';
                        $config['max_size'] = 10240; // 10MB max-size
                        $config['file_name'] = $unique_filename;
                        
                        $this->load->library('upload', $config);
                        
                        if($this->upload->do_upload('profile_image')) {
                            $upload_data = $this->upload->data();
                            $data['profile_image'] = $upload_data['file_name'];
                            
                            // Delete old image if it's not the default
                            if($current_image != 'default.jpg' && file_exists('./assets/images/users/' . $current_image)) {
                                unlink('./assets/images/users/' . $current_image);
                            }
                        } else {
                            if($this->input->is_ajax_request()) {
                                echo json_encode(['success' => false, 'errors' => $this->upload->display_errors()]);
                                return;
                            }
                            $this->session->set_flashdata('error', $this->upload->display_errors());
                            redirect('users/edit/'.$id, 'refresh');
                            return;
                        }
                    }

		        	$update = $this->model_users->edit($data, $id, $this->input->post('groups'));
		        	if($update == true) {
                        // Log successful user update
                        $this->logs->logActivity(
                            'update',
                            'Users',
                            'Updated user: ' . $data['username'] . ' (ID: ' . $id . ')',
                            true
                        );
                        
		        		if($this->input->is_ajax_request()) {
							echo json_encode([
								'success' => true,
								'message' => 'Successfully updated user "' . $this->input->post('username') . '"'
							]);
							return;
						}
		        		$this->session->set_flashdata('success', 'Successfully updated');
		        		redirect('users/', 'refresh');
		        	}
		        	else {
                        // Log failed user update
                        $this->logs->logActivity(
                            'update',
                            'Users',
                            'Failed to update user: ' . $data['username'] . ' (ID: ' . $id . ')',
                            false
                        );
                        
		        		if($this->input->is_ajax_request()) {
							echo json_encode(['success' => false, 'errors' => 'Error occurred while updating user']);
							return;
						}
		        		$this->session->set_flashdata('errors', 'Error occurred!!');
		        		redirect('users/edit/'.$id, 'refresh');
		        	}
		        }
		        else {
		        	$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
					$this->form_validation->set_rules('cpassword', 'Confirm password', 'trim|required|matches[password]');

					if($this->form_validation->run() == TRUE) {

						$password = $this->password_hash($this->input->post('password'));

						$data = array(
			        		'username' => $this->input->post('username'),
			        		'password' => $password,
			        		'email' => $this->input->post('email'),
			        		'firstname' => $this->input->post('fname'),
			        		'lastname' => $this->input->post('lname'),
			        		'phone' => $this->input->post('phone'),
			        		'gender' => $this->input->post('gender'),
			        	);

                        // Handle profile image upload
                        if(!empty($_FILES['profile_image']['name'])) {
                            // Get user's current image
                            $user_data = $this->model_users->getUserData($id);
                            $current_image = $user_data['profile_image'];
                            
                            // Get file extension
                            $file_ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                            
                            // Create unique filename using username and timestamp
                            $unique_filename = 'user_' . 
                                            preg_replace('/[^A-Za-z0-9]/', '', $this->input->post('username')) . 
                                            '_' . 
                                            time() . 
                                            '_' . 
                                            uniqid() . 
                                            '.' . $file_ext;
                            
                            // Set upload configuration
                            $config['upload_path'] = './assets/images/users/';
                            $config['allowed_types'] = 'gif|jpg|jpeg|png';
                            $config['max_size'] = 10240; // 10MB max-size
                            $config['file_name'] = $unique_filename;
                            
                            $this->load->library('upload', $config);
                            
                            if($this->upload->do_upload('profile_image')) {
                                $upload_data = $this->upload->data();
                                $data['profile_image'] = $upload_data['file_name'];
                                
                                // Delete old image if it's not the default
                                if($current_image != 'default.jpg' && file_exists('./assets/images/users/' . $current_image)) {
                                    unlink('./assets/images/users/' . $current_image);
                                }
                            } else {
                                if($this->input->is_ajax_request()) {
                                    echo json_encode(['success' => false, 'errors' => $this->upload->display_errors()]);
                                    return;
                                }
                                $this->session->set_flashdata('error', $this->upload->display_errors());
                                redirect('users/edit/'.$id, 'refresh');
                                return;
                            }
                        }

			        	$update = $this->model_users->edit($data, $id, $this->input->post('groups'));
			        	if($update == true) {
                            // Log successful user update with password change
                            $this->logs->logActivity(
                                'update',
                                'Users',
                                'Updated user with password change: ' . $data['username'] . ' (ID: ' . $id . ')',
                                true
                            );
                            
			        		if($this->input->is_ajax_request()) {
								echo json_encode([
									'success' => true,
									'message' => 'Successfully updated user "' . $this->input->post('username') . '"'
								]);
								return;
							}
			        		$this->session->set_flashdata('success', 'Successfully updated');
			        		redirect('users/', 'refresh');
			        	}
			        	else {
                            // Log failed user update with password change
                            $this->logs->logActivity(
                                'update',
                                'Users',
                                'Failed to update user with password change: ' . $data['username'] . ' (ID: ' . $id . ')',
                                false
                            );
                            
			        		if($this->input->is_ajax_request()) {
								echo json_encode(['success' => false, 'errors' => 'Error occurred while updating user']);
								return;
							}
			        		$this->session->set_flashdata('errors', 'Error occurred!!');
			        		redirect('users/edit/'.$id, 'refresh');
			        	}
					}
			        else {
			            // false case - validation failed
						if($this->input->is_ajax_request()) {
							// Get all validation errors
							$errors = array();
							
							// Add form validation errors
							if(form_error('password')) $errors[] = strip_tags(form_error('password'));
							if(form_error('cpassword')) $errors[] = strip_tags(form_error('cpassword'));
							
							echo json_encode(['success' => false, 'errors' => $errors]);
							return;
						}

			        	$user_data = $this->model_users->getUserData($id);
			        	$groups = $this->model_users->getUserGroup($id);

			        	$this->data['user_data'] = $user_data;
			        	$this->data['user_group'] = $groups;

			            $group_data = $this->model_groups->getGroupData();
			        	$this->data['group_data'] = $group_data;

						$this->render_template('users/edit', $this->data);	
			        }	

		        }
	        }
	        else {
	            // false case - validation failed
				if($this->input->is_ajax_request()) {
					// Get all validation errors
					$errors = array();
					
					// Add form validation errors
					if(form_error('groups')) $errors[] = strip_tags(form_error('groups'));
					if(form_error('username')) $errors[] = strip_tags(form_error('username'));
					if(form_error('email')) $errors[] = strip_tags(form_error('email'));
					if(form_error('fname')) $errors[] = strip_tags(form_error('fname'));
					
					echo json_encode(['success' => false, 'errors' => $errors]);
					return;
				}

	        	$user_data = $this->model_users->getUserData($id);
	        	$groups = $this->model_users->getUserGroup($id);

	        	$this->data['user_data'] = $user_data;
	        	$this->data['user_group'] = $groups;

	            $group_data = $this->model_groups->getGroupData();
	        	$this->data['group_data'] = $group_data;

				$this->render_template('users/edit', $this->data);	
	        }	
		}	
	}

	public function delete($id)
	{
		if(!in_array('deleteUser', $this->permission)) {
			if($this->input->is_ajax_request()) {
				$this->output->set_status_header(403);
				echo json_encode(['success' => false, 'message' => 'Permission denied']);
				return;
			}
			redirect('dashboard', 'refresh');
		}

		if($id) {
			if($this->input->post('confirm')) {
				// Get user data to delete profile image if needed
				$user_data = $this->model_users->getUserData($id);
				
				$delete = $this->model_users->delete($id);
				if($delete == true) {
					// Delete profile image if not default
					if(isset($user_data['profile_image']) && $user_data['profile_image'] != 'default.jpg') {
						$image_path = './assets/images/users/' . $user_data['profile_image'];
						if(file_exists($image_path)) {
							unlink($image_path);
						}
					}
					
					// Log successful user deletion
					$this->logs->logActivity(
						'delete',
						'Users',
						'Deleted user: ' . $user_data['username'] . ' (ID: ' . $id . ')',
						true
					);
					
					if($this->input->is_ajax_request()) {
						echo json_encode(['success' => true, 'message' => 'User successfully deleted']);
						return;
					}
					$this->session->set_flashdata('success', 'Successfully removed');
					redirect('users/', 'refresh');
				}
				else {
					// Log failed user deletion
					$this->logs->logActivity(
						'delete',
						'Users',
						'Failed to delete user: ' . $user_data['username'] . ' (ID: ' . $id . ')',
						false
					);
					
					if($this->input->is_ajax_request()) {
						$this->output->set_status_header(500);
						echo json_encode(['success' => false, 'message' => 'Error occurred while deleting user']);
						return;
					}
					$this->session->set_flashdata('error', 'Error occurred!!');
					redirect('users/delete/'.$id, 'refresh');
				}
			}	
			else {
				$this->data['id'] = $id;
				$this->render_template('users/delete', $this->data);
			}	
		}
	}

	public function getUsers()
	{
		// Check for permission
		if(!in_array('viewUser', $this->permission)) {
			$this->output->set_status_header(403);
			echo json_encode(['error' => 'Permission denied']);
			return;
		}

		// Get parameters
		$page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
		$search = $this->input->get('search') ? $this->input->get('search') : '';
		$limit = 10;
		$offset = ($page - 1) * $limit;

		// Get user data with search and pagination
		$user_data = $this->model_users->getUserDataWithPagination($limit, $offset, $search);
		$total_users = $this->model_users->getTotalUsers($search);

		// Process user data to include group information
		$result = [];
		foreach ($user_data as $k => $v) {
			$group = $this->model_users->getUserGroup($v['id']);
			$v['group_name'] = $group['group_name'];
			$result[] = $v;
		}

		// Prepare response
		$response = [
			'users' => $result,
			'total_users' => $total_users,
			'page' => $page,
			'limit' => $limit
		];

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	public function profile()
	{
		if(!in_array('viewProfile', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$user_id = $this->session->userdata('id');

		$user_data = $this->model_users->getUserData($user_id);
		$this->data['user_data'] = $user_data;

		$user_group = $this->model_users->getUserGroup($user_id);
		$this->data['user_group'] = $user_group;

        $this->render_template('users/profile', $this->data);
	}

	public function setting()
	{	
		if(!in_array('updateSetting', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$id = $this->session->userdata('id');

		if($id) {
			$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required');
			$this->form_validation->set_rules('fname', 'First name', 'trim|required');
			$this->form_validation->set_rules('gender', 'Gender', 'trim|required');



			if ($this->form_validation->run() == TRUE) {
	            // true case
		        if(empty($this->input->post('password')) && empty($this->input->post('cpassword'))) {
		        	$data = array(
		        		'username' => $this->input->post('username'),
		        		'email' => $this->input->post('email'),
		        		'firstname' => $this->input->post('fname'),
		        		'lastname' => $this->input->post('lname'),
		        		'phone' => $this->input->post('phone'),
		        		'gender' => $this->input->post('gender'),
		        	);

		        	$update = $this->model_users->edit($data, $id);
		        	if($update == true) {
                        // Log successful profile update
                        $this->logs->logActivity(
                            'update',
                            'Users',
                            'Updated profile settings for: ' . $data['username'],
                            true
                        );
		        		$this->session->set_flashdata('success', 'Successfully updated');
		        		redirect('users/setting/', 'refresh');
		        	}
		        	else {
                        // Log failed profile update
                        $this->logs->logActivity(
                            'update',
                            'Users',
                            'Failed to update profile settings for: ' . $data['username'],
                            false
                        );
		        		$this->session->set_flashdata('errors', 'Error occurred!!');
		        		redirect('users/setting/', 'refresh');
		        	}
		        }
		        else {
		        	$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
					$this->form_validation->set_rules('cpassword', 'Confirm password', 'trim|required|matches[password]');

					if($this->form_validation->run() == TRUE) {

						$password = $this->password_hash($this->input->post('password'));

						$data = array(
			        		'username' => $this->input->post('username'),
			        		'password' => $password,
			        		'email' => $this->input->post('email'),
			        		'firstname' => $this->input->post('fname'),
			        		'lastname' => $this->input->post('lname'),
			        		'phone' => $this->input->post('phone'),
			        		'gender' => $this->input->post('gender'),
			        	);

			        	$update = $this->model_users->edit($data, $id, $this->input->post('groups'));
			        	if($update == true) {
                            // Log successful profile update with password change
                            $this->logs->logActivity(
                                'update',
                                'Users',
                                'Updated profile settings with password change for: ' . $data['username'],
                                true
                            );
			        		$this->session->set_flashdata('success', 'Successfully updated');
			        		redirect('users/setting/', 'refresh');
			        	}
			        	else {
                            // Log failed profile update with password change
                            $this->logs->logActivity(
                                'update',
                                'Users',
                                'Failed to update profile settings with password change for: ' . $data['username'],
                                false
                            );
			        		$this->session->set_flashdata('errors', 'Error occurred!!');
			        		redirect('users/setting/', 'refresh');
			        	}
					}
			        else {
			            // false case
			        	$user_data = $this->model_users->getUserData($id);
			        	$groups = $this->model_users->getUserGroup($id);

			        	$this->data['user_data'] = $user_data;
			        	$this->data['user_group'] = $groups;

			            $group_data = $this->model_groups->getGroupData();
			        	$this->data['group_data'] = $group_data;

						$this->render_template('users/setting', $this->data);	
			        }	

		        }
	        }
	        else {
	            // false case
	        	$user_data = $this->model_users->getUserData($id);
	        	$groups = $this->model_users->getUserGroup($id);

	        	$this->data['user_data'] = $user_data;
	        	$this->data['user_group'] = $groups;

	            $group_data = $this->model_groups->getGroupData();
	        	$this->data['group_data'] = $group_data;

				$this->render_template('users/setting', $this->data);	
	        }	
		}
	}


}