<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class News extends CI_Controller {

		public function __construct() {
			
			parent::__construct();
			$this->load->model("News_model");
			
			$this->load->helper('url');
						
			$this->load->library('session');
			$this->load->model("Users_model");
			$this->module_name = "news";
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
			
			$data["news"] = $this->News_model->get_news();
			
			$this->load->view('news/index', $data);
			
		}
		
		function get_news() {

			$news = $this->News_model->get_news();		

	   		$this->output->set_content_type('application/jsonp');
			$this->output->set_output ( json_encode ( $news ) );
  		}
  		
  		function insert_news() { 
  			$this->News_model->insert_news() ;
  		}
  		
  		function update_news() { 
  			$this->News_model->update_news() ;
  		}	
	}
?>
