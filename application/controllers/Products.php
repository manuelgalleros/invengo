<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Manage Products';

		$this->load->model('model_products');
		$this->load->model('model_brands');
		$this->load->model('model_category');
		$this->load->model('model_stores');
        $this->load->model('model_users');
		$this->load->model('model_attributes');
        $this->load->library('upload');
        $this->load->library('logs');

        // Ensure upload directory exists and is writable
        $upload_path = FCPATH . 'assets/images/product_images/';
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        } elseif (!is_writable($upload_path)) {
            chmod($upload_path, 0777);
        }
	}

    /* 
    * It only redirects to the manage product page
    */
    public function index()
    {
        if (!in_array('viewProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $user_id = $this->session->userdata('id');
        $this->data['user_data'] = $this->model_users->getUserData($user_id);
        $this->data['products'] = $this->model_products->getProductData(); 
        $this->data['brands'] = $this->model_brands->getActiveBrands();  
        $this->data['category'] = $this->model_category->getActiveCategroy(); 

        $this->render_template('products/index', $this->data);  
    }
    
    /* 
    * Fetches the product
    */
    public function get_products() {
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $per_page = 10;
        $search = $this->input->get('search') ? $this->input->get('search') : '';
        
        $result = $this->model_products->getProductData(null, $page, $per_page, $search);
        $products = $result['products'];
        $total_pages = $result['total_pages'];
        $current_page = $result['current_page'];
        $total_rows = $result['total_rows'];

        // Calculate the range being shown
        $start = ($page - 1) * $per_page + 1;
        $end = min($start + $per_page - 1, $total_rows);

        // Output products
        foreach ($products as $product) {
            echo '<tr id="row_'.$product['id'].'">
                <td class="ps-3"><input type="checkbox" class="form-check-input" value="'.$product['id'].'"></td>
                <td>'.$product['sku'].'</td>
                <td>
                    <div class="d-flex justify-content-start align-items-center gap-3">
                        <div class="avatar-md">
                            <img src="'.base_url().$product['image'].'" alt="'.$product['name'].'" class="img-fluid rounded-2">
                        </div>
                        '.$product['name'].'
                    </div>
                </td>
                <td>'.$product['brand'].'</td>
                <td>'.$product['description'].'</td>
                <td>₱'.number_format($product['price'], 2).'</td>
                <td>
                    <span class="badge '.($product['qty'] > 10 ? 'badge-outline-success' : 'badge-outline-danger').'">
                        '.$product['qty'].'
                    </span>
                </td>
                <td>'.$product['category_name'].'</td>
                <td>
                    <span class="badge rounded-pill '.($product['availability'] ? 'badge-soft-success' : 'badge-soft-danger').'">
                        '.($product['availability'] ? 'Available' : 'Out of Stock').'
                    </span>
                </td>';
            echo '</tr>';
        }

        // Output pagination and range info
        echo '<script>
            var totalPages = '.$total_pages.';
            var currentPage = '.$current_page.';
            var totalRows = '.$total_rows.';
            var start = '.$start.';
            var end = '.$end.';
            var paginationHtml = "";
            
            // First page button
            paginationHtml += \'<li class="page-item \' + (currentPage === 1 ? "disabled" : "") + \'">\';
            paginationHtml += \'<a href="#" class="page-link" data-page="1"><i class="ti ti-chevrons-left"></i></a>\';
            paginationHtml += \'</li>\';
            
            // Previous button
            paginationHtml += \'<li class="page-item \' + (currentPage === 1 ? "disabled" : "") + \'">\';
            paginationHtml += \'<a href="#" class="page-link" data-page="\' + (currentPage - 1) + \'">Previous</a>\';
            paginationHtml += \'</li>\';

            // Page numbers
            var startPage = Math.max(1, currentPage - 2);
            var endPage = Math.min(totalPages, currentPage + 2);

            if (currentPage <= 3) {
                endPage = Math.min(5, totalPages);
            }
            if (currentPage > totalPages - 2) {
                startPage = Math.max(totalPages - 4, 1);
            }

            for (var i = startPage; i <= endPage; i++) {
                paginationHtml += \'<li class="page-item \' + (i === currentPage ? "active" : "") + \'">\';
                paginationHtml += \'<a href="#" class="page-link" data-page="\' + i + \'">\' + i + \'</a>\';
                paginationHtml += \'</li>\';
            }

            // Next button
            paginationHtml += \'<li class="page-item \' + (currentPage === totalPages ? "disabled" : "") + \'">\';
            paginationHtml += \'<a href="#" class="page-link" data-page="\' + (currentPage + 1) + \'">Next</a>\';
            paginationHtml += \'</li>\';
            
            // Last page button
            paginationHtml += \'<li class="page-item \' + (currentPage === totalPages ? "disabled" : "") + \'">\';
            paginationHtml += \'<a href="#" class="page-link" data-page="\' + totalPages + \'"><i class="ti ti-chevrons-right"></i></a>\';
            paginationHtml += \'</li>\';

            $("#productFooter .pagination").html(paginationHtml);

            // Clear and update the range info
            $("#productFooter .text-muted").empty();
            var rangeHtml = \'<div>Showing \' + start + \' to \' + end + \' of \' + totalRows + \' products</div>\';
            $("#productFooter .text-muted").html(rangeHtml);

            // Handle pagination clicks
            $("#productFooter .page-link").click(function(e) {
                e.preventDefault();
                var page = $(this).data("page");
                if(page >= 1 && page <= totalPages) {
                    loadProductTable(page, $("#searchBox").val());
                }
            });
        </script>';
    }
    
    /*
    * Returns all product IDs for select all functionality
    */
    public function get_all_product_ids() {
        if(!in_array('viewProduct', $this->permission)) {
            $response = array('success' => false, 'message' => 'Permission denied');
            echo json_encode($response);
            return;
        }
        
        $search = $this->input->get('search') ? $this->input->get('search') : '';
        
        // Build the query to get all product IDs
        $this->db->select('id');
        $this->db->from('products');
        
        // Add search condition if search term is provided
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('sku', $search);
            $this->db->or_like('description', $search);
            $this->db->group_end();
        }
        
        $query = $this->db->get();
        $products = $query->result_array();
        
        // Extract just the IDs
        $product_ids = array_column($products, 'id');
        
        echo json_encode(array('success' => true, 'product_ids' => $product_ids));
    }

    /*
    * If the validation is not valid, then it redirects to the create page.
    * If the validation for each input field is valid then it inserts the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
    public function create()
    {
        if (!in_array('createProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
        // For AJAX requests, check if this is an AJAX request
        $is_ajax = $this->input->is_ajax_request();
        
        if ($is_ajax) {
            // For AJAX requests, we'll return JSON responses
            $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
            $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
            $this->form_validation->set_rules('sku', 'SKU', 'callback_check_duplicate');
            $this->form_validation->set_rules('price', 'Price', 'trim|required');
            $this->form_validation->set_rules('barcode', 'Barcode', 'trim|required');
            $this->form_validation->set_rules('category', 'Category', 'trim|required');
            $this->form_validation->set_rules('brand', 'Brand', 'trim|required');
            $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|numeric');
            
            if ($this->form_validation->run() === TRUE) {
                // Process the upload
                $upload_error = $this->handle_upload();
                if($upload_error) {
                    // Return upload error
                    echo json_encode([
                        'success' => false,
                        'message' => $upload_error
                    ]);
                    return;
                }
                
                $product_id = $this->model_products->create($this->upload_data);
                
                if($product_id) {
                    // Get the product details for logging
                    $product = $this->model_products->getProductData($product_id);
                    
                    // Log successful product creation with product SKU/ID
                    $this->logs->logActivity(
                        'create',
                        'Products',
                        'Created product: ' . $product['name'] . ' (SKU: ' . $product['sku'] . ', ID: ' . $product_id . ')',
                        true
                    );
                    
                    echo json_encode([
                        'success' => true,
                        'message' => 'Product created successfully',
                        'product_id' => $product_id
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error occurred while creating product in database'
                    ]);
                }
            } else {
                // Form validation failed
                echo json_encode([
                    'success' => false,
                    'message' => strip_tags(validation_errors())
                ]);
            }
            return;
        }
        
        // For regular form submissions (non-AJAX)
        $this->data['page_title'] = 'Add Products';
        $this->data['brands'] = $this->model_brands->getActiveBrands();        	
        $this->data['category'] = $this->model_category->getActiveCategroy();     
        $user_id = $this->session->userdata('id');
        $this->data['user_data'] = $this->model_users->getUserData($user_id);

        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
        $this->form_validation->set_rules('sku', 'SKU', 'callback_check_duplicate');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('category', 'Category', 'trim|required');
        $this->form_validation->set_rules('brand', 'Brand', 'trim|required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|numeric');

        if ($this->form_validation->run() === TRUE) {
            // Process the upload
            $upload_error = $this->handle_upload();
            if($upload_error) {
                // Handle upload error
                $this->session->set_flashdata('error', $upload_error);
                $this->render_template('products/create', $this->data);
                return;
            }
            
            $product_id = $this->model_products->create($this->upload_data);
            
            if($product_id) {
                // Get the product details for logging
                $product = $this->model_products->getProductData($product_id);
                
                // Log successful product creation with product SKU/ID
                $this->logs->logActivity(
                    'create',
                    'Products',
                    'Created product: ' . $product['name'] . ' (SKU: ' . $product['sku'] . ', ID: ' . $product_id . ')',
                    true
                );
                
                $this->session->set_flashdata('success', 'Successfully created');
                redirect('products/', 'refresh');
            }
            else {
                $this->session->set_flashdata('error', 'Error occurred!!');
                $this->render_template('products/create', $this->data);
            }
        } else {
            // false case
            $this->render_template('products/create', $this->data);
        }	
    }

    /**
     * Handle file upload for product images
     * This function is called from the create method to handle image uploads
     * @return string|null Returns error message on failure, null on success
     */
    public function handle_upload()
    {
        log_message('debug', 'Starting handle_upload method');
        
        // Set up the upload configuration
        $upload_path = 'assets/images/product_images/';
        $config['upload_path'] = $upload_path;
        $config['file_name'] = uniqid();
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '10000';
        
        // Initialize the upload library
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        
        // Log the POST data for debugging
        log_message('debug', 'POST data: ' . json_encode($_POST));
        
        // Set default data
        $this->upload_data = [
            'name' => $this->input->post('product_name'),
            'sku' => $this->input->post('sku'),
            'price' => $this->input->post('price'),
            'description' => $this->input->post('description'),
            'category_id' => $this->input->post('category'),
            'brand_id' => $this->input->post('brand'),
            'qty' => $this->input->post('quantity'),
            'barcode' => $this->input->post('barcode'),
            'availability' => $this->input->post('availability') ? 1 : 0
        ];
        
        log_message('debug', 'Product data: ' . json_encode($this->upload_data));
        
        // Check if a file was uploaded
        if(!empty($_FILES['product_image']['name'])) {
            log_message('debug', 'Attempting to upload file: ' . $_FILES['product_image']['name']);
            
            // Attempt to upload the file
            if($this->upload->do_upload('product_image')) {
                $upload_data = $this->upload->data();
                $this->upload_data['image'] = $upload_path . $upload_data['file_name'];
                log_message('debug', 'File upload successful: ' . $this->upload_data['image']);
                return null; // No error
            } else {
                // Log and return upload error
                $error = $this->upload->display_errors();
                log_message('error', 'File upload failed: ' . $error);
                return $error;
            }
        } else {
            // No file uploaded, use default image
            log_message('debug', 'No file uploaded, using default image');
            $this->upload_data['image'] = 'assets/images/product_images/no-image.jpg';
            return null; // No error
        }
    }

    /*
    * This function is invoked from another function to upload the image into the assets folder
    * and returns the image path
    */
    public function upload_image()
    {
        // Check if a file was uploaded
        if (empty($_FILES['product_image']['name'])) {
            return 'assets/images/product_images/no-image.jpg'; // Default image if no file is uploaded
        }

        $upload_path = 'assets/images/product_images/';
        $config['upload_path'] = $upload_path;
        $config['file_name'] = uniqid();
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '10000';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('product_image')) {
            return 'assets/images/product_images/no-image.jpg'; // Assign default if upload fails
        }

        // Get uploaded file data
        $upload_data = $this->upload->data();
        return $upload_path . $upload_data['file_name']; // Return file path
    }

    
    
    /*
    * If the validation is not valid, then it redirects to the edit product page 
    * If the validation is successfully then it updates the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
    public function update()
    {
        if (!in_array('updateProduct', $this->permission)) {
            echo json_encode(["success" => false, "message" => "You don't have permission to update products"]);
            return;
        }

        // Log the POST data for debugging
        log_message('debug', 'Products update POST data: ' . json_encode($_POST));
        
        $product_id = $this->input->post('product_id');
        
        // Validate form data
        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required|numeric');
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|numeric');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        
        if ($this->form_validation->run() === FALSE) {
            log_message('debug', 'Products update validation errors: ' . validation_errors());
            echo json_encode([
                "success" => false, 
                "message" => strip_tags(validation_errors())
            ]);
            return;
        }
        
        // Get product data before update for logging
        $product_before = $this->model_products->getProductData($product_id);
        
        // Prepare data for update
        $data = [
            'name' => $this->input->post('product_name'),
            'sku' => $this->input->post('sku'),
            'price' => $this->input->post('price'),
            'qty' => $this->input->post('quantity'),
            'description' => $this->input->post('description'),
            'category_id' => $this->input->post('category'),
            'brand_id' => $this->input->post('brand'),
            'barcode' => $this->input->post('barcode'),
            'availability' => $this->input->post('availability'),
        ];
        
        // Handle product image if uploaded
        if (!empty($_FILES['product_image']['name'])) {
            $upload_path = 'assets/images/product_images/';
            $config['upload_path'] = $upload_path;
            $config['file_name'] = uniqid();
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '10000';
            
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            
            if ($this->upload->do_upload('product_image')) {
                $upload_data = $this->upload->data();
                $data['image'] = $upload_path . $upload_data['file_name'];
            }
        }
        
        // Handle product data update
        $result = $this->model_products->update($data, $product_id);
        
        if ($result) {
            // Get updated product data for logging
            $product_after = $this->model_products->getProductData($product_id);
            
            // Log successful product update with product SKU/ID
            $this->logs->logActivity(
                'update',
                'Products',
                'Updated product: ' . $product_after['name'] . ' (SKU: ' . $product_after['sku'] . ', ID: ' . $product_id . ')',
                true
            );
            
            echo json_encode(["success" => true, "message" => "Product updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "An error occurred. Please try again."]);
        }
    }

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
    public function remove()
    {
        if (!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        // Check if we're getting a single product_id or an array of product_ids
        $product_ids = $this->input->post('product_ids');
        $product_id = $this->input->post('product_id');
        
        // Handle both single product deletion and bulk deletion
        if ($product_id) {
            // Single product deletion
            // Get product details before deletion for logging
            $product = $this->model_products->getProductData($product_id);
            
            if (!$product) {
                echo json_encode(["success" => false, "message" => "Product not found"]);
                return;
            }
            
            $delete = $this->model_products->remove($product_id);
            
            if ($delete) {
                // Log successful product deletion with product SKU/ID
                $this->logs->logActivity(
                    'delete',
                    'Products',
                    'Deleted product: ' . $product['name'] . ' (SKU: ' . $product['sku'] . ', ID: ' . $product_id . ')',
                    true
                );
                echo json_encode(["success" => true, "message" => "Product deleted successfully."]);
            } else {
                // Log failed deletion
                $this->logs->logActivity(
                    'delete',
                    'Products',
                    'Failed to delete product: ' . $product['name'] . ' (SKU: ' . $product['sku'] . ', ID: ' . $product_id . ')',
                    false
                );
                echo json_encode(["success" => false, "message" => "An error occurred. Please try again."]);
            }
        } 
        elseif ($product_ids && is_array($product_ids)) {
            // Bulk product deletion
            $deleted_products = [];
            $failed_products = [];
            
            // Log start of bulk operation
            log_message('debug', 'Starting bulk deletion of ' . count($product_ids) . ' products');
            
            if (empty($product_ids)) {
                echo json_encode([
                    "success" => false, 
                    "message" => "No products were selected for deletion"
                ]);
                return;
            }
            
            foreach ($product_ids as $id) {
                // Validate ID is numeric
                if (!is_numeric($id)) {
                    $failed_products[] = ['id' => $id, 'reason' => 'Invalid product ID'];
                    continue;
                }
                
                $product = $this->model_products->getProductData($id);
                
                if (!$product) {
                    $failed_products[] = ['id' => $id, 'reason' => 'Product not found'];
                    continue;
                }
                
                try {
                    $delete = $this->model_products->remove($id);
                    
                    if ($delete) {
                        $deleted_products[] = [
                            'id' => $id,
                            'name' => $product['name'],
                            'sku' => $product['sku']
                        ];
                        
                        // Log each successful deletion
                        $this->logs->logActivity(
                            'delete',
                            'Products',
                            'Deleted product: ' . $product['name'] . ' (SKU: ' . $product['sku'] . ', ID: ' . $id . ')',
                            true
                        );
                    } else {
                        $failed_products[] = [
                            'id' => $id,
                            'name' => $product['name'],
                            'sku' => $product['sku'],
                            'reason' => 'Database error'
                        ];
                        
                        // Log each failed deletion
                        $this->logs->logActivity(
                            'delete',
                            'Products',
                            'Failed to delete product: ' . $product['name'] . ' (SKU: ' . $product['sku'] . ', ID: ' . $id . ')',
                            false
                        );
                    }
                } catch (Exception $e) {
                    log_message('error', 'Exception during product deletion: ' . $e->getMessage());
                    $failed_products[] = [
                        'id' => $id,
                        'name' => isset($product['name']) ? $product['name'] : 'Unknown',
                        'sku' => isset($product['sku']) ? $product['sku'] : 'Unknown',
                        'reason' => 'Exception: ' . $e->getMessage()
                    ];
                }
            }
            
            // For very large deletions, just log the total count to minimize response size
            $total_deleted = count($deleted_products);
            $total_failed = count($failed_products);
            
            // Log overall bulk deletion
            if ($total_deleted > 0) {
                $this->logs->logActivity(
                    'delete',
                    'Products',
                    'Bulk deletion completed: ' . $total_deleted . ' products deleted successfully, ' . 
                    $total_failed . ' failed',
                    true
                );
            }
            
            log_message('debug', 'Bulk deletion complete. Success: ' . $total_deleted . ', Failed: ' . $total_failed);
            
            echo json_encode([
                "success" => !empty($deleted_products),
                "message" => $total_deleted . " product(s) deleted successfully. " . 
                             ($total_failed > 0 ? $total_failed . " product(s) failed." : ""),
                "products" => $deleted_products,
                "failed" => $failed_products,
                "count" => $total_deleted
            ]);
        } 
        else {
            echo json_encode(["success" => false, "message" => "No product IDs provided"]);
        }
    }

    /*
    * Fetches a single product's details
    * Returns the response in JSON format
    */
    public function get_product()
    {
        if(!in_array('updateProduct', $this->permission)) {
            echo json_encode(['success' => false, 'message' => 'You do not have permission to update products']);
            return;
        }

        $product_id = $this->input->post('product_id');
        
        if(!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
            return;
        }

        $product = $this->model_products->getProductData($product_id);
        
        if($product) {
            // Ensure image path is correct
            $image_path = $product['image'];
            if(!empty($image_path) && $image_path !== 'no-image.jpg') {
                $image_path = base_url() . $image_path;
            } else {
                $image_path = base_url() . 'assets/images/product_images/no-image.jpg';
            }

            echo json_encode([
                'success' => true,
                'data' => [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'sku' => $product['sku'],
                    'price' => $product['price'],
                    'quantity' => $product['qty'],
                    'description' => $product['description'],
                    'barcode' => $product['barcode'],
                    'brand_id' => $product['brand_id'],
                    'category_id' => $product['category_id'],
                    'availability' => $product['availability'],
                    'image' => $image_path
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
        }
    }

    public function import() {
        if(!in_array('createProduct', $this->permission)) {
            $response = array(
                'success' => false,
                'message' => 'You do not have permission to import products.'
            );
        } else {
            if(isset($_FILES['file'])) {
                $file = $_FILES['file'];
                
                // Check file extension
                $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if($file_ext != 'xlsx') {
                    $response = array(
                        'success' => false,
                        'message' => 'Please upload an Excel file (.xlsx)'
                    );
                } else {
                    require_once(APPPATH . '../vendor/autoload.php');
                    
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $spreadsheet = $reader->load($file['tmp_name']);
                    $worksheet = $spreadsheet->getActiveSheet();
                    
                    $rows = $worksheet->toArray();
                    $header = array_shift($rows); // Remove header row
                    
                    // Validate header
                    $required_headers = ['Product Name', 'SKU', 'Description', 'Barcode', 'Category', 'Brand', 'Price', 'Quantity', 'Availability'];
                    $header_matches = array_diff($required_headers, $header);
                    
                    if(!empty($header_matches)) {
                        $response = array(
                            'success' => false,
                            'message' => 'Invalid Excel format. Please check the column headers. Missing: ' . implode(', ', $header_matches)
                        );
                    } else {
                        // Get all available categories and brands for mapping
                        $categories = $this->model_category->getActiveCategroyData();
                        $brands = $this->model_brands->getActiveBrandsData();
                        
                        // Create lookup arrays for category and brand names to IDs
                        $category_lookup = [];
                        foreach ($categories as $category) {
                            $category_lookup[strtolower($category['name'])] = $category['id'];
                        }
                        
                        $brand_lookup = [];
                        foreach ($brands as $brand) {
                            $brand_lookup[strtolower($brand['name'])] = $brand['id'];
                        }
                        
                        $success_count = 0;
                        $error_count = 0;
                        $errors = [];
                        $row_count = 0;
                        $new_categories = [];
                        $new_brands = [];
                        
                        foreach($rows as $row) {
                            $row_count++;
                            
                            // Skip empty rows
                            if (empty($row[0])) {
                                continue;
                            }
                            
                            // Convert category and brand names to IDs
                            $category_name = trim($row[4]);
                            $brand_name = trim($row[5]);
                            
                            $category_id = null;
                            $brand_id = null;
                            
                            // Look up category ID from name or create a new category
                            if (isset($category_lookup[strtolower($category_name)])) {
                                $category_id = $category_lookup[strtolower($category_name)];
                            } else {
                                // Create a new category
                                $category_data = array(
                                    'name' => $category_name,
                                    'active' => 1
                                );
                                
                                $this->model_category->create($category_data);
                                $category_id = $this->db->insert_id();
                                
                                // Add to lookup array for future use
                                $category_lookup[strtolower($category_name)] = $category_id;
                                $new_categories[] = $category_name;
                            }
                            
                            // Look up brand ID from name or create a new brand
                            if (isset($brand_lookup[strtolower($brand_name)])) {
                                $brand_id = $brand_lookup[strtolower($brand_name)];
                            } else {
                                // Create a new brand
                                $brand_data = array(
                                    'name' => $brand_name,
                                    'active' => 1
                                );
                                
                                $this->model_brands->create($brand_data);
                                $brand_id = $this->db->insert_id();
                                
                                // Add to lookup array for future use
                                $brand_lookup[strtolower($brand_name)] = $brand_id;
                                $new_brands[] = $brand_name;
                            }
                            
                            // Validate required fields
                            if (empty($row[0]) || empty($row[1]) || empty($row[6]) || empty($row[7])) {
                                $errors[] = "Row $row_count: Missing required fields (Product Name, SKU, Price, or Quantity)";
                                $error_count++;
                                continue;
                            }
                            
                            // Validate numeric fields
                            if (!is_numeric($row[6]) || !is_numeric($row[7]) || !in_array($row[8], ['0', '1'])) {
                                $errors[] = "Row $row_count: Price, Quantity, or Availability has invalid format";
                                $error_count++;
                                continue;
                            }
                            
                            $data = array(
                                'name' => $row[0],
                                'sku' => $row[1],
                                'description' => $row[2],
                                'barcode' => $row[3],
                                'category_id' => $category_id,
                                'brand_id' => $brand_id,
                                'price' => $row[6],
                                'qty' => $row[7],
                                'availability' => $row[8],
                                'image' => 'assets/images/product_images/no-image.jpg' 
                            );
                            
                            if($this->model_products->create($data)) {
                                $success_count++;
                            } else {
                                $error_count++;
                                $errors[] = "Row $row_count: Database error - Failed to import product";
                            }
                        }
                        
                        if($success_count > 0) {
                            $message = "Import completed. Successfully imported: $success_count, Failed: $error_count";
                            
                            // Add info about created categories and brands
                            if (!empty($new_categories) || !empty($new_brands)) {
                                $created_msg = [];
                                if (!empty($new_categories)) {
                                    $unique_categories = array_unique($new_categories);
                                    $created_msg[] = "Created " . count($unique_categories) . " new categories";
                                }
                                if (!empty($new_brands)) {
                                    $unique_brands = array_unique($new_brands);
                                    $created_msg[] = "Created " . count($unique_brands) . " new brands";
                                }
                                
                                if (!empty($created_msg)) {
                                    $message .= " (" . implode(", ", $created_msg) . ")";
                                }
                            }
                            
                            // Log successful import
                            $this->logs->logActivity(
                                'Create', 
                                'Products', 
                                "Imported $success_count products with $error_count failures" . 
                                (!empty($created_msg) ? " (" . implode(", ", $created_msg) . ")" : "")
                            );
                            
                            $response = array(
                                'success' => true,
                                'message' => $message,
                            );
                            
                            if (!empty($errors)) {
                                $response['errors'] = $errors;
                            }
                        } else {
                            // Log failed import
                            $this->logs->logActivity(
                                'Create', 
                                'Products', 
                                "Import failed. No products were imported. Error count: $error_count", 
                                false
                            );
                            
                            $response = array(
                                'success' => false,
                                'message' => "Import failed. No products were imported.",
                                'errors' => $errors
                            );
                        }
                    }
                }
            } else {
                $response = array(
                    'success' => false,
                    'message' => 'No file uploaded'
                );
            }
        }
        
        echo json_encode($response);
    }
    

    public function generate_sample_excel() {
        require_once(APPPATH . '../vendor/autoload.php');
        
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $headers = [
            'Product Name', 'SKU', 'Description', 'Barcode', 'Category', 'Brand', 'Price', 'Quantity', 'Availability'
        ];
        
        // Add headers to the sheet
        foreach(range('A', 'I') as $index => $column) {
            $sheet->setCellValue($column . '1', $headers[$index]);
        }
        
        // Get some real category and brand names from database
        $categories = $this->model_category->getActiveCategroyData();
        $brands = $this->model_brands->getActiveBrandsData();
        
        // Default values if no categories or brands exist
        $category_names = ['General', 'Electronics', 'Accessories'];
        $brand_names = ['Generic', 'Premium', 'Standard'];
        
        // Use actual category and brand names if available
        if (!empty($categories)) {
            $category_names = array_column(array_slice($categories, 0, 3), 'name');
        }
        
        if (!empty($brands)) {
            $brand_names = array_column(array_slice($brands, 0, 3), 'name');
        }
        
        // Sample data
        $sample_data = [
            [
                'Gaming Laptop', 'LAP-001', 'High-performance gaming laptop with RTX 3080', '8901234567890',
                $category_names[0], $brand_names[0], '89999.99', '10', '1'
            ],
            [
                'Wireless Mouse', 'MOU-002', 'Ergonomic wireless mouse with RGB', '8901234567891',
                isset($category_names[1]) ? $category_names[1] : $category_names[0], 
                isset($brand_names[1]) ? $brand_names[1] : $brand_names[0], 
                '2499.99', '25', '1'
            ],
            [
                'Mechanical Keyboard', 'KEY-003', 'RGB mechanical keyboard with Cherry MX switches', '8901234567892',
                isset($category_names[2]) ? $category_names[2] : $category_names[0], 
                isset($brand_names[2]) ? $brand_names[2] : $brand_names[0], 
                '5499.99', '15', '1'
            ]
        ];
        
        // Add sample data to the sheet
        foreach($sample_data as $row_index => $row_data) {
            foreach(range('A', 'I') as $column_index => $column) {
                $sheet->setCellValue($column . ($row_index + 2), $row_data[$column_index]);
            }
        }
        
        // Add a comment to the Availability column header explaining the values
        $comment = $sheet->getComment('I1');
        if (!$comment) {
            $comment = $sheet->getCommentByColumnAndRow(9, 1);
        }
        $comment->setText('Use "1" for Available products, "0" for Unavailable products.');
        
        // Add a comment to the Category column header
        $commentCategory = $sheet->getComment('E1');
        if (!$commentCategory) {
            $commentCategory = $sheet->getCommentByColumnAndRow(5, 1);
        }
        $commentCategory->setText('If category does not exist, it will be created automatically.');
        
        // Add a comment to the Brand column header
        $commentBrand = $sheet->getComment('F1');
        if (!$commentBrand) {
            $commentBrand = $sheet->getCommentByColumnAndRow(6, 1);
        }
        $commentBrand->setText('If brand does not exist, it will be created automatically.');
        
        // Auto-size columns
        foreach(range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Set header style
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);
        
        // Add available categories and brands in a separate sheet for reference
        $infoSheet = $spreadsheet->createSheet();
        $infoSheet->setTitle('Available References');
        
        // Add available categories
        $infoSheet->setCellValue('A1', 'Available Categories');
        $infoSheet->getStyle('A1')->getFont()->setBold(true);
        
        foreach ($categories as $index => $category) {
            $infoSheet->setCellValue('A' . ($index + 2), $category['name']);
        }
        
        // Add available brands
        $infoSheet->setCellValue('C1', 'Available Brands');
        $infoSheet->getStyle('C1')->getFont()->setBold(true);
        
        foreach ($brands as $index => $brand) {
            $infoSheet->setCellValue('C' . ($index + 2), $brand['name']);
        }
        
        // Auto-size columns in info sheet
        $infoSheet->getColumnDimension('A')->setAutoSize(true);
        $infoSheet->getColumnDimension('C')->setAutoSize(true);
        
        // Set first sheet as active
        $spreadsheet->setActiveSheetIndex(0);
        
        // Create the writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="sample_products.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Save file to PHP output stream
        $writer->save('php://output');
        exit;
    }

    /**
     * Check if product field value already exists in database
     * Used for AJAX validation to prevent duplicate entries
     */
    public function check_duplicate($str = NULL)
    {
        // This is called in two contexts:
        // 1. As a form_validation callback (where $str is passed)
        // 2. As a direct AJAX endpoint (where POST parameters are used)
        
        // If this is a callback validation
        if ($str !== NULL) {
            // Get the field name from the validation context
            $field = $this->input->post('sku') ? 'sku' : ($this->input->post('barcode') ? 'barcode' : 'name');
            $value = $str;
            $product_id = $this->input->post('product_id');
            
            log_message('debug', 'Callback check_duplicate for ' . $field . ': ' . $value);
            
            // Set up the query
            $this->db->where($field, $value);
            
            // Exclude current product if editing
            if ($product_id) {
                $this->db->where('id !=', $product_id);
            }
            
            // Execute query
            $query = $this->db->get('products');
            
            // If it's duplicate, return FALSE (validation fails)
            if ($query->num_rows() > 0) {
                $this->form_validation->set_message('check_duplicate', 'The {field} already exists.');
                return FALSE;
            }
            
            return TRUE;
        }
        
        // If this is an AJAX call
        // Check permission
        if (!in_array('createProduct', $this->permission) && !in_array('updateProduct', $this->permission)) {
            echo json_encode(['duplicate' => false]);
            return;
        }
        
        // Get input parameters
        $field = $this->input->post('field');
        $value = $this->input->post('value');
        $product_id = $this->input->post('product_id');
        
        log_message('debug', 'AJAX check_duplicate for ' . $field . ': ' . $value);
        
        // Validate field parameter
        $allowed_fields = ['name', 'sku', 'barcode'];
        if (!in_array($field, $allowed_fields)) {
            echo json_encode(['duplicate' => false]);
            return;
        }
        
        // Set up the query
        $this->db->where($field, $value);
        
        // Exclude current product if editing
        if ($product_id) {
            $this->db->where('id !=', $product_id);
        }
        
        // Execute query
        $query = $this->db->get('products');
        
        // Return result
        echo json_encode(['duplicate' => ($query->num_rows() > 0)]);
    }

    public function archive()
    {
        // Permission checking
        if(!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $product_id = $this->input->post('product_id');
        
        // Get product data before archiving
        $product_data = $this->model_products->getProductData($product_id);
        
        if($product_id) {
            $delete = $this->model_products->archive($product_id);
            
            if($delete == true) {
                // Product archived successfully
                $this->logs->logActivity(
                    'Archive',
                    'Products',
                    "Product archived: " . $product_data['name'] . " (ID: $product_id)",
                    true
                );
                
                $response['success'] = true;
                $response['messages'] = 'Successfully archived';
            }
            else {
                // Archive failed
                $this->logs->logActivity(
                    'Archive',
                    'Products',
                    "Failed to archive product: " . ($product_data ? $product_data['name'] : "ID: $product_id"),
                    false
                );
                
                $response['success'] = false;
                $response['messages'] = 'Error in the database while archiving the product information';
            }
        }
        else {
            $this->logs->logActivity(
                'Archive',
                'Products',
                "Failed to archive product: No product ID provided",
                false
            );
            
            $response['success'] = false;
            $response['messages'] = 'Error in the database while archiving the product information';
        }

        echo json_encode($response);
    }

    public function restore()
    {
        // Permission checking
        if(!in_array('updateProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $product_id = $this->input->post('product_id');
        
        // Get product data before restoring
        $product_data = $this->model_products->getProductData($product_id);
        
        if($product_id) {
            $restore = $this->model_products->restore($product_id);
            
            if($restore == true) {
                // Product restored successfully
                $this->logs->logActivity(
                    'Restore',
                    'Products',
                    "Product restored: " . $product_data['name'] . " (ID: $product_id)",
                    true
                );
                
                $response['success'] = true;
                $response['messages'] = 'Successfully restored';
            }
            else {
                // Restore failed
                $this->logs->logActivity(
                    'Restore',
                    'Products',
                    "Failed to restore product: " . ($product_data ? $product_data['name'] : "ID: $product_id"),
                    false
                );
                
                $response['success'] = false;
                $response['messages'] = 'Error in the database while restoring the product information';
            }
        }
        else {
            $this->logs->logActivity(
                'Restore',
                'Products',
                "Failed to restore product: No product ID provided",
                false
            );
            
            $response['success'] = false;
            $response['messages'] = 'Error in the database while restoring the product information';
        }

        echo json_encode($response);
    }

    /*
     * Fetches the product data by barcode via AJAX
     */
    public function fetch_product_by_barcode()
    {
        // Check permissions
        if (!in_array('viewProduct', $this->permission)) {
            $response = array(
                'success' => false, 
                'messages' => 'Permission denied'
            );
            echo json_encode($response);
            return;
        }
        
        // Get the barcode from POST data
        $barcode = $this->input->post('barcode');
        
        // Validate barcode input
        if (empty($barcode)) {
            $response = array(
                'success' => false,
                'messages' => 'Barcode is required'
            );
            echo json_encode($response);
            return;
        }
        
        // Log the barcode being fetched
        log_message('debug', 'Products: Fetching product with barcode: ' . $barcode);
        
        // Get product by barcode
        $product = $this->model_products->getProductByBarcode($barcode);
        
        if ($product) {
            // Log successful product retrieval
            log_message('info', 'Products: Retrieved product by barcode: ' . $barcode . ' (Product: ' . $product['name'] . ', ID: ' . $product['id'] . ')');
            
            $response = array(
                'success' => true,
                'product' => $product,
                'messages' => 'Product found'
            );
        } else {
            // Log product not found
            log_message('error', 'Products: Product not found with barcode: ' . $barcode);
            
            $response = array(
                'success' => false,
                'messages' => 'No product found with the given barcode'
            );
        }
        
        echo json_encode($response);
    }

    /**
     * Lookup product by barcode
     * Returns product data in JSON format
     */
    public function barcode_lookup()
    {
        // Check if user has permission
        if(!in_array('viewProduct', $this->permission)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'You do not have sufficient permission']));
            return;
        }

        // Get barcode from request
        $barcode = $this->input->get('barcode');
        
        // Validate barcode
        if(!$barcode) {
            log_message('error', 'Product barcode lookup failed: No barcode provided');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'No barcode provided']));
            return;
        }

        // Log the lookup attempt
        log_message('info', 'Product barcode lookup attempt: ' . $barcode);
        
        // Get product data
        $product = $this->model_products->getProductByBarcode($barcode);
        
        // Check if product exists
        if($product) {
            // Get category and brand names
            if(isset($product['category_id']) && $product['category_id']) {
                $product['category_name'] = $this->model_products->getCategoryNames($product['category_id']);
            } else {
                $product['category_name'] = '';
            }
            
            if(isset($product['brand_id']) && $product['brand_id']) {
                $product['brand'] = $this->model_products->getBrandName($product['brand_id']);
            } else {
                $product['brand'] = '';
            }
            
            log_message('info', 'Product found for barcode: ' . $barcode);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => true, 'data' => $product]));
        } else {
            log_message('info', 'No product found for barcode: ' . $barcode);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'No product found for barcode: ' . $barcode]));
        }
    }

}