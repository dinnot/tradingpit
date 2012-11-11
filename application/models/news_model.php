<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class News_Model extends CI_Model {
		
		public function __construct() {
			
			$this->load->database();
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
	
