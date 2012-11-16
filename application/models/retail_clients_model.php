<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Retail_clients_model extends CI_Model {

		function __construct () {
			parent::__construct();
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
		
		function make_deal ($offer, $price) {
			print "plm";
			$new_amount = $this->get_total_day_amount ($offer->user_id, $offer->pair_id, $offer->deal) + $offer->amount;
			$this->update_total_day_amount ($offer->user_id, $offer->pair_id, $offer->deal, $new_amount);
		
			$bank = $this->get_user_bank ($offer->user_id);
			if ($offer->deal == 1) $offer->amount = -$offer->amount;
		
			$deal = array ();
			$deal['user_id'] = $offer->user_id;
			$deal['ccy_pair'] = $offer->pair_id;
			$deal['amount_base_ccy'] = $offer->amount;
			$deal['price'] = $price;
			$deal['counter_party'] = 0;
			$deal['value_date'] = $offer->date;
			$deal['trade_date'] = $offer->date;
			
			print_r ($deal);
			$bank = $this->get_user_bank ($offer->user_id);		
			$this->update_balances ($offer->user_id, $bank, $offer->amount, $offer->pair_id);				
			$this->insert_fx_deal ($deal);
		
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
			
			if ($deal == 1) 
				return $rate->sell;
			else
				return $rate->buy;
		}
		
		# //// facut tabelul dupa ////					
		function get_client_details ($pips_difference) {
			// return amount and date			
			$result['amount'] = 0;
			$result['amount'] = rand (1000, 12000);
			$result['date'] = time () + 60 + rand () % 5 - 10;
			
			return $result;
		}
		
		function update_next_client ($user_id, $pair_id, $deal, $client) {
		
		  	$this->db->where ( array ('user_id'=>$user_id, 'pair_id'=>$pair_id, 'deal'=>$deal) );
	  		$this->db->update ('users_has_retail_offers', $client);
		}
					
		function generate_next_client ($user_id, $pair_id, $deal, $rate) {
						
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
				$this->db->insert ("users_retail_amount", array ('user_id' => $user_id, 'pair_id' => $pair_id,
																	'sell' => 0, 'buy' => 0));
				return 0;
			}
				
			$query = $query->row ();
			if ($deal == 0)
				return $query->sell;
			return $query->buy;
		}
		
		function update_total_day_amount ($user_id, $pair_id, $deal, $amount) {
			$this->db->where (array ('user_id' => $user_id, 'pair_id'=> $pair_id));
			$what = array ();
			if ($deal == 1) $what['sell'] = $amount;			
			else $what['buy'] = $amount;
			$this->db->update ("users_retail_amount", $what);
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
							$this->make_deal ($result, $price);
							$this->generate_next_client ($user_id, $pair_id, $deal,$price);
						}
						
						if ($result->date == 0)
							$this->generate_next_client ($user_id, $pair_id, $deal,$price);
					}					
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
			$rate[1]['sell'] = 0; $rate[2]['sell'] = 0;
			$rate[1]['buy'] = 0; $rate[2]['buy'] = 0;
			
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
			
			$amount = array ();
			for ($pair_id = 1; $pair_id <= 2; $pair_id++) {
				$this->db->from ("users_retail_amount");
				$this->db->where (array('user_id' => $user_id, 'pair_id' => $pair_id));
				$query = $this->db->get ();
				if ($query->num_rows == 0) {
					$this->db->insert ("users_retail_amount", array('user_id' => $user_id, 'pair_id' => $pair_id,
																			'sell' => 0, 'buy' => 0));
					$amount[$pair_id]['sell'] = 0;					
					$amount[$pair_id]['buy'] = 0;
				}
				else {
					$result = $query->row ();
					$amount[$pair_id]['sell'] = $result->sell;					
					$amount[$pair_id]['buy'] = $result->buy;
				}
			}
						
			return $amount;
		}
	};	
	
?>
