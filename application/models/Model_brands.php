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

	public function remove($id)
	{
		if($id) {
			// Check if $id is an array for multiple deletion
			if(is_array($id)) {
				$this->db->where_in('id', $id);
			} else {
				$this->db->where('id', $id);
			}
			$delete = $this->db->delete('brands');
			return ($delete == true) ? true : false;
		}
	}

}