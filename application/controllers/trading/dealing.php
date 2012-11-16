<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Dealing extends CI_Controller {

		public function __construct() {
		
			parent::__construct();
			$this->load->model("Dealing_model");
			
			
			$this->load->helper('url');
			$this->load->library('session');
			$this->load->model("Users_model");
			$this->module_name = "blotters";
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
			
		}
		
		public function index() {
			
			
			$user_id = $this->user->id ;
			//$user_id = 2;
			
			$data["fx_deals"] = $this->Dealing_model->get_fx_deals($user_id);
			$data["mm_deals"] = $this->Dealing_model->get_mm_deals($user_id);
			
		
			$this->load->view('dealing/index', $data);
			
		}
	}
?>
