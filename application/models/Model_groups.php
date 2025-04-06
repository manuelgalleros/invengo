<?php 

class Model_groups extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getGroupData($groupId = null) 
	{
		if($groupId) {
			$sql = "SELECT * FROM groups WHERE id = ?";
			$query = $this->db->query($sql, array($groupId));
			return $query->row_array();
		}

		$sql = "SELECT * FROM groups WHERE id != ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function create($data = '')
	{
		$create = $this->db->insert('groups', $data);
		return ($create == true) ? true : false;
	}

	public function edit($data, $id)
	{
		$this->db->where('id', $id);
		$update = $this->db->update('groups', $data);
		return ($update == true) ? true : false;	
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		$delete = $this->db->delete('groups');
		return ($delete == true) ? true : false;
	}

	public function existInUserGroup($id)
	{
		$sql = "SELECT * FROM user_group WHERE group_id = ?";
		$query = $this->db->query($sql, array($id));
		return ($query->num_rows() == 1) ? true : false;
	}

	public function getUserGroupByUserId($user_id) 
	{
		$sql = "SELECT * FROM user_group 
		INNER JOIN groups ON groups.id = user_group.group_id 
		WHERE user_group.user_id = ?";
		$query = $this->db->query($sql, array($user_id));
		$result = $query->row_array();

		return $result;
	}

	/**
	 * Get group data with pagination and search
	 * 
	 * @param int $limit Items per page
	 * @param int $offset Starting position
	 * @param string $search Search term
	 * @return array Array of groups
	 */
	public function getGroupDataWithPagination($limit = 10, $offset = 0, $search = '')
	{
		$this->db->select('*');
		$this->db->from('groups');
		$this->db->where('id !=', 1); // Exclude admin group
		
		// Apply search if provided
		if(!empty($search)) {
			$this->db->like('group_name', $search);
		}
		
		$this->db->limit($limit, $offset);
		$this->db->order_by('id', 'DESC');
		
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/**
	 * Get total count of groups for pagination
	 * 
	 * @param string $search Search term
	 * @return int Total count of groups
	 */
	public function getTotalGroups($search = '')
	{
		$this->db->select('COUNT(*) as count');
		$this->db->from('groups');
		$this->db->where('id !=', 1); // Exclude admin group
		
		// Apply search if provided
		if(!empty($search)) {
			$this->db->like('group_name', $search);
		}
		
		$query = $this->db->get();
		$result = $query->row_array();
		
		return $result['count'];
	}
}