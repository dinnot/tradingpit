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
	
<<<<<<< HEAD
?>
=======
?>	
>>>>>>> d56753890ac0484691a1bcf6baa6e7b0a97d37ce
