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
            
            // Previous button
            paginationHtml += \'<li class="page-item \' + (currentPage === 1 ? "disabled" : "") + \'">\';
            paginationHtml += \'<a href="#" class="page-link" data-page="\' + (currentPage - 1) + \'">Previous</a>\';
            paginationHtml += \'</li>\';

            // Page numbers
            for(var i = 1; i <= totalPages; i++) {
                paginationHtml += \'<li class="page-item \' + (i === currentPage ? "active" : "") + \'">\';
                paginationHtml += \'<a href="#" class="page-link" data-page="\' + i + \'">\' + i + \'</a>\';
                paginationHtml += \'</li>\';
            }

            // Next button
            paginationHtml += \'<li class="page-item \' + (currentPage === totalPages ? "disabled" : "") + \'">\';
            paginationHtml += \'<a href="#" class="page-link" data-page="\' + (currentPage + 1) + \'">Next</a>\';
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
    $this->data['page_title'] = 'Add Products';
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
                                'image' => 'default-product.png' // Default image
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
                            
                            $response = array(
                                'success' => true,
                                'message' => $message,
                            );
                            
                            if (!empty($errors)) {
                                $response['errors'] = $errors;
                            }
                        } else {
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

}