<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Clients_model extends CI_Model {

		function __construct () {
			parent::__construct();
		}
		
		// imi incarc ofertele de la corporate din ultimile 24 ore, si vad daca s-a schimbat ceva fata de ce am in browser
		// daca am o oferta care e in pending, stabilesc rezultatul (set_result)
		function get_corporate_offers ($user_id) {
						
			$this->db->select ("*");
			$this->db->select ("clients_offers.date AS display_date");
			$this->db->from ("users_has_corporate_offers");
			$this->db->where (array ('user_id' => $user_id));
			$this->db->join ("clients_offers", "users_has_corporate_offers.offer_id = clients_offers.id", "left");			
			$this->db->join ("clients", "clients_offers.client_id = clients.id", "left");
			
			$query = $this->db->get ();			
			$offers = $query->result_array ();
		
			$offers_to_display = array ();
			$seconds = 24*3600;
			
			$time = time ();			
			$data = array ('date' => $time);			
			for ($i = 0; isset ($offers[$i]) ; $i++) {
				if ($time - $offers[$i]['display_date'] <= $seconds) 
					$offers_to_display[] = $offers[$i];
				
				if ($time - $offers[$i]['display_date'] >= 60 && $offers[$i]['pending'] == 1)
					$this->Clients_trading_model->set_result_corporate ($offers[$i]);
			}					
				
			return $offers_to_display;
		}

		// luam banca userului		
		public function get_user_bank ($user_id) {
			$this->db->from ("users");
			$this->db->where ("users.id", $user_id);
			$this->db->join ("jobs", "users.id = jobs.id", "left");			
			$result = $this->db->get ()->row ();
			
			return $result->banks_id;
		}		

	};	
	
?>
