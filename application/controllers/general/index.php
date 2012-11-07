<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {
	
    public function __construct () {		
        
        parent::__construct ();		
        
        $this->load->database();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index () {	
        //$this->load->model("Users_model");
        $data['error'] = $this->session->flashdata('ERROR');
        $this->load->view("general/index", $data);
    }
    
    public function register() {
        $this->load->model("Users_model");
        $this->load->model("Game_model");
        $this->load->library('session');

        $settings = array();
        $all_settings = $this->Users_model->getUserSettings();
        foreach($all_settings as $k=>$setting) {
            $settings[$k]['id'] = $setting->id;
            $settings[$k]['name'] = $setting->name;
            $settings[$k]['value'] = $setting->default;
        }
        $result = $this->Users_model->createUser($this->input->post('email'), $this->input->post('password'), $this->input->post('username'), $this->Game_model->getSetting("default_usertype")->value, $this->Game_model->getSetting("default_country")->value, $settings);
        if($result !== Users_model::ERROR_EMAIL_EXISTS && $result !== Users_model::ERROR_USERNAME_EXISTS && $result !== false) {
            $this->session->set_userdata(array("key"=>$result));
            redirect('general/dashboard');
        } else {
            $this->session->set_flashdata('ERROR', "Oops... An error occured");
            redirect('general/index');
        }
    }
    
    public function login() {
        $this->load->model("Users_model");
        $result = $this->Users_model->getLogin($this->input->post('email'), $this->input->post('password'));
        if($result !== false) {
            $this->session->set_userdata(array("key"=>$result));
            redirect('general/dashboard');
        } else {
            $this->session->set_flashdata('ERROR', "Oops... An error occured");
            redirect('general/index');
        }
    }
};

