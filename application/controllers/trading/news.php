<?php
	class News extends CI_Controller {

		public function index() {
                    echo "START";
                        $this->load->model("News_model");
			$data["news"] = $this->News_model->get_news();
			echo "st2";
			/////////////// form pt filtru /////////////////////
			$this->load->helper('form');
			$this->load->library('form_validation');
		
			$this->form_validation->set_rules('text','text');
		
			$data["string_to_search"] = $this->input->post('text');
			$this->load->view('news/index', $data);
			
		}
		public function test() {
			exit ("WTF");
		}
	}
?>