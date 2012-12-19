<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Clients_trading_model extends CI_Model {

		function __construct () {
			parent::__construct();
		}

		// luam banca userului		
		public function get_user_bank ($user_id) {
			$this->db->from ("users");
			$this->db->where ("users.id", $user_id);
			$this->db->join ("jobs", "users.id = jobs.id", "left");			
			$result = $this->db->get ()->row ();
			
			return $result->banks_id;
		}
				
		// actualizam in baza de date cand un user stabileste pretul pentru o oferta de corporate
		function set_quote ($offer_id, $user_id, $quote) {
		
			$this->db->from ("clients_offers")->where ('id', $offer_id);
			$result = $this->db->get ()->row ();

			if ($result->pending == 0)
				return 0;
				
			$this->db->where ( array ('offer_id' => $offer_id, 'user_id' => $user_id) );
			$this->db->update ('users_has_corporate_offers', array ('quote' => $quote, 'status' => '1' ) );
			return 1;
		}

		// actualizam in db pretul pentru ofertele de retail
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


		// actualizam user_balances | apelam din make_deal
    public function update_balances($user, $bank, $amount, $currency) {
      $this->db->set("amount", "amount + {$amount}", false)->where(array("users_id"=>$user, "currencies_id"=>$currency))->update("users_fx_positions");
      $this->db->set("amount", "amount + {$amount}", false)->where(array("banks_id"=>$user, "currencies_id"=>$currency))->update("banks_balances");
    }
		
		// inseram in fx_deals | apelam din make_deal
		function insert_fx_deal ($deal) {
			$deal['type'] = 1;
			$this->db->insert ("deals", $deal);
		}
		
		// oferta acceptata, actualizam si inseram unde este nevoie
		// update in insert_fx_deal, update_balances
		function make_corporate_deal ($offer) {
			$bank = $this->get_user_bank ($offer['user_id']);
			if ($offer['deal'] != 1) $offer['amount'] = -$offer['amount'];
		
			$deal = 	array ();
			$deal['user_id'] = $offer['user_id'];
			$deal['ccy_pair'] = $offer['currency'];
			$deal['amount_base_ccy'] = $offer['amount'];
			$deal['price'] = $offer['quote'];
			$deal['counter_party'] = - ($offer['client_id']);
			$deal['value_date'] = time ();
			$deal['trade_date'] = time ();
			$deal['period'] = 1;
		
			if ($offer['market'] == "FX") {
				$this->trading_model->updateBalances ($offer['user_id'], $bank, $offer['amount'], $offer['currency'],$offer['quote']);				
				$this->insert_fx_deal ($deal);
			}
		}

		// oferta acceptata, actualizam si inseram unde este nevoie
		// update in insert_fx_deal, update_balances		
		function make_retail_deal ($offer, $price) {
			
			$new_amount = $this->get_total_day_amount ($offer->user_id, $offer->pair_id, $offer->deal) + $offer->amount;
			$this->update_total_day_amount ($offer->user_id, $offer->pair_id, $offer->deal, $new_amount);
		
			$bank = $this->get_user_bank ($offer->user_id);
			if ($offer->deal != 1) $offer->amount = -$offer->amount;
		
			$deal = array ();
			$deal['user_id'] = $offer->user_id;
			$deal['ccy_pair'] = $offer->pair_id;
			$deal['amount_base_ccy'] = $offer->amount;
			$deal['price'] = $price;
			$deal['counter_party'] = 0;
			$deal['value_date'] = $offer->date;
			$deal['trade_date'] = $offer->date;
			$deal['period'] = 1;
			
			$bank = $this->get_user_bank ($offer->user_id);		
			$this->trading_model->updateBalances ($offer->user_id, $bank, $offer->amount, $offer->pair_id, $price);				
			$this->insert_fx_deal ($deal);		
		}

		// stabilim userii castigatori pentru corporate-offer
		// transmitem prin referinta sa actualizam statusu pentru js
		function set_result_corporate (&$offer) {		
			$this->db->where('id', $offer['offer_id'])->update ("clients_offers", array ("pending" => 0));		

			$this->db->select ("*");
			$this->db->from ("users_has_corporate_offers");			
			$this->db->join ("clients_offers", "users_has_corporate_offers.offer_id = clients_offers.id", "left");									
			$this->db->where (array ('offer_id'=>$offer['offer_id']));
			
			$query = $this->db->get ();
			$results = $query->result_array ();

			$best_offer = $this->corporate_clients_model->get_best_offer ($offer);			
			if ($best_offer['num'] != 0) $offer['amount'] = $offer['amount'] / $best_offer['num'];
			foreach ($results as $item) {
				$item['amount'] = $offer['amount'];
				if ($item['quote'] == $best_offer['quote'] && $best_offer['quote'] != 0) {
					$offer['status'] = 2; // make_deal
					$this->make_corporate_deal ($item);
				}
				else 
					$offer['status'] = 3; // reject								
				// update status in database
				$this->db->where ( array ('user_id'=>$item['user_id'], 'offer_id'=>$offer['offer_id']) );
				$this->db->update ('users_has_corporate_offers', array ('status'=>$offer['status']));			
			}
		}
	
		public function get_pairs() {
			$query = $this->db->select("cp.id, c1.shortname as curr1, c2.shortname as curr2")->from("currency_pairs cp")->join("currencies c1", "cp.currency0 = c1.id", "left")->join("currencies c2", "cp.currency1 = c2.id", "left")->get();
			$ret = array();
			foreach($query->result() as $row) 
					$ret[$row->id] = $row->curr1."/".$row->curr2;
			return $ret;
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
			if ($deal == 1)
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
		
		function get_user_deals ($user_id) {
			$this->db->from ("deals");
			$this->db->where ('user_id', $user_id);			
			$this->db->where ('counter_party <=', 0);
			$this->db->order_by ("trade_date", "desc");
			$this->db->limit (10);
			$query = $this->db->get ();
			
			return $query->result_array ();
		}			
	};	
	
?>
