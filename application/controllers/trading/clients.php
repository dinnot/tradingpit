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

		$this->load->database();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('Corporate_clients_model');		
		$this->load->model('Retail_clients_model');
	}
	
	public function index () {
		
		$user_id = $this->user->id;
		print $this->user->username;
		
		$data['user_id'] = $user_id;
		$data['retail_rate'] = $this->Retail_clients_model->get_all_rate_exchange ($user_id);
		$data['amount'] = $this->Retail_clients_model->get_user_amount ($user_id);
		
		$this->load->view ("clients", $data); 
  }
  
};

