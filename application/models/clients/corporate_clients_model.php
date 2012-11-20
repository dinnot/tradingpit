<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Corporate_clients_model extends CI_Model {

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
			$pairs = $this->clients_trading_model->get_pairs ();
			for ($i = 0; isset ($offers[$i]) ; $i++) {
				if ($time - $offers[$i]['display_date'] <= $seconds) {
					$offers[$i]['currency'] = $pairs[$offers[$i]['currency']];
					$offers[$i]['deal'] == 1 ? $offers[$i]['deal'] = "SELL" : $offers[$i]['deal'] = "BUY";
					$offers_to_display[] = $offers[$i];
				}
				
				if ($time - $offers[$i]['display_date'] >= 60 && $offers[$i]['pending'] == 1)
					$this->clients_trading_model->set_result_corporate ($offers[$i]);
			}					
				
			return $offers_to_display;
		}
	
		// generare proasta :D
		function generate_coporate_client () {
			$offer['market'] = "FX";
			$offer['currency'] = rand () % 2 + 1;
			$offer['client_id'] = $this->get_random_client ();
			$offer['amount'] = rand () % 10 + 1;
			$offer['deal'] = rand () % 2 + 1;
			$offer['period_id'] = 1;
			$offer['date'] = time () + 1;
			$offer['pending'] = 1;
			
			$users = $this->get_users ();
			$this->insert_client_offer ($offer, $users);
		}
		
		function get_random_client () {
			$this->db->select ('id');			
			$this->db->from ('clients');
			$this->db->order_by ('id', 'random');
			$this->db->limit (1);
			$query = $this->db->get ();			
			$client = $query->result_array ();
			return $client[0]['id'] ;
		}
		
		function insert_client_offer ($offer, $users) {
			$this->db->insert ('clients_offers', $offer);
			$id = $this->db->insert_id ();
			
			$relations = array ();
			$item = array ();
			foreach ($users as $user) {
				$item['user_id'] = $user;
				$item['offer_id'] = $id;
				array_push ($relations,  $item );
			}
			
			$this->db->insert_batch('users_has_corporate_offers', $relations);			
		}
				
		function get_users () {
			$number = rand () % 3 + 5;		
			$this->db->select ('id');			
			$this->db->from ('users');
			$this->db->order_by ('id', 'random');
			$this->db->limit ($number);
			$query = $this->db->get ();			
			$result = $query->result_array ();
			
			$users = array ();
			foreach ($result as $item)
				$users[] = $item['id'];			
			
			return $users;
		}	
	};	
	
?>
