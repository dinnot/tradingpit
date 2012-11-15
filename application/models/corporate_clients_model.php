<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Corporate_clients_model extends CI_Model {

		function __construct () {
			parent::__construct();
		}
		
		function get_new_offers ($user_id) {
						
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
				/*if ($offers[$i]['display_date'] == 0) {
					$this->db->where ('id', $offers[$i]['offer_id']);	
					$offers[$i]['display_date'] = $time;
					
					$this->db->update ('clients_offers', $data);
					$offers_to_display[] = $offers[$i];
				}
				else */
				if ($time - $offers[$i]['display_date'] <= $seconds) 
					$offers_to_display[] = $offers[$i];
				
				if ($time - $offers[$i]['display_date'] >= 60 && $offers[$i]['status'] == 1)
					$this->set_result ($offers[$i]);
			}			
			
				
			return $offers_to_display;
		}
		
		function set_quote ($offer_id, $user_id, $quote) {
			$this->db->where ( array ('offer_id' => $offer_id, 'user_id' => $user_id) );
			$this->db->update ('users_has_corporate_offers', array ('quote' => $quote, 'status' => '1' ) );
		}
		
		function set_result (&$offer) {
			$this->db->select ("*");
			$this->db->from ("users_has_corporate_offers");			
			$this->db->order_by ("quote", "desc");
			$this->db->join ("clients_offers", "users_has_corporate_offers.offer_id = clients_offers.id", "left");									
			$this->db->where (array ('offer_id'=>$offer['offer_id']));
			$query = $this->db->get ();
			$results = $query->result_array ();
					
			$best_offer = $results[0]['quote'];
			foreach ($results as $item) {
				if ($item['quote'] == $best_offer && $best_offer != 0) 
					$status = 2;	
				else
					$status = 3;
				
				$offer['status'] = $status;
				$this->db->where ( array ('user_id'=>$item['user_id'], 'offer_id'=>$offer['offer_id']) );
				$this->db->update ('users_has_corporate_offers', array ('status'=>$status));
			}
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
				
		function get_users ($suggestion) {
			$number = rand () % 3 + 2;		
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
