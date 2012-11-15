<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Users_fx_positions_model extends CI_Model {
	
		public function __construct () {
			
			$this->load->database() ;  
		}
		
		public function get_users_fx_positions ( $user_id ) {
	
			$what = array() ; 
			
			$what[] = "users_fx_positions.amount as amount" ;
			$what[] = "shortname as currency_name" ;
			$what[] = "banks_balances.amount as banks_amount" ;
			$what[] = "rate" ;
				
			$this->db->select($what) ;
			$this->db->from('users_fx_positions');
			$this->db->join('currencies','users_fx_positions.currencies_id = currencies.id','left');
			$this->db->join('users','users.id = users_id','left');
			$this->db->join('jobs','users.jobs_id = jobs.id','left');
			$this->db->join('banks_balances','jobs.banks_id = banks_balances.banks_id AND currencies.id = banks_balances.currencies_id','left');
			$this->db->where('users_id',$user_id);
			$query = $this->db->get();
			 
			return $query->result_array() ;
		}
		
		public function get_reporting_currencies() {
			
			$this->db->select('value');
			$this->db->from('gamesettings');
			$this->db->where('id >= 7 AND id <= 10');
			$query = $this->db->get() ; 
			
			return $query->result_array();
		}	
	}
	
