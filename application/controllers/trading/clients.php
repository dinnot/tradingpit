<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends CI_Controller {

	var $user;
		
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
		$this->load->model('clients/Corporate_clients_model');		
		$this->load->model('clients/Retail_clients_model');
		$this->load->model('clients/Clients_model');		
		$this->load->model('clients/Clients_trading_model');
	}
	
	public function index () {		
		$user_id = $this->user->id;
		print $this->user->username;
		
		$data['user_id'] = $user_id;
		$data['retail_rate'] = $this->Retail_clients_model->get_all_rate_exchange ($user_id);
		$data['amount'] = $this->Retail_clients_model->get_user_amount ($user_id);
		
		$this->load->view ("clients", $data); 
  }
  
  public function get_time () {		
		$time['server_time'] = time ();
		$this->output->set_content_type('application/jsonp');
		$this->output->set_output ( json_encode ( $time ) );
  }
	
	 function get_corporate_offers () {		
		$user_id = $this->input->get_post ("user_id");
		if (rand () % 10 == 0)
			$this->generate_corporate_client ();	
	
		$offers = $this->Clients_model->get_corporate_offers ($user_id);
		$this->output->set_content_type('application/jsonp');
		$this->output->set_output ( json_encode ( $offers ) );
  }
  
  function set_quote () {  
  	$offer_id = $this->input->get_post ("offer_id");
  	$user_id = $this->input->get_post ("user_id");
  	$quote = $this->input->get_post ("quote");  	
  	$this->Clients_trading_model->set_quote ($offer_id, $user_id, $quote);
  }
  
  function set_result () {
  	$offer_id = $this->input->get_post ('offer_id');
  	$this->Clients_trading_model->set_result_corporate ($offer_id);
  }
	
  function generate_corporate_client () {		  	
		$this->Corporate_clients_model->generate_coporate_client ();
  }
  
};

