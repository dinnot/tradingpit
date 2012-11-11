<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class News_model extends CI_Model {
		
		public function __construct() {
			
    	parent::__construct();
		}
	
		public function get_news() {
		
			$this->db->select("*");
			$this->db->from("news");
			$this->db->join("countries"," countries.id = countries_id ","left") ;
			$query = $this->db->get() ; 
			return $query->result_array();
		}
	}
	
?>
