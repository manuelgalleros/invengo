<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Products';

		$this->load->model('model_products');
		$this->load->model('model_brands');
		$this->load->model('model_category');
		$this->load->model('model_stores');
        $this->load->model('model_users');
		$this->load->model('model_attributes');
        $this->load->library('upload');
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
                    <span class="badge rounded-pill '.($product['qty'] > 10 ? 'badge-soft-success' : 'badge-soft-danger').'">
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
            
            // Previous button
            paginationHtml += \'<li class="page-item \' + (currentPage === 1 ? "disabled" : "") + \'">\';
            paginationHtml += \'<a href="#" class="page-link" data-page="\' + (currentPage - 1) + \'"><i class="ti ti-chevrons-left"></i></a>\';
            paginationHtml += \'</li>\';

            // Page numbers
            for(var i = 1; i <= totalPages; i++) {
                paginationHtml += \'<li class="page-item \' + (i === currentPage ? "active" : "") + \'">\';
                paginationHtml += \'<a href="#" class="page-link" data-page="\' + i + \'">\' + i + \'</a>\';
                paginationHtml += \'</li>\';
            }

            // Next button
            paginationHtml += \'<li class="page-item \' + (currentPage === totalPages ? "disabled" : "") + \'">\';
            paginationHtml += \'<a href="#" class="page-link" data-page="\' + (currentPage + 1) + \'"><i class="ti ti-chevrons-right"></i></a>\';
            paginationHtml += \'</li>\';

            $("#productFooter .pagination").html(paginationHtml);

            // Clear and update the range info
            $("#productFooter .text-muted").empty();
            var rangeHtml = \'<div>Showing \' + start + \' to \' + end + \' of \' + totalRows + \' results</div>\';
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
    * If the validation is not valid, then it redirects to the create page.
    * If the validation for each input field is valid then it inserts the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
public function create()
{
    if (!in_array('createProduct', $this->permission)) {
        redirect('dashboard', 'refresh');
    }

    $this->data['brands'] = $this->model_brands->getActiveBrands();        	
    $this->data['category'] = $this->model_category->getActiveCategroy();     
    $user_id = $this->session->userdata('id');
    $this->data['user_data'] = $this->model_users->getUserData($user_id);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $this->render_template('products/create', $this->data);
        return;
    }

    // Validate Form Data
    $this->form_validation->set_rules('product_name', 'Product Name', 'trim|required');
    $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
    $this->form_validation->set_rules('price', 'Price', 'trim|required|numeric');
    $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|integer');
    $this->form_validation->set_rules('brand', 'Brand', 'trim|required');
    $this->form_validation->set_rules('category', 'Category', 'trim|required');
    $this->form_validation->set_rules('availability', 'Availability', 'trim|required|integer');

    if ($this->form_validation->run() == FALSE) {
        $errors = $this->form_validation->error_array();
        echo json_encode(["success" => false, "messages" => $errors]);
        return;
    }

    // Upload Image or Assign Default
    $upload_image = $this->upload_image();


    // Prepare Data for Insertion
    $data = array(
        'name' => $this->input->post('product_name'),
        'sku' => $this->input->post('sku'),
        'price' => $this->input->post('price'),
        'qty' => $this->input->post('quantity'),
        'barcode' => $this->input->post('barcode'),
        'image' => $upload_image,  
        'description' => $this->input->post('description'),
        'brand_id' => $this->input->post('brand'),
        'category_id' => $this->input->post('category'),
        'availability' => $this->input->post('availability'),
    );

    $create = $this->model_products->create($data);

    header('Content-Type: application/json');

    if ($create) {
        echo json_encode(["success" => true, "message" => "Product added successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "An error occurred. Please try again."]);
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
        if(!in_array('updateProduct', $this->permission)) {
            $response['success'] = false;
            $response['message'] = 'You do not have permission to update products';
            echo json_encode($response);
            return;
        }

        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required|numeric');
        $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|numeric');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('category', 'Category', 'trim|required');
        $this->form_validation->set_rules('brand', 'Brand', 'trim|required');
        $this->form_validation->set_rules('availability', 'Availability', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $product_id = $this->input->post('product_id');
            
            // Prepare product data
            $data = array(
                'name' => $this->input->post('product_name'),
                'sku' => $this->input->post('sku'),
                'price' => $this->input->post('price'),
                'qty' => $this->input->post('quantity'),
                'description' => $this->input->post('description'),
                'category_id' => $this->input->post('category'),
                'brand_id' => $this->input->post('brand'),
                'availability' => $this->input->post('availability'),
                'barcode' => $this->input->post('barcode')
            );

            // Handle image upload if a new image is provided
            if(!empty($_FILES['product_image']['name'])) {
                $config['upload_path'] = 'assets/images/product_images/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '2048'; // 2MB max
                $config['file_name'] = uniqid() . '_' . $_FILES['product_image']['name'];

                // Make sure the upload directory exists
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                
                if($this->upload->do_upload('product_image')) {
                    $upload_data = $this->upload->data();
                    
                    // Get old image path
                    $old_image = $this->model_products->getProductData($product_id)['image'];
                    
                    // Delete old image if it exists and is not the default image
                    if($old_image && $old_image != 'no-image.jpg' && file_exists($config['upload_path'] . basename($old_image))) {
                        unlink($config['upload_path'] . basename($old_image));
                    }
                    
                    $data['image'] = 'assets/images/product_images/' . $upload_data['file_name'];
                } else {
                    $response['success'] = false;
                    $response['message'] = $this->upload->display_errors();
                    echo json_encode($response);
                    return;
                }
            }

            $update = $this->model_products->update($data, $product_id);
            if($update) {
                $response['success'] = true;
                $response['message'] = 'Product "' . $this->input->post('product_name') . '" has been successfully updated';
            } else {
                $response['success'] = false;
                $response['message'] = 'Error updating product';
            }
        } else {
            $response['success'] = false;
            $response['message'] = validation_errors();
        }

        echo json_encode($response);
	}

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
	public function remove()
	{
        if(!in_array('deleteProduct', $this->permission)) {
            $response['success'] = false;
            $response['message'] = 'You do not have permission to delete products.';
        } else {
            $product_ids = $this->input->post('product_ids');
            
            if(!empty($product_ids)) {
                // Get product names before deletion
                $this->db->select('id, name');
                $this->db->where_in('id', $product_ids);
                $query = $this->db->get('products');
                $products = $query->result_array();
                
                $delete = $this->model_products->remove($product_ids);
                if($delete) {
                    $response['success'] = true;
                    $response['products'] = $products;
                    $response['count'] = count($products);
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Error occurred while removing product(s).';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'No products selected for deletion.';
            }
        }
        
        echo json_encode($response);
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

}