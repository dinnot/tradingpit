<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Make_econ extends CI_Controller {

	public function __construct () {
		
		parent::__construct ();
				
    $this->load->database();
	}
	
	function get_econlevels () {
		$this->db->from ("econlevels");
		$query = $this->db->get ();
		$results = $query->result_array ();
		
		$levels = array ();
		foreach ($results as $item) 
			$levels[ $item['name'] ] = $item['id'];
		
		return $levels;
	}

	function get_units () {
		$this->db->from ("units");
		$query = $this->db->get ();
		$results = $query->result_array ();
		
		$levels = array ();
		foreach ($results as $item) 
			$levels[ $item['name'] ] = $item['id'];
		
		return $levels;
	}
	
	function get_countries () {
		$this->db->from ("countries");
		$query = $this->db->get ();
		$results = $query->result_array ();
		
		$levels = array ();
		foreach ($results as $item) 
			$levels[ $item['short_name'] ] = $item['id'];
		
		return $levels;
	}

	function get_econindicators () {
		$this->db->from ("econindicators");
		$query = $this->db->get ();
		$results = $query->result_array ();
		
		$levels = array ();
		foreach ($results as $item) 
			$levels[ $item['name'] ] = $item['id'];
		
		return $levels;
	}


	function get_ids ($what) {
		$this->db->from ($what);
		$query = $this->db->get ();
		$results = $query->result_array ();
		
		$ids = array ();
		foreach ($results as $item) 
			array_push ($ids, $item['id']);
				
		return $ids;
	}
		
	public function add_contries_indicators () {
		return ;
		$countries = $this->get_ids ("countries");
		$indicators = $this->get_ids ("econindicators");
		foreach ($countries as $country) 
			foreach ($indicators as $ind) 
				$this->db->insert ("countries_has_econindicators", array ('countries_id'=>$country,'econindicators_id'=>$ind));	
	}
		
	public function add_econ () {
	
		return ;
  	$levels = $this->get_econlevels ();
  	print_r ($levels);
  	print '<hr />';
	
		$file = "/var/www/tradingpit/application/controllers/make_econ/econ_ind";
		$handle = fopen ($file, "r");
		if ($handle) {
 	  	while (($buffer = fgets($handle, 4096)) !== false) {
      	$econ = explode (",", $buffer);
      	$econ [1] = rtrim ($econ[1]);
      	$econ [0] = rtrim ($econ[0]);
      	$econ [1] = ltrim ($econ[1]);
      	$econ [0] = ltrim ($econ[0]);
      	$econ[1] = $levels[$econ[1]];
      	
      	$this->db->insert ("econindicators", array ('name'=>$econ[0], 'formula'=>'formula1', 'econcategories_id'=>'1', 'econlevels_id'=>$econ[1]));
      	
    	}
    	if (!feof($handle)) 
        echo "Error: unexpected fgets() fail\n";
    	fclose($handle);
		}
	}

	public function update_units () {
		return ;	
  	$units = $this->get_units ();
  	print_r ($units);
  	print '<hr />';
	
		$file = "/var/www/tradingpit/application/controllers/make_econ/ind_units";
		$handle = fopen ($file, "r");
		if ($handle) {
 	  	while (($buffer = fgets($handle, 4096)) !== false) {
      	$econ = explode (",", $buffer);
      	$econ [1] = rtrim ($econ[1]);
      	$econ [0] = rtrim ($econ[0]);
      	$econ [1] = ltrim ($econ[1]);
      	$econ [0] = ltrim ($econ[0]);
      	
      	$this->db->where (array ('name'=>$econ[0]));
      	$this->db->update ("econindicators", array ('units_id'=>$units[$econ[1]]));      	
    	}
    	if (!feof($handle)) 
        echo "Error: unexpected fgets() fail\n";
    	fclose($handle);
		}
	}

	public function add_econ_indicator ($what) {
		$this->db->insert ("econindicators",
			array ('name'=>$what['econindicators'], 'impact_power'=>$what['impact_power'], 'market_impact'=>$what['market_impact'], 'units_id'=>$what['units_id'], 'econlevels_id' => $what['econlevels_id'], 'econcategories_id' => 1
			)
		);
		
		return $this->db->insert_id ();
	}

	public function remove_ws (&$array) {
		foreach ($array as &$item) {
			$item = rtrim ($item);
			$item = ltrim ($item);
		}
	}
	
	public function verify_countries_has_econindicators ($countries_id, $econcategories_id) {
		$this->db->from ("countries_has_econindicators");
		$this->db->where ( array ('countries_id'=>$countries_id, 'econindicators_id'=>$econcategories_id) );
		$query = $this->db->get ();
		if ($query->num_rows == 0)
			$this->db->insert ("countries_has_econindicators", array ('countries_id'=>$countries_id, 'econindicators_id'=>$econcategories_id) );
	}
	
	public function index () {
		
		$this->db->empty_table ("econforcasts");

		$countries = $this->get_countries ();
		$econlevels = $this->get_econlevels ();
		$units = $this->get_units ();
		$econindicators = $this->get_econindicators ();

		print_r ($econindicators);

		$file = "/var/www/tradingpit/application/controllers/make_econ/beta";
		$handle = fopen ($file, "r");
		$start_data = strtotime('15 nov');
		if ($handle) {
 	  	while (($buffer = fgets($handle, 4096)) !== false) {
      	$econ = explode (",", $buffer);
    		$this->remove_ws ($econ);  	
				
				print_r ($econ);
				
				$current_data = $start_data + ($econ[0] - 1) * 24 * 3600;
				$data = date ('Y-M-d', $current_data);
				$data = $data . ' ' . $econ['1'];
				$time = strtotime ($data);				

				$pos = 2;			
				for ($country = 1; $country <= 3; $country++) {
					$what = array ();
					$what['date'] = $time;
					$what['countries_id'] = $countries[$econ[$pos]];	$pos++;
					$what['econindicators'] = $econ[$pos]; $pos++;
					$what['econlevels_id'] = $econlevels[$econ[$pos]]; $pos++;
					$what['units_id'] = $units[$econ[$pos]]; $pos++;
					$what['market_impact'] = $econ[$pos]; $pos++; $pos++;
					$what['impact_power'] = $econ[$pos]; $pos++;
					if (isset ($econindicators[$what['econindicators']])) 
						$what['econindicators_id'] = $econindicators[$what['econindicators']];
					else {
						$econindicators[$what['econindicators']] = $this->add_econ_indicator ($what);
						$what['econindicators_id'] = $econindicators[$what['econindicators']];
					}
					$what['actual'] = $econ[$pos]; $pos++;
					$what['survey'] = $econ[$pos]; $pos++;
					$what['prior'] = $econ[$pos]; $pos++;
	
					unset ($what['econindicators']);
					unset ($what['econlevels_id']);
					unset ($what['units_id']);
					unset ($what['market_impact']);
					unset ($what['impact_power']);
					
					$this->verify_countries_has_econindicators ($what['countries_id'], $what['econindicators_id']);
					
					print_r ($what);
					print '<hr>';									
					$this->db->insert ("econforcasts", $what);
				}	
				
				print '<hr />';
    	}
    	if (!feof($handle)) 
        echo "Error: unexpected fgets() fail\n";
    	fclose($handle);
		}
		
  }

};

