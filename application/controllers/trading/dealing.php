<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Dealing extends CI_Controller {

		public function __construct() {
		
			parent::__construct();
			$this->load->model("Dealing_Model");
			
			/*
			$this->load->helper('url');
			$this->load->library('session');
			$this->load->model("Users_model");
			$this->module_name = "dealing";
			$valid = false;
        
			if($this->session->userdata("key")) {
				$key = $this->session->userdata("key");
				$auth = $this->Users_model->getAuth($key, $this->module_name);
				
				if($auth !== false) {
					$this->user = $auth;
					$valid = true;
				}
			}
        
			if(!$valid) {
				redirect("/errors/404");
			}
			*/
		}
		
		public function index() {
			
			/*
			$data["user_id"] = $this->user->id ;
			$data["fx_deals"] = $this->Dealing_Model->get_fx_deals($this->user->id);
			$data["mm_deals"] = $this->Dealing_Model->get_mm_deals($this->user->id);
			*/
			
			$username = "GOGU";
			
			$data["fx_deals"] = $this->Dealing_Model->get_fx_deals($username);
			$data["mm_deals"] = $this->Dealing_Model->get_mm_deals($username);
			
		
			$this->load->view('dealing/index', $data);
			
		}
	}
?>
