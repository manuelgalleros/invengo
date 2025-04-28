<?php

class Product_model extends CI_Model {

    public function get_product_by_id($product_id) {
        $this->db->select('products.*, categories.name as category_name, brands.name as brand_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('brands', 'brands.id = products.brand_id', 'left');
        $this->db->where('products.id', $product_id);
        
        $query = $this->db->get();
        return $query->row_array();
    }
} 