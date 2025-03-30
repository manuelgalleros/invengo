<?php 

class Model_category extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get active brand infromation */
	public function getActiveCategroy()
	{
		$sql = "SELECT * FROM categories WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get active category data with full details */
	public function getActiveCategroyData()
	{
		$sql = "SELECT * FROM categories WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the brand data */
	public function getCategoryData($id = null, $page = 1, $search = '')
	{
		if($id) {
			$sql = "SELECT * FROM categories WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$limit = 10;
		$offset = ($page - 1) * $limit;

		$sql = "SELECT * FROM categories";
		
		if($search) {
			$sql .= " WHERE name LIKE ?";
			$query = $this->db->query($sql, array('%' . $search . '%'));
		} else {
			$query = $this->db->query($sql);
		}

		$total_records = $query->num_rows();
		$total_pages = ceil($total_records / $limit);

		// Get paginated results
		$sql .= $search ? " WHERE name LIKE ?" : "";
		$sql .= " LIMIT ? OFFSET ?";
		
		if($search) {
			$query = $this->db->query($sql, array('%' . $search . '%', $limit, $offset));
		} else {
			$query = $this->db->query($sql, array($limit, $offset));
		}

		return array(
			'data' => $query->result_array(),
			'total_records' => $total_records,
			'total_pages' => $total_pages,
			'current_page' => $page,
			'limit' => $limit
		);
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('categories', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('categories', $data);
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
			$delete = $this->db->delete('categories');
			return ($delete == true) ? true : false;
		}
	}

}