<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Econ extends CI_Controller {
	
	public function __construct () {
		
		parent::__construct ();		

		$this->load->database();
		$this->load->helper('form');
	}
	
	public function index () {
		
		$this->load->model ("Econ_model");				
		$this->load->model ("News_model");
		$data['econlevels'] = $this->Econ_model->get_econlevels ();
		$data['econforcasts'] = $this->Econ_model->get_econforcasts ();		
		
		$this->load->view ("econ", $data); 
   }
};

