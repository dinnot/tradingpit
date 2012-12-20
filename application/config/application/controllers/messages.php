<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends CI_Controller {

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

		$this->load->helper('form');
		$this->load->model ("messages_model");				
	}

	public function index () {
		$data['user_id'] = $this->user->id;
		$data['username'] = $this->user->username;
		$this->load->view ("messages", $data); 
	}

	public function get_conversations () {
		$user_id = $this->user->id;
		$last_conv = $this->input->get_post ('last_conv');			
			
		$conversations = $this->messages_model->get_conversations ($user_id, $last_conv);

		$this->output->set_content_type('application/json');
		$this->output->set_output ( json_encode ( $conversations ) );			
	}	
	
	public function get_messages () {
		$user_id = $this->user->id;
		$conv_id = $this->input->get_post ('conv_id');
		
		$conversations = $this->messages_model->get_messages (array('conversations_id'=>$conv_id));

		$this->output->set_content_type('application/json');
		$this->output->set_output ( json_encode ( $conversations ) );			
	}	

	public function add_message () {
		$user_id = $this->user->id;
		$conv_id = $this->input->get_post ('conv_id');
		$text = $this->input->get_post ('message');
		$message = array ('user_id'=>$user_id, 'message'=>$text, 'conversations_id'=>$conv_id);

		$this->messages_model->add_message ($message);
	}	
	
	public function add_conversation () {
		$message['message']	= $this->input->get_post ('message');
		$message['subject']	= $this->input->get_post ('subject');
		$users = array ();

		$users[] = $this->messages_model->get_user_id($this->input->get_post ('username'));		
		if ($users[0] == 0) return;
		$users[] = $this->user->id;
		
		$data = array ();
		$data['conv_id'] = $this->messages_model->make_new_conversation ($message, $users);
		$this->output->set_content_type('application/json');
		$this->output->set_output ( json_encode ( $data ) );			
	}
	
	public function get_new_messages () {
		$conv_id = $this->input->get_post ('conv_id');		
		$messages = $this->messages_model->get_messages (array('conversations_id'=>$conv_id, 'seen'=>0));
		$this->output->set_content_type('application/json');
		$this->output->set_output ( json_encode ( $messages ) );			
	}
};

