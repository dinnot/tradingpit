<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	
// electronic broker model class
class Ebroker_model extends CI_Model {

	function __construct () {
		parent::__construct();
	}
	
	function add_price ($users_id, $price) {
		if ($price <= 0)
			return 0;
		$this->db->where ('users_id', $users_id);
		$price['users_id'] = $users_id;
//		$price['banks_id'] = $this->get_user_bank ($users);
		$this->db->insert ('eb_prices', $price);
	}

	function remove_user_price ($users_id, $eb_prices) {
		$this->db->where (array ('users_id'=>$users_id, 'id'=>$eb_prices));
		$this->db->delete ('eb_prices');
	}
	
	function cancel_user_prices ($users_id, $pairs_id) {
		$this->db->where ('users_id', $users_id);
		$this->db->where ('pairs_id', $pairs_id);
		$this->db->delete ('eb_prices');
	}
	
	function hold_user_prices ($users_id) {
		$this->db->where ('users_id',$users_id);
		$this->db->update ('eb_prices', array ('hold'=>1));
	}
	
	function get_best_price ($order, $pairs_id, $deal, $users_id) {
		$this->db->from ('eb_prices');
		$this->db->where (array('pairs_id' => $pairs_id, 'deal'=>$deal));
		$this->db->where ('users_id !=', $users_id);

		$this->db->order_by ('price', $order);
		$this->db->limit (1);
		$query = $this->db->get ();
		if ($query->num_rows () == 0)
			$result['available'] = '0.0000';
		else 
			$result['available'] = $query->row ()->price;
		
		$this->db->from ('eb_prices');
		$this->db->where (array('pairs_id' => $pairs_id, 'deal'=>$deal));
		$this->db->order_by ('price', $order);
		$this->db->limit (1);
		$query = $this->db->get ();
//		print $query->num_rows ();
		if ($query->num_rows () == 0)
			$result['all'] = '0.0000';
		else
			$result['all'] = $query->row ()->price;
		
		return $result;
	}
	
	function get_best_amount ($price, $pairs_id, $deal, $users_id) {
		$this->db->select ('amount');
		$this->db->where ('users_id !=', $users_id);
		$this->db->from ('eb_prices')->where (array ('pairs_id'=>$pairs_id, 'deal'=>$deal, 'price'=>$price));
		$results = $this->db->get ()->result_array ();
		
		$amount = 0;
		foreach ($results as $row) {
			$amount+= $row['amount'];
			if ($amount >= 100) 
				break;
		}
		
		if ($amount >= 10)
			$amount = 'X';
			
		if ($amount >= 100)
			$amount = 'C';
		
		return $amount;		
	}
	
	function get_bf ($price) {
		$price = substr ($price, 0);
		$price = substr ($price, 0, strlen ($price) - 2);		
		return $price;
	}
	
	function get_pips ($price) {
		$price = substr ($price, 0);
		$price = substr ($price, strlen ($price) - 2);		
		return $price;
	}
	
	function get_best_prices ($users_id) {
		$best = array ();
		for ($pairs_id = 1; $pairs_id <= 2; $pairs_id++) {
			$price = $this->get_best_price ('desc', $pairs_id, 'buy', $users_id);
			$best[$pairs_id]['all']['buy'] = $price['all'];
			$best[$pairs_id]['available']['buy']['bf'] = $this->get_bf ($price['available']);			
			$best[$pairs_id]['available']['buy']['pips'] = $this->get_pips ($price['available']);			
			$best[$pairs_id]['available']['buy']['amount'] = $this->get_best_amount ($price['available'], $pairs_id, 'buy', $users_id);

			$price = $this->get_best_price ('asc', $pairs_id, 'sell', $users_id);												
			$best[$pairs_id]['all']['sell'] = $price['all'];						
			$best[$pairs_id]['available']['sell']['bf'] = $this->get_bf ($price['available']);				
			$best[$pairs_id]['available']['sell']['pips'] = $this->get_pips ($price['available']);
			$best[$pairs_id]['available']['sell']['amount'] = $this->get_best_amount ($price['available'], $pairs_id, 'sell', $users_id);
		}
		
		return $best;
	}
	
	function get_user_prices ($users_id) {	
		$this->db->from ('eb_prices')->where ('users_id', $users_id);
		//$this->db->order_by ('pairs_id', 'desc');
		
		return $this->db->get ()->result_array ();
	}
	
	function get_user_deals ($users_id) {	
		$this->db->from ('deals')->where ('user_id', $users_id);
		$this->db->where ('is_eb', 1);
		$this->db->order_by ('id', 'desc');
		$this->db->limit (6);	
		return $this->db->get ()->result_array ();
	}
	
	public function get_user_bank ($user_id) {
		$this->db->from ("users");
		$this->db->where ("users.id", $user_id);
		$this->db->join ("jobs", "users.id = jobs.id", "left");			
		$result = $this->db->get ()->row ();
		
		return $result->banks_id;
	}

	
	function add_deal ($deal, $users_id, $bank) {

		$add['ccy_pair'] = $deal['pairs_id'];
		$add['amount_base_ccy'] = $deal['amount'] * 1000000;
		if ($deal['deal'] == 'buy') $add['amount_base_ccy'] = -$add['amount_base_ccy'];
		$add['price'] = $deal['price'];
		$add['counter_party'] = $deal['users_id'];
		$add['user_id'] = $users_id;
		$add['value_date'] = time ();
		$add['trade_date'] = $add['value_date'];
		$add['type'] = 1;
		$add['period'] = 1;
		$add['is_eb'] = 1;
				
		$this->db->insert ("deals", $add);
		$this->trading_model->updateBalances ($add['user_id'], $bank, $add['amount_base_ccy'], $add['ccy_pair'], $add['price']);		
		
		$bank = $this->get_user_bank ($add['counter_party']);
		$this->trading_model->updateBalances ($add['counter_party'], $bank, -$add['amount_base_ccy'], $add['ccy_pair'], $add['price']);		
	}
	
	function make_deal ($deal ,$users_id) {
		$bank = $this->get_user_bank ($users_id);
		
		$this->db->from ('eb_prices');
		$this->db->where (array ('deal'=>$deal['deal'], 'price'=>$deal['price'], 'pairs_id'=>$deal['pairs_id'] ));
		$this->db->where ('used', 0);
		$this->db->where ('users_id !=', $users_id);
		$results = $this->db->get ()->result_array ();
		
		foreach ($results as $row) {			
			$this->db->where ('id', $row['id']);
			$this->db->where ('used', 0);
			$this->db->update ('eb_prices', array ('used'=>'1'));
			if ($this->db->affected_rows() > 0)  {
				if ($deal['amount'] >= $row['amount']) {
					$deal['amount']-= $row['amount'];
					$this->db->where ('id', $row['id']);
					$this->db->delete ('eb_prices');
					$amount = $row['amount'];
				}			
				else {
					$this->db->where ('id', $row['id']);
					$this->db->update ('eb_prices', array ('used'=>0, 'amount'=>($row['amount'] - $deal['amount'])));
					$amount = $deal['amount'];
				}
				
				// transfer amount
				$row['amount'] = $amount;
				$this->add_deal ($row, $users_id, $bank);
			}
			else {
				$this->db->where ('id', $row['id']);
				$this->db->update ('eb_prices', array ('used'=>'0'));
			}
		}						
	}
};	
	
?>
