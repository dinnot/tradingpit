<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Econ extends CI_Controller {
	
	public function __construct () {
		
		parent::__construct ();		

		$this->load->database();
		$this->load->helper('form');
	}
	
	public function index () {
		
		$this->load->model ("econ_model");				
		$data['econlevels'] = $this->Econ_model->get_econlevels ();
		$data['econforcasts'] = $this->Econ_model->get_econforcasts ();		
		
		$this->EconModel->apply_filter_name ($data['econforcasts'], $this->input->get_post('filter_name'));
		
		$filter_date['start'] = $this->input->get_post ("filter_date_start"); 
		$filter_date['end'] = $this->input->get_post ("filter_date_end");
		$this->Econ_model->apply_filter_date ($data['econforcasts'], $filter_date);
				
		$this->Econ_model->apply_filter_type ($data['econforcasts'], $this->input->get_post('filter_type'));

		$this->load->view ("econ", $data); 
   }
};

