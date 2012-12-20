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
			$seconds = 80;
			
			$time = time ();			
			$data = array ('date' => $time);	
			$pairs = $this->clients_trading_model->get_pairs ();
			for ($i = 0; isset ($offers[$i]) ; $i++) {
				if ($time - $offers[$i]['display_date'] <= $seconds) {
					$offers[$i]['currency'] = $pairs[$offers[$i]['currency']];
					$offers[$i]['deal'] == 1 ? $offers[$i]['deal'] = "SELLS" : $offers[$i]['deal'] = "BUYS";
					$offers_to_display[] = $offers[$i];
				}
				
				if ($time - $offers[$i]['display_date'] >= 60 && $offers[$i]['pending'] == 1)
					$this->clients_trading_model->set_result_corporate ($offers[$i]);
			}					
				
			$this->update_activity ($user_id);
			return $offers_to_display;
		}
		
		function update_activity ($user_id) {
			$query = $this->db->from ('users_corporate_limits')->where ('user_id',$user_id)->get();	
			if ($query->num_rows == 0)
				$this->db->insert ('users_corporate_limits', array ('user_id'=>$user_id, 'ratio'=>'1', 'last_activity'=>time()));
			else
				$this->db->where ('user_id', $user_id)->update ('users_corporate_limits', array('last_activity'=>time()) );
		}
	
		function get_best_offer (&$offer) {
			$this->db->from ("users_has_corporate_offers");
			$this->db->where ("offer_id", $offer['offer_id']);
			
			if ($offer['market'] == "FX") {
				if ($offer['deal'] == "SELLS") {
					$this->db->order_by ("quote", "desc");
					$this->db->where ("quote >", 0);
				}
				else {
					$this->db->order_by ("quote", "asc");
					$this->db->where ("quote >", 0);
				}
				
				$query = $this->db->get ();
				if ($query->num_rows == 0)
					return 0;
					
				$results = $query->result_array ();				
				$best['quote'] = $results[0]['quote'];
				$best['num'] = 0;
				foreach ($results as $item) {
					if ($item['quote'] == $best['quote'])
						$best['num']++;
					else 
						break;
				}
				
				return $best;
			}	
			if ($offer['market'] == "MM") {
				return 0;
			}
			
			return 0;
		}

		function next_corporate_clients ($user_id) {
			$this->db->from ("users_corporate_limits");
			$this->db->where ('user_id', $user_id);
			$query = $this->db->get();
			if ($query->num_rows () == 0) // o sa se creeze la get_corporate_offers
				return ;
				
			$row = $query->row ();
		
			if ($row->available > time ()) 
				return ;
				
			$this->db->from ("users_corporate_limits");
			$this->db->where ("available <=", time () + 20);
			$this->db->where ("last_activity >=", time () - 10);
			$this->db->order_by ('user_id', 'random');
			$this->db->limit (rand () % 5 + 3);
			$users = $this->db->get ()->result_array ();
			$num = count ($users);
		
			if ($num < 1)
				return ;
						
			$group = array ();
			foreach ($users as $user)	{
				array_push ($group, $user['user_id']);
				$updates = array ();
				$updates['available'] = 60 + time() + 10 * ($user['num_clients'] + 1) + $user['penalty'] + rand () % 10 - 5;
				$updates['num_clients'] = $user['num_clients'] + 1;
		
				$this->db->where ('user_id', $user['user_id'])->update('users_corporate_limits', $updates);						
			}
			
			$client = $this->generate_corporate_client ($group);				
			if (rand () % 20 == 0) { // grupuri de clienti
				$client = $this->generate_corporate_client ($group);				
				if (rand () % 20 == 0)
					$client = $this->generate_corporate_client ($group);			
			}
		}
		/*
		function generate_for_all_users () { // o sa o folosesc la indicatori
			$last_check = $this->db->from ("variables")->where('name', 'last_check')->get()->row()->value;
			if (time () - $last_check < 15) return ;
			$this->db->where ('name', 'last_check')->update ('variables', array('value'=>time()));
			
			$this->db->from ("users");
			$this->db->join ("users_corporate_limits", "users.id = users_corporate_limits.user_id");
			$this->db->where ('available <=', time());
			$this->db->where ('last_activity >=', time () - 10);
			$this->db->order_by ('ratio');
			$users = $this->db->get ()->result_array ();			

			$ratio = $this->econ_model->get_last_ratio ();			
			$len = count ($users);			
			if ($len < 2)
				return ;
			for ($i = 0; $i < $len;) {
				$num = rand () % 5 + 3;
				$start = $i;
				$last = min ($len - 1, $i + $num - 1);
				$group = array ();
				
				for ($i = $start; $i <= $last; $i++) 
					array_push ($group, $users[$i]['id']);				
				// sa adaug si un bot cu un pret decent, pentru a castiga in fata ofertelor "nesimtite"				
				$client = $this->generate_corporate_client ($group);
								
				for ($i = $start; $i <= $last; $i++) {
					$updates = array ();
					//if ($users[$i]['available'] == 0)	$users[$i]['available'] = time ();
					
					$updates['available'] = 50 + time() + 5 * ($users[$i]['num_clients'] + 1) + $users[$i]['penalty'] + rand () % 10 - 5;
					$updates['num_clients'] = $users[$i]['num_clients'] + 1;
					$updates['sell_amount'] = $users[$i]['sell_amount'];
					$updates['buy_amount'] = $users[$i]['buy_amount'];
					if ($client['deal'] == 1) $updates['sell_amount'] = $users[$i]['sell_amount'] + $client['amount'];
					if ($client['deal'] == 2) $updates['buy_amount'] = $users[$i]['buy_amount'] + $client['amount'];
					
					if ($updates['buy_amount'] != 0) 
						$updates['ratio'] = $updates['sell_amount'] / $updates['buy_amount'];
					else 
						$updates['ratio'] = $users[$len-1]['ratio']; // max ratio				
					
					$this->db->where ('user_id', $users[$i]['id'])->update('users_corporate_limits', $updates);	
				}

				$i = $last + 1;
			}
			
		}*/

		function get_random_client () {
			$this->db->select ('id');			
			$this->db->from ('clients');
			$this->db->where ('id !=', 0);
			$this->db->order_by ('id', 'random');
			$this->db->limit (1);
			$query = $this->db->get ();			
			$client = $query->result_array ();
			return $client[0]['id'] ;
		}
	
		function generate_corporate_client ($users) {
			$offer['market'] = "FX";
			$offer['currency'] = rand () % 2 + 1;
			$offer['client_id'] = $this->get_random_client ();
			$offer['amount'] = (rand () % 49 + 1) * 100000;
			$offer['deal'] = rand () % 2 + 1;
			$offer['period_id'] = 1;
			$offer['date'] = time () + 1;
			$offer['pending'] = 1;
			
			$this->insert_client_offer ($offer, $users);
			return $offer;
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
				
	};	
?>
