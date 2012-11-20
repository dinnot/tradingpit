<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Dealing_model extends CI_Model {
	
		public function __construct () {
			
			$this->load->database() ;  
		}
		
		public function get_fx_deals ( $user_id ) {
		
			$what = array() ; 
			$what[] = "amount_base_ccy" ;		
			$what[] = "price" ;
			$what[] = "u2.username as counter_party" ;
			$what[] = "value_date" ;
			$what[] = "trade_date" ;
			$what[] = "fx_deals.id as fx_deals_id" ;
			$what[] = "u1.username as user_name" ;
			$what[] = "c1.shortname as first_currency" ;
			$what[] = "c2.shortname as second_currency" ;
			$what[] = "periods.name as period_name" ;
			
			
			$this->db->select($what);
			$this->db->from('fx_deals');
			$this->db->join('currency_pairs','currency_pairs.id = ccy_pair','left');  
			$this->db->join('currencies c1','currency_pairs.currency0 = c1.id','left') ;
			$this->db->join('currencies c2','currency_pairs.currency1 = c2.id','left') ;
			$this->db->join('periods','periods.id = period','left') ;
			$this->db->join('users u1','u1.id = user_id','left');
			$this->db->join('users u2', 'u2.id = counter_party','left'); 
			$this->db->where('counter_party',$user_id );
			$this->db->or_where('user_id',$user_id);
			$this->db->order_by('trade_date','desc');
			$this->db->limit(10,0);
			$query = $this->db->get();
			
			return $query->result_array();
			
		}

		public function get_mm_deals ( $user_id ) {
		
			$what = array() ; 
			$what[] = "amount_base_ccy" ;		
			$what[] = "price" ;
			$what[] = "u2.username as counter_party" ;
			$what[] = "value_date" ;
			$what[] = "trade_date" ;
			$what[] = "mm_deals.id as mm_deals_id" ;
			$what[] = "u1.username as user_name" ;
			$what[] = "c1.shortname as first_currency" ;
			$what[] = "c2.shortname as second_currency" ;
			$what[] = "periods.name as period_name" ;
		
			$this->db->select($what);
			$this->db->from('mm_deals');
			$this->db->join('currency_pairs','currency_pairs.id = ccy_pair','left');  
			$this->db->join('currencies c1','currency_pairs.currency0 = c1.id','left') ;
			$this->db->join('currencies c2','currency_pairs.currency1 = c2.id','left') ;
			$this->db->join('periods','periods.id = period','left') ;
			$this->db->join('users u1','u1.id = user_id','left');
			$this->db->join('users u2', 'u2.id = counter_party','left'); 
			$this->db->where('counter_party',$user_id );
			$this->db->or_where('user_id',$user_id);
			$this->db->order_by('trade_date','desc');
			$this->db->limit(10,0);
			$query = $this->db->get();
			
			return $query->result_array();
			
		}
		
	}
	