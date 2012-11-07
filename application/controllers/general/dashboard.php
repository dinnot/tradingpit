<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
    public function __construct () {		
        
        parent::__construct ();		
        
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model("Users_model");
        $this->module_name = "dashboard";
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

    public function index () {	
        //$this->load->model("Users_model");
        $data['error'] = $this->session->flashdata('ERROR');
        $this->load->view("general/index", $data);
    }
};

