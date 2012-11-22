<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Retail_clients_model extends CI_Model {

		function __construct () {
			parent::__construct();
		}		

		function check_next_client ($user_id) {
						
			$clients = array ();			
	
			for ($deal = 1; $deal <= 2; $deal++) {
				for ($pair_id = 1; $pair_id <= 2; $pair_id++) {					
					$price = $this->get_rate_exchange (array ('user_id'=>$user_id, 'pair_id'=>$pair_id), $deal);
				
					$this->db->from ('users_has_retail_offers');
					$this->db->where ( array ('user_id' => $user_id, 'pair_id' => $pair_id, 'deal' => $deal) );
					$query = $this->db->get ();
					if ($query->num_rows == 0) {
						$this->db->insert ("users_has_retail_offers", array ('user_id' => $user_id, 'pair_id' => $pair_id, 'deal' => $deal,
																															'date' => 0, 'amount' => 0));

						$this->generate_next_client ($user_id, $pair_id, $deal,$price);
					}
					else {
					
						$result = $query->row ();
					
						if ($result->date != 0 && $result->date <= time ())	{
							$this->update_next_client ($user_id, $result->pair_id, $result->deal, array ('date' => 0, 'amount' =>'0'));													
							
							$clients[] = $result;
							$this->clients_trading_model->make_retail_deal ($result, $price);
							$this->generate_next_client ($user_id, $pair_id, $deal,$price);
						}
						
						if ($result->date == 0) {
							
							$this->generate_next_client ($user_id, $pair_id, $deal,$price);
						}
					}					
				}
			}
			
			return $clients;
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
			
			if ($deal == 1) 
				return $rate->sell;
			else
				return $rate->buy;
		}

		function get_best_retail_price ($pair_id, $deal) {
		
			$this->db->from ("users_retail_rate");
			if ($deal == 1) {
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
			if ($deal == 1)
				return $rate->sell;
			return $rate->buy;
		}
		
		function get_client_details ($pips_difference) {
			if ($pips_difference >= 800) {
				$result['amount'] = 0;
				return $result;
			}
		
			if ($pips_difference == 0)
				$pips_difference = 1;
		
			$this->db->from ("retail_limits");
			$this->db->where ("pips_out < ", $pips_difference + 25);
			$this->db->order_by ("pips_out", "desc");
			$this->db->limit (1);
			$query = $this->db->get ();
			$client = $query->row();
			
			$result['amount'] = rand () % ($client->max_amount - 1000) + 1000;
			$result['date'] = time () + $client->secs + rand () % 11 - 5;
			
			return $result;
		}
		
		function update_next_client ($user_id, $pair_id, $deal, $client) {
		
		  	$this->db->where ( array ('user_id'=>$user_id, 'pair_id'=>$pair_id, 'deal'=>$deal) );
	  		$this->db->update ('users_has_retail_offers', $client);
		}
					
		function generate_next_client ($user_id, $pair_id, $deal, $rate) {
											
			if ($rate == 0) return ;		
							
			$best_rate = $this->get_best_retail_price ($pair_id, $deal);						
			$pips_difference = ($best_rate - $rate) * 10000;
			if ($pips_difference < 0) $pips_difference = -$pips_difference;
			
			$client = $this->get_client_details ($pips_difference);			
			
			if ($client['amount'] != 0) 
				$this->update_next_client ($user_id, $pair_id, $deal, $client);			
		}
							
		function get_bf ($price) {
			$bf = $price * 10; $bf = (int) $bf; $bf/= 10;
			return number_format($bf, 1, '.', '');
		}
		
		function get_pips ($price) {
			$bf = $price * 10; $bf = (int) $bf; $bf/= 10;
			$pips = $price - $bf;
			$pips = $pips * 10000;
			$pips = (int) $pips;
			return $pips;
		}
							
		function get_all_rate_exchange ($user_id) {
			$this->db->from ('users_retail_rate');
			$this->db->where (array('user_id'=>$user_id));
			$query = $this->db->get ();
			$results = $query->result_array ();
			
			$rate = array ();
			$rate[1]['sell_bf'] = '0.0'; $rate[2]['sell_bf'] = '0.0';
			$rate[1]['buy_bf'] = '0.0'; $rate[2]['buy_bf'] = '0.0';
			
			$rate[1]['sell_pips'] = 0; $rate[2]['sell_pips'] = 0;
			$rate[1]['buy_pips'] = 0; $rate[2]['buy_pips'] = 0;
			
			foreach ($results as $item) {
				$rate[ $item['pair_id'] ]['sell_bf'] = $this->get_bf ($item['sell']);				
				$rate[ $item['pair_id'] ]['sell_pips'] = $this->get_pips ($item['sell']);
				$rate[ $item['pair_id'] ]['buy_bf'] = $this->get_bf ($item['buy']);				
				$rate[ $item['pair_id'] ]['buy_pips'] = $this->get_pips ($item['buy']);
			}
		
			return $rate;
		}	
	};	
	
?>
