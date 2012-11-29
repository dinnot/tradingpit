<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pull extends CI_Controller {
		
	public function __construct () {
		
		parent::__construct ();		

    $this->load->database();
    $this->load->helper('url');
    $this->load->library('session');
    $this->load->model("Users_model");
    $this->module_name = "clients";
    $valid = false;
    if($this->session->userdata("key")) {
        $key = $this->session->userdata("key");
        $auth = $this->Users_model->getAuth($key, $this->module_name);
        $this->user = $auth;
        if($auth !== false) {
            $this->user = $auth;
            $valid = true;
        }
    }
    if(!$valid) {
        redirect("/general/index");
    }

		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('clients/corporate_clients_model');		
		$this->load->model('clients/retail_clients_model');
		$this->load->model('clients/clients_trading_model');
		$this->load->model('trading_model');
		$this->load->model('econ_model');
	}
	
	public function index () {		
		$user_id = $this->user->id;
		$username = $this->user->username;
		$data = $this->input->post ();
			
		$response = array ();
		foreach ($data as $type => $what) {
			$response[$type] = array ();
			foreach ($what as $item) 
				$response[$type][$item] = $this->$item ();
		}

		$this->output->set_content_type('application/jsonp');
		$this->output->set_output ( json_encode ( $response ) );		
  }
  
	function get_corporate_offers () {		
		$user_id = $this->user->id;
		$offers = $this->corporate_clients_model->get_corporate_offers ($user_id);
		return $offers;
  }	
  
	function next_corporate_clients () {
		$user_id = $this->user->id;
		$this->corporate_clients_model->next_corporate_clients ($user_id);
	}

};
