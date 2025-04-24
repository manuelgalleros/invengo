<?php 

class Model_products extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getProductData($id = null, $page = 1, $per_page = 10, $search = '')
    {
        if ($id) {
            $sql = "SELECT p.*, c.name as category_name, b.name as brand 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN brands b ON p.brand_id = b.id 
                    WHERE p.id = ?";
            $query = $this->db->query($sql, array($id));
            
            // Check if product exists
            if ($query->num_rows() == 0) {
                return null;
            }
            
            $product = $query->row_array();

            // Access array keys safely
            $category_ids = isset($product['category_id']) ? $product['category_id'] : null;
            $brand_ids = isset($product['brand_id']) ? $product['brand_id'] : null;

            // Only attempt to get names if IDs exist
            if ($category_ids) {
                $product['category_name'] = $this->getCategoryNames($category_ids);
            } else {
                $product['category_name'] = 'N/A';
            }
            
            if ($brand_ids) {
                $product['brand'] = $this->getBrandName($brand_ids);
            } else {
                $product['brand'] = 'N/A';
            }

            return $product;
        }

        // Calculate offset
        $offset = ($page - 1) * $per_page;

        // Build the base query for counting
        $this->db->select('COUNT(*) as total');
        $this->db->from('products p1');
        $this->db->join('categories c', 'p1.category_id = c.id', 'left');
        $this->db->join('brands b', 'p1.brand_id = b.id', 'left');

        // Add search condition if search term is provided
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('p1.name', $search);
            $this->db->or_like('p1.sku', $search);
            $this->db->or_like('p1.description', $search);
            $this->db->or_like('c.name', $search);
            $this->db->or_like('b.name', $search);
            $this->db->group_end();
        }

        // Get total rows for pagination
        $query = $this->db->get();
        $total_rows = $query->row()->total;
        $total_pages = ceil($total_rows / $per_page);

        // Get paginated results
        $this->db->select('p1.*, c.name as category_name, b.name as brand');
        $this->db->from('products p1');
        $this->db->join('categories c', 'p1.category_id = c.id', 'left');
        $this->db->join('brands b', 'p1.brand_id = b.id', 'left');

        // Add search condition again for the actual query
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('p1.name', $search);
            $this->db->or_like('p1.sku', $search);
            $this->db->or_like('p1.description', $search);
            $this->db->or_like('c.name', $search);
            $this->db->or_like('b.name', $search);
            $this->db->group_end();
        }

        $this->db->order_by('p1.id', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get();

        $products = $query->result_array();

        // Loop through products to get category names
        foreach ($products as $key => $product) {
            $category_ids = $product['category_id'];
            $brand_ids = $product['brand_id'];
            $products[$key]['category_name'] = $this->getCategoryNames($category_ids);
            $products[$key]['brand'] = $this->getBrandName($brand_ids);
        }

        return array(
            'products' => $products,
            'total_pages' => $total_pages,
            'current_page' => $page,
            'total_rows' => $total_rows
        );
    }

    /**
     * Fetch category names based on category IDs
     */
    public function getCategoryNames($category_ids)
    {
       
        $this->db->select('name');
        $this->db->from('categories');
        $this->db->where_in('id', $category_ids);
        $query = $this->db->get();

        $category_names = array_column($query->result_array(), 'name');
        return implode(', ', $category_names); 
    }

     /**
     * Fetch brand names based on brand IDs
     */
    public function getBrandName($brand_ids)
    {

        $this->db->select('name');
        $this->db->from('brands');
        $this->db->where_in('id', $brand_ids);
        $query = $this->db->get();

        $brand_name = array_column($query->result_array(), 'name');
        return implode(', ', $brand_name); 
    }  
    


	public function getActiveProductData()
	{
		$sql = "SELECT * FROM products WHERE availability = ? ORDER BY id DESC";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('products', $data);
			return ($insert == true) ? $this->db->insert_id() : false;
		}
		return false;
	}
    
    public function insertProductImage($imageData) {
        $this->db->update('products', ['Image' => $imageData['image']]);
        
        return $this->db->affected_rows();
    }

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('products', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($product_ids)
	{
		if(!empty($product_ids)) {
			// Delete product images first
			$this->db->select('image');
			$this->db->where_in('id', $product_ids);
			$query = $this->db->get('products');
			$products = $query->result_array();
			
			foreach($products as $product) {
				if($product['image'] && $product['image'] != 'no-image.jpg') {
					$image_path = 'assets/images/product_images/' . $product['image'];
					if(file_exists($image_path)) {
						unlink($image_path);
					}
				}
			}
			
			// Delete products from database
			$this->db->where_in('id', $product_ids);
			$delete = $this->db->delete('products');
			
			return $delete;
		}
		return false;
	}

	public function countTotalProducts()
	{
		$sql = "SELECT COUNT(*) as total_products FROM products";
		$query = $this->db->query($sql);
		return $query->row()->total_products;
	}

	/**
	 * Get product by barcode
	 * Returns product data if barcode exists, null otherwise
	 */
	public function getProductByBarcode($barcode = null)
	{
		if($barcode) {
			// Log the barcode lookup with additional detail
			log_message('debug', 'Looking up product by barcode: "' . $barcode . '", Length: ' . strlen($barcode) . ', Type: ' . gettype($barcode));
			
			// Cast the barcode to string to ensure consistent comparison
			$barcode = (string)$barcode;
			
			// Use the query builder with a direct comparison
			$this->db->where('barcode', $barcode);
			$query = $this->db->get('products');
			
			// Log the SQL query that was executed
			log_message('debug', 'SQL query: ' . $this->db->last_query());
			
			// Return the product data if found
			if($query->num_rows() == 1) {
				log_message('debug', 'Product found for barcode: "' . $barcode . '"');
				return $query->row_array();
			}
			
			// If no exact match found, try a LIKE search as a fallback
			$this->db->like('barcode', $barcode);
			$query = $this->db->get('products');
			
			log_message('debug', 'Fallback LIKE query: ' . $this->db->last_query());
			log_message('debug', 'Fallback query rows: ' . $query->num_rows());
			
			if($query->num_rows() == 1) {
				log_message('debug', 'Product found for barcode via LIKE search');
				return $query->row_array();
			} else if($query->num_rows() > 1) {
				log_message('debug', 'Multiple products found for barcode via LIKE search, using first match');
				return $query->row_array();
			}
			
			log_message('debug', 'No product found for barcode: "' . $barcode . '"');
			return false;
		}
		
		// If no barcode provided, log and return false
		log_message('error', 'getProductByBarcode called with no barcode parameter');
		return false;
	}

}