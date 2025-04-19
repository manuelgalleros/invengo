<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Admin_Controller 
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_auth');
		$this->load->model('model_logs');
		
		// Set Philippine timezone for all date/time operations
		date_default_timezone_set('Asia/Manila');
	}

	/* 
		Check if the login form is submitted, and validates the user credential
		If not submitted it redirects to the login page
	*/
public function login()
{
    $this->logged_in();

    // Initialize errors
    $this->data['errors'] = [];

    // Set validation rules
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'required');

    if ($this->form_validation->run() == TRUE) {
        // true case
        $email_exists = $this->model_auth->check_email($this->input->post('email'));

        if ($email_exists == TRUE) {
            $login = $this->model_auth->login($this->input->post('email'), $this->input->post('password'));

            if ($login) {
                $logged_in_sess = array(
                    'id' => $login['id'],
                    'username' => $login['username'],
                    'email' => $login['email'],
                    'logged_in' => TRUE
                );

                $this->session->set_userdata($logged_in_sess);
                
                // Log successful login
                $this->model_logs->create([
                    'user_id' => $login['id'],
                    'username' => $login['username'],
                    'action' => 'Login',
                    'description' => 'Auth: User logged in: ' . $login['username'],
                    'created_at' => time(),
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
                
                redirect('dashboard', 'refresh');
            } else {
                $this->data['errors'][] = 'Incorrect email and password combination';
                
                // Log failed login attempt
                $this->model_logs->create([
                    'user_id' => 0,
                    'username' => 'Unknown',
                    'action' => 'Login',
                    'description' => 'Auth: Failed login attempt for email: ' . $this->input->post('email'),
                    'created_at' => time(),
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
            }
        } else {
            $this->data['errors'][] = 'Email does not exist';
        }
    } else {
        // Collect validation errors
        $this->data['errors'] = array_merge($this->data['errors'], $this->form_validation->error_array());
    }

    // Load the view with errors
    $this->load->view('auth-login', $this->data);
}

	/**
	 * Handle AJAX login requests
	 * Returns JSON response instead of redirecting
	 */
	public function login_ajax()
	{
		// Check if already logged in
		if($this->session->userdata('logged_in')) {
			echo json_encode([
				'success' => true, 
				'message' => 'Already logged in',
				'redirect' => base_url('dashboard')
			]);
			return;
		}
		
		// Initialize response
		$response = [
			'success' => false,
			'message' => 'An error occurred',
		];
		
		// Set validation rules
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == TRUE) {
			// Check if email exists
			$email_exists = $this->model_auth->check_email($this->input->post('email'));
			
			if ($email_exists == TRUE) {
				// Attempt to log in
				$login = $this->model_auth->login($this->input->post('email'), $this->input->post('password'));
				
				if ($login) {
					// Create session data
					$logged_in_sess = array(
						'id' => $login['id'],
						'username' => $login['username'],
						'email' => $login['email'],
						'logged_in' => TRUE
					);
					
					$this->session->set_userdata($logged_in_sess);
					
					// Log successful login
					$this->model_logs->create([
						'user_id' => $login['id'],
						'username' => $login['username'],
						'action' => 'Login',
						'description' => 'Auth: User logged in: ' . $login['username'],
						'created_at' => time(),
						'timestamp' => date('Y-m-d H:i:s')
					]);
					
					// Return success response
					$response = [
						'success' => true,
						'message' => 'Login successful. Redirecting...',
						'redirect' => base_url('dashboard')
					];
				} else {
					// Log failed login attempt
					$this->model_logs->create([
						'user_id' => 0,
						'username' => 'Unknown',
						'action' => 'Login',
						'description' => 'Auth: Failed login attempt for email: ' . $this->input->post('email'),
						'created_at' => time(),
						'timestamp' => date('Y-m-d H:i:s')
					]);
					
					$response['message'] = 'Incorrect email and password combination';
				}
			} else {
				$response['message'] = 'Email does not exist';
			}
		} else {
			// Validation errors
			$response['message'] = validation_errors('', '');
		}
		
		// Return JSON response
		echo json_encode($response);
	}

	/*
		clears the session and redirects to login page
	*/
	public function logout()
	{
		// Log logout before destroying session
		if($this->session->userdata('logged_in')) {
			$this->model_logs->create([
				'user_id' => $this->session->userdata('id'),
				'username' => $this->session->userdata('username'),
				'action' => 'Logout',
				'description' => 'Auth: User logged out: ' . $this->session->userdata('username'),
				'created_at' => time(),
				'timestamp' => date('Y-m-d H:i:s')
			]);
		}
		
		$this->session->sess_destroy();
		redirect('auth/login', 'refresh');
	}

}
