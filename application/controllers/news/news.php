<?php
	class News extends CI_Controller {

		public function __construct() {
		
			parent::__construct();
<<<<<<< HEAD
			$this->load->model("News_Model");
		}

		public function index() {
		
			$data["news"] = $this->News_Model->get_news();
			
=======
                        //$this->load->database();
		}

		public function index() {
                        $this->load->model("News_model");
			$data["news"] = $this->News_model->get_news();
>>>>>>> 8a878c0ee8588efb61256a688a9e3fe3f1717d7f
			/////////////// form pt filtru /////////////////////
			$this->load->helper('form');
			$this->load->library('form_validation');
		
			$this->form_validation->set_rules('text','text');
		
			$data["string_to_search"] = $this->input->post('text');
			$this->load->view('news/index', $data);
			
		}
	}
?>