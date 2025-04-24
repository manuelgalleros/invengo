<?php 

class Dashboard extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Dashboard | Invengo';
		
		$this->load->model('model_products');
		$this->load->model('model_orders');
		$this->load->model('model_users');
		$this->load->model('model_stores');
		$this->load->model('model_logs');
	}

	/* 
	* It only redirects to the manage category page
	* It passes the total product, total paid orders, total users, and total stores information
	into the frontend.
	*/
	public function index()
	{
        $user_id = $this->session->userdata('id');
		$this->data['total_products'] = $this->model_products->countTotalProducts();
		$this->data['total_paid_orders'] = $this->model_orders->countTotalPaidOrders();
		$this->data['total_users'] = $this->model_users->countTotalUsers();
		$this->data['todays_earnings'] = $this->model_orders->getTodaysEarnings();
		
		// Get recent activities for dashboard
		$recent_activities = $this->model_logs->get_activities(10, 0);
		$this->data['recent_activities'] = $recent_activities['activities'];
		
		// Get data for monthly earnings chart
		$this->data['monthly_earnings'] = $this->getMonthlyEarnings();
		
		// Get data for product categories chart
		$this->data['product_categories'] = $this->getProductCategories();
		
		$user_data = $this->model_users->getUserData($user_id);
		$this->data['user_data'] = $user_data;
        
		$is_admin = ($user_id == 1) ? true :false;

		$this->data['is_admin'] = $is_admin;
		$this->render_template('dashboard', $this->data);
	}
	
	/**
	 * Get monthly earnings for the current year
	 * 
	 * @return array Monthly earnings data
	 */
	private function getMonthlyEarnings()
	{
		$current_year = date('Y');
		$months = array();
		$earnings = array();
		
		// Generate data for all 12 months
		for ($i = 1; $i <= 12; $i++) {
			$month_start = strtotime($current_year . '-' . $i . '-01 00:00:00');
			$month_end = strtotime($current_year . '-' . $i . '-' . date('t', $month_start) . ' 23:59:59');
			
			$sql = "SELECT SUM(net_amount) as total 
					FROM orders 
					WHERE paid_status = ? 
					AND date_time BETWEEN ? AND ?";
			
			$query = $this->db->query($sql, array(1, $month_start, $month_end));
			$result = $query->row_array();
			
			$months[] = date('M', $month_start);
			$earnings[] = ($result['total']) ? round(floatval($result['total']), 2) : 0.00;
		}
		
		return array(
			'months' => $months,
			'earnings' => $earnings
		);
	}
	
	/**
	 * Get product distribution by categories
	 * 
	 * @return array Category distribution data
	 */
	private function getProductCategories()
	{
		$sql = "SELECT c.name, COUNT(p.id) as count 
				FROM products p 
				JOIN categories c ON p.category_id = c.id 
				GROUP BY p.category_id 
				ORDER BY count DESC 
				LIMIT 5";
		
		$query = $this->db->query($sql);
		$results = $query->result_array();
		
		$categories = array();
		$counts = array();
		
		foreach ($results as $row) {
			$categories[] = $row['name'];
			$counts[] = (int)$row['count'];
		}
		
		return array(
			'categories' => $categories,
			'counts' => $counts
		);
	}
}