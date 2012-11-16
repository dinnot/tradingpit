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

		public function get_user_bank ($user_id) {
			$this->db->from ("users");
			$this->db->where ("users.id", $user_id);
			$this->db->join ("jobs", "users.id = jobs.id", "left");			
			$result = $this->db->get ()->row ();
			
			return $result->banks_id;
		}
		
    public function update_balances($user, $bank, $amount, $currency) {
      $this->db->set("amount", "amount + {$amount}", false)->where(array("users_id"=>$user, "currencies_id"=>$currency))->update("users_fx_positions");
      $this->db->set("amount", "amount + {$amount}", false)->where(array("banks_id"=>$user, "currencies_id"=>$currency))->update("banks_balances");
    }
		
		function insert_fx_deal ($deal) {
			$this->db->insert ("fx_deals", $deal);
		}
		
		function make_deal ($offer) {
			$bank = $this->get_user_bank ($offer['user_id']);
			if ($offer['deal'] == 1) $offer['amount'] = -$offer['amount'];
		
			$deal = 	array ();
			$deal['user_id'] = $offer['user_id'];
			$deal['ccy_pair'] = $offer['currency'];
			$deal['amount_base_ccy'] = $offer['amount'];
			$deal['price'] = $offer['quote'];
			$deal['counter_party'] = - ($offer['client_id']);
			$deal['value_date'] = $offer['date'];
			$deal['trade_date'] = $offer['date'];
		
			if ($offer['market'] == "FX") {
				$this->update_balances ($offer['user_id'], $bank, $offer['amount'], $offer['currency']);				
				$this->insert_fx_deal ($deal);
			}
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
				if ($status == 2) {
					$this->make_deal ($item);
				}
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
