<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Dealing_Model extends CI_Model {
	
		public function __construct () {
			
			$this->load->database() ;  
		}
		
		public function get_fx_deals ( $username ) {
			
			$this->db->select("*");
			$this->db->from("fx_deals");
			$this->db->where('counter_party',$username);
			$this->db->or_where('user_id',$username);
			$this->db->order_by('trade_date','desc');
			$this->db->limit(10,0);
			$query = $this->db->get() ;
			
			return $query->result_array();
			
		}
		
		public function get_mm_deals ( $username ) {
			
			$this->db->select("*");
			$this->db->from("mm_deals");
			$this->db->where('counter_party',$username);
			$this->db->or_where('user_id',$username);
			$this->db->order_by('trade_date','desc');
			$this->db->limit(10,0);
			$query = $this->db->get() ;
			
			return $query->result_array();
		}
	}
	
