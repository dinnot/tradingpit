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
			return '0.0000';
		$row = $query->row ();
		return $row->price;
	}
	
	function get_best_amount ($price, $pairs_id, $deal, $users_id) {
		$this->db->select ('amount');
		$this->db->where ('users_id !=', $users_id);
		$this->db->from ('eb_prices')->where (array ('pairs_id'=>$pairs_id, 'deal'=>$deal, 'price'=>$price));
		$results = $this->db->get ()->result_array ();
		
		$amount = 0;
		foreach ($results as $row) 
			$amount+= $row['amount'];
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
			$best[$pairs_id]['buy']['bf'] = $this->get_bf ($price);			
			$best[$pairs_id]['buy']['pips'] = $this->get_pips ($price);			
			$best[$pairs_id]['buy']['amount'] = $this->get_best_amount ($price, $pairs_id, 'buy', $users_id);

			$price = $this->get_best_price ('asc', $pairs_id, 'sell', $users_id);									
			$best[$pairs_id]['sell']['bf'] = $this->get_bf ($price);				
			$best[$pairs_id]['sell']['pips'] = $this->get_pips ($price);
			$best[$pairs_id]['sell']['amount'] = $this->get_best_amount ($price, $pairs_id, 'sell', $users_id);
		}
		
		return $best;
	}
	
	function get_user_prices ($users_id) {	
		$this->db->from ('eb_prices')->where ('users_id', $users_id);
		return $this->db->get ()->result_array ();
	}
	
	function make_deal ($deal ,$users_id) {
		
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
			}
			else {
				$this->db->where ('id', $row['id']);
				$this->db->update ('eb_prices', array ('used'=>'0'));
			}
		}						
	}
};	
	
?>
