<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retail_clients extends CI_Controller {
	
	public function __construct () {
		
		parent::__construct ();		

		$this->load->database();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('Corporate_clients_model');
		$this->load->model('Retail_clients_model');
	}

	function set_exchange_rate () {
		$rate['user_id'] = $this->input->get_post ('user_id');
		$rate['pair_id'] = $this->input->get_post ('pair_id');
		$rate['sell'] = $this->input->get_post ('sell');
		$rate['buy'] = $this->input->get_post ('buy');
		
		$this->Retail_clients_model->set_exchange_rate ($rate);
	}
	
	function check_next_client () {
		
		$user_id = $this->input->get_post ('user_id');
		//////////////////////
		//		$user_id = 1;	//	
		//////////////////////
		$response = $this->Retail_clients_model->check_next_client ($user_id);
		$response['amount'] = $this->Retail_clients_model->get_user_amount ($user_id);
		$this->output->set_content_type('application/jsonp');
		$this->output->set_output ( json_encode ( $response ) );

	}
}

?>
