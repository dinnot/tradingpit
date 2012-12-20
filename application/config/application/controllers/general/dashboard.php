<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
    public function __construct () {		
        
        parent::__construct ();		
        
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('form');
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
        if(is_null($this->user->jobs_id)) {
            redirect("/general/dashboard/getgeneraljob");
        } else {
            $this->load->model("Banks_model");
            $job = $this->Banks_model->getFullJob($this->user->jobs_id);
            if($job['signed_secondparty'] == 0) {
                redirect("/general/dashboard/showcontract/".$this->user->jobs_id);
            }
        }
        $data['error'] = $this->session->flashdata('ERROR');
        redirect("/trading/trading");
    }
    
    public function getgeneraljob() {
        $this->load->model("Banks_model");
        $this->load->model("Game_model");
        $position = $this->Game_model->getSettingValue('default_employment_position');
        $job = $this->Banks_model->getFreeJob($this->user->id, $position);
        if($job === false) {
            $main_investor = $this->Game_model->getSettingValue("main_investor");
            $bank = $this->Banks_model->createBank(array($main_investor=>1));
            $clauses = explode(",",$this->Game_model->getSettingValue("default_employment_clauses"));
            $clauses_values = array();
            $def_payment = explode(",",$this->Game_model->getSettingValue("default_employment_payment"));
            for($i = 0; $i < count($def_payment); $i+=2) {
                $clauses_values[$def_payment[$i]][] = $def_payment[$i+1];
            }
            $this->Banks_model->createJob($bank, $position, $clauses, $clauses_values, 3); //availability 3 hardcoded
            $this->Banks_model->createJob($bank, $position, $clauses, $clauses_values, 3); //repeat 2 times - hardcoded
            $job = $this->Banks_model->getFreeJob($this->user->id, $position);
        }
        redirect("/general/dashboard/showcontract/".$job);
    }
    
    public function showcontract($job) {
        $this->load->helper('url');
        $this->load->model("Banks_model");
        $data["contract"] = $this->Banks_model->getFullJob($job);
        $data["contract"]["uname"] = $this->user->username;
        $data["signurl"] = base_url("/general/dashboard/signcontract/{$data['contract']['cid']}");
        $this->load->view("general/contract", $data);
    }
    
    public function signcontract($id) {
        $this->load->model("Banks_model");
        $this->Banks_model->signContract($id);
        redirect("/general/dashboard");
    }
};

