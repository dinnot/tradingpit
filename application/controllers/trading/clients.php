<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends CI_Controller {
	
	public function __construct () {
		
		parent::__construct ();		

		$this->load->database();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('Corporate_clients_model');		
		$this->load->model('Retail_clients_model');
	}
	
	public function index () {
		
		$user_id = 1;
		$data['retail_rate'] = $this->Retail_clients_model->get_all_rate_exchange ($user_id);
		$data['amount'] = $this->Retail_clients_model->get_user_amount ($user_id);
		$this->load->view ("clients", $data); 
  }
  
  function get_new_clients_offers () {
		
		$user_id = $this->input->get_post ("user_id");
		if (rand () % 20 == 0)
			$this->generate_offer ();	
		$offers = $this->Corporate_clients_model->get_new_offers ($user_id);
		$this->output->set_content_type('application/jsonp');
		$this->output->set_output ( json_encode ( $offers ) );

  }
  
  function set_quote () {
  
  	$offer_id = $this->input->get_post ("offer_id");
  	$user_id = $this->input->get_post ("user_id");
  	$quote = $this->input->get_post ("quote");
  	
  	$this->Corporate_clients_model->set_quote ($offer_id, $user_id, $quote);
  }
  
  function set_result () {
  	$offer_id = $this->input->get_post ('offer_id');
  	$this->Corporate_clients_model->set_result ($offer_id);
  }
  
  function generate_offer () {
  	
//  	$suggestion = $this->input->get_post ("user_id");	
		$suggestion = 0;
		$users = $this->Corporate_clients_model->get_users ($suggestion);
		
		$offer['market'] = rand () % 2;
		if ($offer['market'] == 0) {
			$offer['market'] = "FX";
			$offer['first_ccy'] = 1;
			$offer['second_ccy'] = 2;
		}
		else {
			$offer['market'] = "MM";
			$offer['first_ccy'] = 1;
			$offer['second_ccy'] = 1;
		}  	
		
		$offer['client_id'] = $this->Corporate_clients_model->get_random_client ();
		$offer['amount'] = rand () % 10 + 1;
		$offer['deal'] = "BUY";
		$offer['period_id'] = 1;
		$offer['date'] = time () + 1;
		
		$this->Corporate_clients_model->insert_client_offer ($offer, $users);
  }
};

