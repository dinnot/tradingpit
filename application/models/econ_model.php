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
			$this->apply_filter_type ();
			$this->apply_filter_name ();	
				
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
			$filter_date_start = $this->input->get_post ("filter_date_start"); 
			$filter_date_end = $this->input->get_post ("filter_date_end");
			if ($filter_date_start && $filter_date_end) {
				$this->db->where ('date >', $filter_date_start);
				$this->db->where ('date <', $filter_date_end);
			}
		}
		
		function apply_filter_type () {
			$filter_type = $this->input->get_post ("filter_type");
			if ($filter_type != 0)
				$this->db->where ('econlevels_id', $filter_type);
		}
		
		function apply_filter_name () {
			$filter_name = $this->input->get_post ("filter_name");
			if ($filter_name)
				$this->db->like ('econindicators.name', $filter_name);
		}
					
	};	
	
?>
