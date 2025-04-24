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
		if($id) {
			try {
				$this->db->where('id', $id);
				$result = $this->db->delete('groups');
				
				// Check if there was a database error
				if ($this->db->error()['code']) {
					$error_message = $this->db->error()['message'];
					
					// Check if it's a foreign key constraint violation
					if (strpos($error_message, 'foreign key constraint fails') !== false) {
						throw new Exception('Cannot delete group with ID ' . $id . ' because it has users assigned to it.');
					}
					
					// For other database errors
					throw new Exception('Database error: ' . $error_message);
				}
				
				return ($result === true) ? true : false;
			} catch (Exception $e) {
				// Log the error
				log_message('error', 'Group deletion error: ' . $e->getMessage());
				
				// Re-throw the exception to be caught by the controller
				throw $e;
			}
		}
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