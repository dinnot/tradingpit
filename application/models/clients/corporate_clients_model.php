<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Corporate_clients_model extends CI_Model {

		function __construct () {
			parent::__construct();
		}
		
		// generare proasta :D

		function generate_coporate_client () {
			$offer['market'] = "FX";
			$offer['currency'] = rand () % 3 + 1;
			$offer['client_id'] = $this->Corporate_clients_model->get_random_client ();
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
