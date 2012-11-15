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
			
			$this->apply_filter_date ();
			$this->apply_type_filter ();
			$this->apply_event_filter ();	
				
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
			
		function apply_filter_date () {
			$date_start_filter = strtotime ($this->input->get_post ("date_start_filter")); 
			$date_end_filter = strtotime ( $this->input->get_post ("date_end_filter"));
	
			if ($date_start_filter && $date_end_filter) {
				$this->db->where ('date >=', $date_start_filter);
				$this->db->where ('date <=', $date_end_filter);
			}
		}
		
		function apply_type_filter () {
			$type_filter = $this->input->get_post ("type_filter");
			if ($type_filter != 0)
				$this->db->where ('econlevels_id', $type_filter);
		}
		
		function apply_event_filter () {
			$event_filter = $this->input->get_post ("event_filter");
			if ($event_filter)
				$this->db->like ('econindicators.name', $event_filter);
		}
					
	};	
	
?>
