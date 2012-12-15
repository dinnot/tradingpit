<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trading extends CI_Controller {
	
    public function __construct () {		
        
        parent::__construct ();		
        
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model("Users_model");
        $this->module_name = "trading";
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
        $this->load->model("Trading_model");
        $this->load->model("Validate_model");
        $this->Users_model->updateTrading($this->user->id);
    }

    public function compute_spot_positions ( &$data ) { 

        $user_id = $this->user->id ;
        //$user_id = 15;
        $this->load->model("Blotters_model");
        $data['spot_positions']= $this->Blotters_model->get_users_fx_positions($user_id);

    }
    
    public function index () {	
        $pairs = $this->Trading_model->getPairs();
        $data = array("pairs"=>$pairs, "amounts"=>array(1,2,3,4,5), "user"=>$this->user->id);
        $this->compute_spot_positions($data);
        $this->load->view("trading/index", $data);
    }
    
    public function add() {
        $data = $_POST;
        $this->load->model("Game_model");
        $settings = $this->Game_model->getAllSettings();
        $ret = $this->Trading_model->createEnquiries($this->user->id, $this->user->bid, 7, $data['pair'], $data['amount'], $settings);
        $this->load->view("ajax", array("error"=>false, "data"=>$ret));
    }
    
    public function respond() {
        $data = $_POST;
        $ret = $this->Trading_model->respondEnquiry($data['id'], $this->user->id, $data['buy'], $data['sell']);
        
        if( !$this->Validate_model->Validate_price($data['buy']) || !$this->Validate_model->Validate_price($data['sell']) ) 
        	$ret = false ; 
        
        if($ret !== false) {
            $this->load->view("ajax", array("error"=>false, "data"=>$ret));
        } else {
            $this->load->view("ajax", array("error"=>true));
        }
    }
    
    public function buy() {
        $data = $_POST;
        $ret = $this->Trading_model->buyEnquiry($data['id'], $this->user->id);
        if($ret !== false) {
            $this->load->view("ajax", array("error"=>false, "data"=>$ret));
        } else {
            $this->load->view("ajax", array("error"=>true));
        }
    }
    
    public function sell() {
        $data = $_POST;
        $ret = $this->Trading_model->sellEnquiry($data['id'], $this->user->id);
        if($ret !== false) {
            $this->load->view("ajax", array("error"=>false, "data"=>$ret));
        } else {
            $this->load->view("ajax", array("error"=>true));
        }
    }
    
    public function cancel() {
        $data = $_POST;
        $ret = $this->Trading_model->cancelEnquiry($data['id'], $this->user->id);
        if($ret !== false) {
            $this->load->view("ajax", array("error"=>false, "data"=>$ret));
        } else {
            $this->load->view("ajax", array("error"=>true));
        }
    }
    
    public function status() {
        $data = $_POST;
        if(isset($data['ids']) && count($data['ids']) > 0) {
            $ids = $data['ids'];
            $sts = array();
            foreach($data['sts'] as $x=>$st) {
                $sts[$ids[$x]] = $st;
            }
            $ret = $this->Trading_model->statusEnquiry($this->user->id, $ids, $sts);
        } else {
            $ret = false;
        }
        if($ret !== false) {
            $this->load->view("ajax", array("error"=>false, "data"=>$ret));
        } else {
            $this->load->view("ajax", array("error"=>true));
        }
    }
    
    public function newen() {
        if(isset($_POST['ids'])) {
            $ids = $_POST['ids'];
        } else {
            $ids = false;
        }
        $ret = $this->Trading_model->newEnquiries($this->user->id, $ids);
        if($ret !== false) {
            $this->load->view("ajax", array("error"=>false, "data"=>$ret));
        } else {
            $this->load->view("ajax", array("error"=>true));
        }
    }
};

