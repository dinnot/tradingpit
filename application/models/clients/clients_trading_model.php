<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Clients_trading_model extends CI_Model {

		function __construct () {
			parent::__construct();
		}
				
		// actualizam in baza de date cand un user stabileste pretul pentru o oferta
		function set_quote ($offer_id, $user_id, $quote) {
			$this->db->from ("clients_offers")->where ('id', $offer_id);
			$result = $this->db->get ()->row ();

			if ($result->pending == 0)
				return 0;
				
			$this->db->where ( array ('offer_id' => $offer_id, 'user_id' => $user_id) );
			$this->db->update ('users_has_corporate_offers', array ('quote' => $quote, 'status' => '1' ) );
			return 1;
		}

		// actualizam user_balances | apelam din make_deal
    public function update_balances($user, $bank, $amount, $currency) {
      $this->db->set("amount", "amount + {$amount}", false)->where(array("users_id"=>$user, "currencies_id"=>$currency))->update("users_fx_positions");
      $this->db->set("amount", "amount + {$amount}", false)->where(array("banks_id"=>$user, "currencies_id"=>$currency))->update("banks_balances");
    }
		
		// inseram in fx_deals | apelam din make_deal
		function insert_fx_deal ($deal) {
			$this->db->insert ("fx_deals", $deal);
		}
		
		// oferta acceptata, actualizam si inseram unde este nevoie
		// update in insert_fx_deal, update_balances
		function make_deal ($offer) {
			$bank = $this->Clients_model->get_user_bank ($offer['user_id']);
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

		// stabilim userii castigatori pentru corporate-offer
		// transmitem prin referinta sa actualizam statusu pentru js
		function set_result_corporate (&$offer) {		
			$this->db->where('id', $offer['offer_id'])->update ("clients_offers", array ("pending" => 0));		

			$this->db->select ("*");
			$this->db->from ("users_has_corporate_offers");			
			$this->db->order_by ("quote", "desc");
			$this->db->join ("clients_offers", "users_has_corporate_offers.offer_id = clients_offers.id", "left");									
			$this->db->where (array ('offer_id'=>$offer['offer_id']));
			
			$query = $this->db->get ();
			$results = $query->result_array ();

			// prost
			$best_offer['price'] = $results[0]['quote'];			
			
			foreach ($results as $item) {
				if ($item['quote'] == $best_offer['price'] && $best_offer['price'] != 0) {
					$offer['status'] = 2; // make_deal
					$this->make_deal ($item);
				}
				else 
					$offer['status'] = 3; // reject								
				// update status in database
				$this->db->where ( array ('user_id'=>$item['user_id'], 'offer_id'=>$offer['offer_id']) );
				$this->db->update ('users_has_corporate_offers', array ('status'=>$offer['status']));			
			}
		}

	};	
	
?>
