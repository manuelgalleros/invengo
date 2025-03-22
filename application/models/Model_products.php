<?php 

class Model_products extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getProductData($id = null)
    {
        if ($id) {
            $sql = "SELECT * FROM products WHERE id = ?";
            $query = $this->db->query($sql, array($id));
            $product = $query->row_array();

            // Decode the category_id JSON array
            $category_ids = json_decode($product['category_id'], true);

            // Fetch category names
            $product['category_name'] = $this->getCategoryNames($category_ids);

            return $product;
        }

        $sql = "SELECT * FROM products ORDER BY id DESC";
        $query = $this->db->query($sql);
        $products = $query->result_array();

        // Loop through products to get category names
        foreach ($products as $key => $product) {
            $category_ids = json_decode($product['category_id'], true);
            $products[$key]['category_name'] = $this->getCategoryNames($category_ids);
        }

        return $products;
    }

    /**
     * Fetch category names based on category IDs
     */
    public function getCategoryNames($category_ids)
    {
        if (!is_array($category_ids) || empty($category_ids)) {
            return 'No Category';
        }

        $this->db->select('name');
        $this->db->from('categories');
        $this->db->where_in('id', $category_ids);
        $query = $this->db->get();

        $category_names = array_column($query->result_array(), 'name');
        return implode(', ', $category_names); // Return names as a comma-separated string
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
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('products', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('products');
			return ($delete == true) ? true : false;
		}
	}

	public function countTotalProducts()
	{
		$sql = "SELECT * FROM products";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

}