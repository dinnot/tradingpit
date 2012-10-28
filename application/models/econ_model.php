<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Econ_model extends CI_Model {

		function __construct () {
			parent::__construct();
		}
		
		function get_econforcasts () {
		
			$what = array ();
			$what[] = "econforcasts.id"; $what[] = "econforcasts.date"; 
			$what[] = "econforcasts.forecast"; $what[] = "econforcasts.actual";
			$what[] = "countries.name AS countries_name";
      $what[] = "econindicators.name AS econindicators_name";
      $what[] = "econlevels_id";
                                                                  
			$this->db->select ($what);
			$this->db->from ("econforcasts");
			$this->db->join("countries", "econforcasts.countries_id = countries.id", "left");
			$this->db->join("econindicators", "econforcasts.econindicators_id = econindicators.id", "left");
			$query = $this->db->get ();
			
			$econforcasts = $query->result_array ();			

			return $econforcasts;
		}
		
		function get_econlevels () {
			$this->db->select ('id, name');			
			$this->db->from ('econlevels');
			$query = $this->db->get ();
			$econlevels = $query->result_array ();
			
			return $econlevels;
		}
				
		function apply_filter_name (&$econforcasts, $filter_name) {
			if ($filter_name == '') 
				return ;
				
			$new_econforcast = array ();
			foreach ($econforcasts as $item) {
				if (stripos ($item['econindicators_name'], $filter_name) !== FALSE)
					$new_econforcast[] = $item;
			}
						
			$econforcasts = $new_econforcast; 
		}
		
		function apply_filter_date (&$econforcasts, $filter_date) {
			if ($filter_date['start'] == '' || $filter_date['end'] == '') 
				return ;
				
			$new_econforcast = array ();
			foreach ($econforcasts as $item) {
				if ($filter_date['start'] <= $item['date'] && $item['date'] <= $filter_date['end'])
					$new_econforcast[] = $item;
			}
									
			$econforcasts = $new_econforcast; 
		}
		
		function apply_filter_type (&$econforcasts, $filter_type) {
		
			if ($filter_type == 0)
				return ;
				
			$new_econforcast = array ();
			foreach ($econforcasts as $item) 
				if ($item['econlevels_id'] == $filter_type)
					$new_econforcast[] = $item;
	
			$econforcasts = $new_econforcast; 
		}
	};	
	
?>
