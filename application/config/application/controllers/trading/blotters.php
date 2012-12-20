<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Blotters extends CI_Controller {

		public function __construct() {
		
			parent::__construct();
			$this->load->model("Blotters_model");
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
				 redirect("/general/index");
			}
							
		}
		
			
		public function index() {
			
			
			$user_id = $this->user->id ;
			
			$data = $this->Blotters_model->get_blotters($user_id) ;
			
			$this->load->view('blotters/index', $data);
			
		}
		
		public function get_blotters() {			
	     
		        $user_id = $this->user->id ;
			
			$data = $this->Blotters_model->get_blotters($user_id) ;

	   		$this->output->set_content_type('application/jsonp');
			$this->output->set_output ( json_encode ( $data ) );
	       }
	       
	       public function get_spot_positions () {
	       	
	       		$user_id = $this->user->id ;
			
			$data = $this->Blotters_model->compute_blotters($user_id) ;

	   		$this->output->set_content_type('application/jsonp');
			$this->output->set_output ( json_encode ( $data ) );
	       }
	       
	        public function get_banks_balances () {
	       	
	       		$user_id = $this->user->id ;
			
			$data = $this->Blotters_model->get_banks_balances($user_id) ;

	   		$this->output->set_content_type('application/jsonp');
			$this->output->set_output ( json_encode ( $data ) );
	       }
	       
	        public function get_banks_capital () {
	       	
	       		$user_id = $this->user->id ;
			
			$data = $this->Blotters_model->compute_banks_capital($user_id) ;

	   		$this->output->set_content_type('application/jsonp');
			$this->output->set_output ( json_encode ( $data ) );
	       }
	       
	        public function get_banks_funds () {
	       	
	       		$user_id = $this->user->id ;
			
			$data = $this->Blotters_model->compute_banks_funds($user_id) ;

	   		$this->output->set_content_type('application/jsonp');
			$this->output->set_output ( json_encode ( $data ) );
	       }
	       
	        public function get_fx_positions () {
	       	
	       		$user_id = $this->user->id ;
			
			$data = $this->Blotters_model->compute_fx_positions($user_id) ;

	   		$this->output->set_content_type('application/jsonp');
			$this->output->set_output ( json_encode ( $data ) );
	       }
		
         	public function get_agg () {
	       	
	       		$user_id = $this->user->id ;
			
			$data = $this->Blotters_model->compute_agg($user_id) ;

	   		$this->output->set_content_type('application/jsonp');
			$this->output->set_output ( json_encode ( $data ) );
	       }	       
	       
	        public function get_fx_deals () {
	       	
	       		$user_id = $this->user->id ;
			
			$data = $this->Blotters_model->get_fx_deals($user_id) ;

	   		$this->output->set_content_type('application/jsonp');
			$this->output->set_output ( json_encode ( $data ) );
	       }
	       
	       	public function get_mm_deals () {
	       	
	       		$user_id = $this->user->id ;
			
			$data = $this->Blotters_model->get_mm_deals($user_id) ;

	   		$this->output->set_content_type('application/jsonp');
			$this->output->set_output ( json_encode ( $data ) );
	       }
	       
	}
?>
