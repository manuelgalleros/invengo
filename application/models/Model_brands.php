<?php 

class Model_brands extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*get the active brands information*/
	public function getActiveBrands()
	{
		$sql = "SELECT * FROM brands WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/*get the active brands data with full details*/
	public function getActiveBrandsData()
	{
		$sql = "SELECT * FROM brands WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getBrandData($id = null, $page = 1, $per_page = 10, $search = '')
	{
		if($id) {
			$sql = "SELECT * FROM brands WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		// Calculate offset
		$offset = ($page - 1) * $per_page;

		// Build the base query for counting
		$this->db->select('COUNT(*) as total');
		$this->db->from('brands');

		// Add search condition if search term is provided
		if (!empty($search)) {
			$this->db->like('name', $search);
		}

		// Get total rows for pagination
		$query = $this->db->get();
		$total_rows = $query->row()->total;

		// Get paginated results
		$this->db->select('*');
		$this->db->from('brands');

		// Add search condition again for the actual query
		if (!empty($search)) {
			$this->db->like('name', $search);
		}

		$this->db->order_by('id', 'DESC');
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();

		$brands = $query->result_array();

		return array(
			'brands' => $brands,
			'total_rows' => $total_rows,
			'per_page' => $per_page
		);
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('brands', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('brands', $data);
			return ($update == true) ? true : false;
		}
	}

	/**
	* Remove the brand data
	* @param int $id 
	* @return boolean
	*/
	public function remove($id)
	{
		if($id) {
			try {
				$this->db->where('id', $id);
				$result = $this->db->delete('brands');
				
				// Check if there was a database error
				if ($this->db->error()['code']) {
					$error_message = $this->db->error()['message'];
					
					// Check if it's a foreign key constraint violation
					if (strpos($error_message, 'foreign key constraint fails') !== false) {
						throw new Exception('Cannot delete brand with ID ' . $id . ' because it has products assigned to it.');
					}
					
					// For other database errors
					throw new Exception('Database error: ' . $error_message);
				}
				
				return ($result === true) ? true : false;
			} catch (Exception $e) {
				// Log the error
				log_message('error', 'Brand deletion error: ' . $e->getMessage());
				
				// Re-throw the exception to be caught by the controller
				throw $e;
			}
		}
	}

}