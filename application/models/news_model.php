<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class News_Model extends CI_Model {
		
		public function __construct() {
			
			$this->load->database();

		    	parent::__construct();

		}
	
		public function get_news() {
		
			$what = array() ;
			 
			$what[] = "date" ; 
			$what[] = "headline" ; 
			$what[] = "body" ; 
			$what[] = "countries_id" ;
			$what[] = "countries.name as country_name" ; 			  
			
			$this->db->select($what);
			$this->db->from("news");
			$this->db->join("countries"," countries.id = countries_id ","left") ;
			
			$this->apply_body_filter() ;
			$this->apply_country_filter() ;
			
			$this->db->order_by("date","desc");
			$query = $this->db->get() ; 
			return $query->result_array();
		}
		
		public function apply_body_filter() {
			$body_filter = $this->input->get_post ("body_filter");
			
				if( $body_filter ) 
					$this->db->like('body',$body_filter);
		}
		
		public function apply_country_filter() {
			$country_filter = $this->input->get_post("country_filter");
			
				if( $country_filter != 0 ) 
					$this->db->where("countries.id",$country_filter);
		}
	}
	
?>

