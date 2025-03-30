<?php 

class Model_users extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getUserData($userId = null) 
	{
		if($userId) {
			$sql = "SELECT * FROM users WHERE id = ?";
			$query = $this->db->query($sql, array($userId));
			return $query->row_array();
		}

		$sql = "SELECT * FROM users WHERE id != ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

    public function getUserGroup($userId = null) 
    {
        if ($userId) {
            $sql = "SELECT ug.group_id, g.group_name 
                    FROM user_group ug 
                    JOIN groups g ON ug.group_id = g.id 
                    WHERE ug.user_id = ?";
            $query = $this->db->query($sql, array($userId));
            return $query->row_array();
        }
        return null;
    }


	public function create($data = '', $group_id = null)
	{

		if($data && $group_id) {
			$create = $this->db->insert('users', $data);

			$user_id = $this->db->insert_id();

			$group_data = array(
				'user_id' => $user_id,
				'group_id' => $group_id
			);

			$group_data = $this->db->insert('user_group', $group_data);

			return ($create == true && $group_data) ? true : false;
		}
	}

	public function edit($data = array(), $id = null, $group_id = null)
	{
		$this->db->where('id', $id);
		$update = $this->db->update('users', $data);

		if($group_id) {
			// user group
			$update_user_group = array('group_id' => $group_id);
			$this->db->where('user_id', $id);
			$user_group = $this->db->update('user_group', $update_user_group);
			return ($update == true && $user_group == true) ? true : false;	
		}
			
		return ($update == true) ? true : false;	
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		$delete = $this->db->delete('users');
		return ($delete == true) ? true : false;
	}

	public function countTotalUsers()
	{
		$sql = "SELECT * FROM users";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	/**
	 * Get user data with pagination and search support
	 */
	public function getUserDataWithPagination($limit = 10, $offset = 0, $search = '')
	{
		// Skip admin user (ID = 1)
		$this->db->where('id !=', 1);
		
		// Apply search if provided
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('username', $search);
			$this->db->or_like('email', $search);
			$this->db->or_like('firstname', $search);
			$this->db->or_like('lastname', $search);
			$this->db->or_like('phone', $search);
			$this->db->group_end();
		}
		
		// Apply pagination
		$this->db->limit($limit, $offset);
		$this->db->order_by('id', 'DESC');
		
		$query = $this->db->get('users');
		return $query->result_array();
	}
	
	/**
	 * Get total number of users (for pagination)
	 */
	public function getTotalUsers($search = '')
	{
		// Skip admin user (ID = 1)
		$this->db->where('id !=', 1);
		
		// Apply search if provided
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('username', $search);
			$this->db->or_like('email', $search);
			$this->db->or_like('firstname', $search);
			$this->db->or_like('lastname', $search);
			$this->db->or_like('phone', $search);
			$this->db->group_end();
		}
		
		$query = $this->db->get('users');
		return $query->num_rows();
	}
}