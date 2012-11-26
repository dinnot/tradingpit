<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends CI_Controller {
		
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
		$this->load->model('econ_model');
	}
	
	public function index () {		
		$user_id = $this->user->id;
		
		$data['user_id'] = $user_id;
		$data['retail_rate'] = $this->retail_clients_model->get_all_rate_exchange ($user_id);
		$data['amount'] = $this->clients_trading_model->get_user_amount ($user_id);
				
		$this->load->view ("clients", $data); 
  }
  
  public function get_time () {		
		$time['server_time'] = time ();
		$this->output->set_content_type('application/jsonp');
		$this->output->set_output ( json_encode ( $time ) );
  }

	 // corporate	
	 function get_corporate_offers () {		
		$user_id = $this->user->id;
		$offers = $this->corporate_clients_model->get_corporate_offers ($user_id);
//		$this->corporate_clients_model->generate_for_all_users (); /////// // / // / / /
		$this->output->set_content_type('application/jsonp');
		$this->output->set_output ( json_encode ( $offers ) );
  }

	function next_corporate_clients () {
		$user_id = $this->user->id;
		$this->corporate_clients_model->next_corporate_clients ($user_id);
	}
  
  function set_quote () {  
  	$offer_id = $this->input->get_post ("offer_id");
		$user_id = $this->user->id;
  	$quote = $this->input->get_post ("quote");  	
  	$this->clients_trading_model->set_quote ($offer_id, $user_id, $quote);
  }
  
  function set_result () {
  	$offer_id = $this->input->get_post ('offer_id');
  	$this->clients_trading->set_result_corporate ($offer_id);
  }
	
	// retail
	function set_exchange_rate () {
		$rate['user_id'] = $this->user->id;
		$rate['pair_id'] = $this->input->get_post ('pair_id');
		$rate['sell'] = $this->input->get_post ('sell');
		$rate['buy'] = $this->input->get_post ('buy');
		
		$this->clients_trading_model->set_exchange_rate ($rate);
	}
	
	function check_next_client () {
		
		$user_id = $this->user->id;
		$response = $this->retail_clients_model->check_next_client ($user_id);
		$response['amount'] = $this->clients_trading_model->get_user_amount ($user_id);
		$this->output->set_content_type('application/json');
		$this->output->set_output ( json_encode ( $response ) );
	}
	
	function get_user_deals () {
		$user_id = $this->user->id;
		$deals = $this->clients_trading_model->get_user_deals ($user_id);
		$this->output->set_content_type('application/json');
		$this->output->set_output ( json_encode ( $deals ) );
	}
	
	function generate_for_all_users () {
		$this->corporate_clients_model->generate_for_all_users ();
	}	
};

