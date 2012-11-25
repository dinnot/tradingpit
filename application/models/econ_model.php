<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Econ_model extends CI_Model {

		function __construct () {
			parent::__construct();
		}
		
		function get_econforcasts () {
		
			$what = array ();
			$what[] = "econforcasts.prior";$what[] = "econforcasts.survey";
			$what[] = "econforcasts.id"; $what[] = "econforcasts.date"; 
			$what[] = "econforcasts.forecast"; $what[] = "econforcasts.actual";
			$what[] = "countries.name AS countries_name";
      $what[] = "econindicators.name AS econindicators_name";
      $what[] = "econlevels_id"; $what[] = "econindicators_id";
                                                                  
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

		// precalculata din ora in ora
		function get_last_ratio () {
			$current_time = time ();
			$this->db->from ("econforcasts");
			$this->db->where ("date <=", $current_time);
			$this->db->order_by ("date", "desc");
			$this->db->limit (1);
			$row = $this->db->get()->row();
			
			return array ('date' => $row->date, 'ratio' => $row->ratio);
		}

		function compute_econindicators_ratio () {
			$this->db->select (array ("*", "econforcasts.id as econforcasts_id"));
			$this->db->from ("econforcasts");
			$this->db->join ("econindicators", "econindicators.id = econforcasts.econindicators_id", "left");
			$query = $this->db->get ();
									
			$results = $query->result_array ();
			foreach ($results as $item) {

				$impact = (($item['actual'] - $item['survey']) / abs ($item['survey']) ) * $item['impact_power'];
				$this->db->where ('id', $item['econforcasts_id']);
				$this->db->update ("econforcasts", array ('ratio' => $impact));
			}			 			
		}
		
		// mai bine calculam si o tinem in baza de date cand introducem un nou forecast
		function get_prior (&$econforcasts) {
			$len = count ($econforcasts);
			for ($i = 0; $i < $len; $i++) {
				$this->db->select ("forecast");
				$this->db->from ("econforcasts");
				$this->db->where ("date <", $econforcasts[$i]['date']);
				$this->db->where ("econindicators_id", $econforcasts[$i]['econindicators_id']);
				$this->db->order_by ("date", "desc");
				$this->db->limit (1);
				$query = $this->db->get ();
				if ($query->num_rows () == 0)
					$econforcasts[$i]['prior'] = "-";
				else {
					$result = $query->row ();
					$econforcasts[$i]['prior'] = $result->forecast;
				}
			}
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

			if ($date_end_filter) 
				$date_end_filter = $date_end_filter + 24*3600;

			if (!$date_end_filter || !$date_start_filter) {
				$date_end_filter = time ();
				$date_start_filter = time () - 3 * 24 * 3600;
			}
			
			$date_end_filter = min ($date_end_filter, time ());
	
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
