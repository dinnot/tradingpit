<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ebroker extends CI_Controller {
		
	public function __construct () {
		
		parent::__construct ();		

    $this->load->database();
    $this->load->helper('url');
    $this->load->library('session');
    $this->load->model("Users_model");
    $this->module_name = "trading";
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
		$this->load->model('ebroker_model');
	}
	
	function add_price () {
		$users_id = $this->user->id;
		$price['pairs_id'] = $this->input->get_post ('pairs_id');		
		$price['deal'] = $this->input->get_post ('deal');
		$price['amount'] = $this->input->get_post ('amount');		
		$price['price'] = $this->input->get_post ('price');		
		
		$this->ebroker_model->add_price ($users_id, $price);
	}	
	
	function get_best_prices () {
		$users_id = $this->user->id;
		$prices = $this->ebroker_model->get_best_prices ($users_id);

		$this->output->set_content_type('application/jsonp');
		$this->output->set_output ( json_encode ( $prices ) );		
	}
	
	function make_deal () {
		$users_id = $this->user->id;
		$deal['pairs_id'] = $this->input->get_post ('pairs_id');
		$deal['deal'] = $this->input->get_post ('deal');
		$deal['price'] = $this->input->get_post ('price');
		$deal['amount'] = $this->input->get_post ('amount');
		
		$this->ebroker_model->make_deal ($deal, $users_id);
	}
	
	function get_user_prices () {
		$users_id = $this->user->id;
		$prices = $this->ebroker_model->get_user_prices ($users_id);

		$this->output->set_content_type('application/jsonp');
		$this->output->set_output ( json_encode ( $prices ) );			
	}
	
	function remove_user_price () {
		$users_id = $this->user->id;
		$price_id = $this->input->get_post ('price_id');
		$this->ebroker_model->remove_user_price ($users_id, $price_id);
	}
	
	function cancel_user_prices () {
		$users_id = $this->user->id;
		$pairs_id = $this->input->get_post ('pairs_id');
		$this->ebroker_model->cancel_user_prices ($users_id, $pairs_id);
	}
	
	function hold_user_prices () {
		$users_id = $this->user->id;
		$this->ebroker_model->hold_user_price ($users_id);
	}
};

