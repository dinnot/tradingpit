<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Retail_clients_model extends CI_Model {

		function __construct () {
			parent::__construct();
		}
				
		function insert_what_doesnt_exist ($user_id) {
				
			$user['user_id'] = $user_id;
			for ($user['pair_id'] = 0; $user['pair_id'] <= 1; $user['pair_id']++)
				for ($user['deal'] = 0; $user['deal'] <= 1; $user['deal']++) {
					$this->db->from ('users_has_retail_offers');
					$this->db->where ($user);
					if ( $this->db->count_all_results () == 0) 
						$this->db->insert ('users_has_retail_offers', $user);
				}
		}
					
		function get_best_rate ($pair_id, $deal) {
		
			$this->db->from ("users_retail_rate");
			if ($deal == 0) {
				// user sell
				$this->db->order_by ("sell", "asc");
				$this->db->where ('sell >', 0);
			}
			else {
				// user buy
				$this->db->order_by ("buy", "desc");
				$this->db->where ('buy >', 0);
			}
		
			$this->db->limit (1);
			$this->db->where ( array ('pair_id'=>$pair_id) );
			$query = $this->db->get ();
			
			if ($query->num_rows () == 0)
				return 0;			
						
			$rate = $query->row ();			
			
			if ($deal == 0)
				return $rate->sell;
			return $rate->buy;
		}
					
		function get_rate_exchange ($where, $deal) {
			
			$this->db->from ("users_retail_rate");
			$this->db->where ($where);
			if ($this->db->count_all_results () == 0) 
				return 0;
			
			$this->db->from ("users_retail_rate");
			$this->db->where ($where);
			$query = $this->db->get ();
			$rate = $query->row ();
			
			if ($deal == 0) 
				return $rate->sell;
			else
				return $rate->buy;
		}
					
		function get_client_details ($pips_difference) {
			// return amount and date			
			$result['amount'] = 0;
			$result['amount'] = rand (1000, 12000);
			$result['date'] = time () + 30 + rand () % 5 - 10;
			
			return $result;
		}
		
		function update_next_client ($user_id, $pair_id, $deal, $client) {
		
		  	$this->db->where ( array ('user_id'=>$user_id, 'pair_id'=>$pair_id, 'deal'=>$deal) );
	  		$this->db->update ('users_has_retail_offers', $client);
		}
					
		function generate_next_client ($user_id, $pair_id, $deal) {
			
			$rate = $this->get_rate_exchange (array ('user_id'=>$user_id, 'pair_id'=>$pair_id), $deal);
			
			if ($rate == 0) return ;		
							
			$best_rate = $this->get_best_rate ($pair_id, $deal);			
			$pips_difference = ($best_rate - $rate) * 10000;
			if ($pips_difference < 0) $pips_difference = -$pips_difference;
			
			$client = $this->get_client_details ($pips_difference);			
			if ($client['amount'] != 0) 
				$this->update_next_client ($user_id, $pair_id, $deal, $client);			
		}
		
		function get_total_day_amount ($user_id, $pair_id, $deal) {
			$this->db->from ("users_retail_amount");
			$this->db->where (array ('user_id' => $user_id, 'pair_id'=> $pair_id));			
			$query = $this->db->get ();
			if ($query->num_rows () == 0) {
				$this->db->insert ("users_retail_amount", array ('user_id' => $user_id, 'pair_id' => $pair_id));
				return 0;
			}
				
			$query = $query->row ();
			if ($deal == 0)
				return $query->total_day_sell;
			return $query->total_day_buy;
		}
		
		function update_total_day_amount ($user_id, $pair_id, $deal, $amount) {
			$this->db->where (array ('user_id' => $user_id, 'pair_id'=> $pair_id));
			$what = array ();
			if ($deal == 0) $what['total_day_sell'] = $amount;			
			else $what['total_day_buy'] = $amount;
			$this->db->update ("users_retail_amount", $what);
		}
		
		function make_deal ($deal) {
			// insert in deal table
			
			// update total_day_amount
			$new_amount = $this->get_total_day_amount ($deal['user_id'], $deal['pair_id'], $deal['deal']) + $deal['amount'];
			$this->update_total_day_amount ($deal['user_id'], $deal['pair_id'], $deal['deal'], $new_amount);
		}
		
		function check_next_client ($user_id) {
//			$this->insert_what_doesnt_exist ($user_id);
		
			$this->db->from ('users_has_retail_offers');
			$this->db->where (array('user_id' => $user_id));
			$query = $this->db->get ();
			$results = $query->result_array ();
			
			$clients = array ();
			
			foreach ($results as $item) {
				
				if ($item['date'] == 0) 
					$this->generate_next_client ($user_id, $item['pair_id'], $item['deal']);
				if ($item['date'] <= time ()) {

					$this->update_next_client ($user_id, $item['pair_id'], $item['deal'], array ('date'=>0, 'amount'=>'0'));													
					$clients[] = $item;
					$this->make_deal ($item);
					$this->generate_next_client ($user_id, $item['pair_id'], $item['deal']);
				}
			}
			
			return $clients;
		}
					
		function get_all_rate_exchange ($user_id) {
			$this->db->from ('users_retail_rate');
			$this->db->where (array('user_id'=>$user_id));
			$query = $this->db->get ();
			$results = $query->result_array ();
			
			$rate = array ();
			$rate[0]['sell'] = 0; $rate[1]['sell'] = 0;
			$rate[0]['buy'] = 0; $rate[1]['buy'] = 0;
			
			foreach ($results as $item) {
				$rate[ $item['pair_id'] ]['sell'] = $item['sell'];				
				$rate[ $item['pair_id'] ]['buy'] = $item['buy'];
			}
		
			return $rate;
		}
					
		function set_exchange_rate ($rate) {
			
			$this->db->where (array ('user_id' => $rate['user_id'], 'pair_id' => $rate['pair_id']));
			$this->db->from ('users_retail_rate');
			if ($this->db->count_all_results()) {				
				$this->db->where (array ('user_id' => $rate['user_id'], 'pair_id' => $rate['pair_id']));
				$this->db->update ('users_retail_rate', array ('sell' => $rate['sell'], 'buy' => $rate['buy']));
			}
			else 
				$this->db->insert ('users_retail_rate', $rate);			
		}
		
		function get_user_amount ($user_id) {
			$this->db->from ("users_retail_amount");
			$this->db->where (array('user_id' => $user_id));
			$query = $this->db->get ();
			$results = $query->result_array ();
			
			$amount = array ();			
			foreach ($results as $item) 
				$amount[$item['pair_id']] = $item;
			
			return $amount;
		}
	};	
	
?>
