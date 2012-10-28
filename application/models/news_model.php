<<<<<<< HEAD:application/models/news_model.php
<?php
	class News_Model extends CI_Model {
=======
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class News_model extends CI_Model {
>>>>>>> 8a878c0ee8588efb61256a688a9e3fe3f1717d7f:application/models/news_model.php
		
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
		
		//
	}
	
?>	
