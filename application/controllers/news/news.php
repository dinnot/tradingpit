<?php
	class News extends CI_Controller {

		public function __construct() {
		
			parent::__construct();
			$this->load->model("NewsModel");
		}

		public function index() {
		
			$data["news"] = $this->NewsModel->get_news();
			
			/////////////// form pt filtru /////////////////////
			$this->load->helper('form');
			$this->load->library('form_validation');
		
			$this->form_validation->set_rules('text','text');
		
			$data["string_to_search"] = $this->input->post('text');
			$this->load->view('news/index', $data);
			
		}
	}
?>