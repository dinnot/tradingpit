<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Econ extends CI_Controller {
	
	public function __construct () {
		
		parent::__construct ();		

		$this->load->helper('form');
    $this->load->database();
    $this->load->helper('url');
    $this->load->library('session');
    $this->load->model("Users_model");
		
		$this->load->model ("Econ_model");				
		$this->load->model ("News_model");    
	}
	
	public function index () {
		
		$data['econlevels'] = $this->Econ_model->get_econlevels ();
		$data['econforcasts'] = $this->Econ_model->get_econforcasts ();		
		
		$this->load->view ("econ/econ", $data); 
   }
   
   public function get_econforcasts () {
		
		$econforcasts = $this->Econ_model->get_econforcasts ();		

   	$this->output->set_content_type('application/jsonp');
		$this->output->set_output ( json_encode ( $econforcasts ) );
   }
};

