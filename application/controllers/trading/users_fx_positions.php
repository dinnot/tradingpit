<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Users_fx_positions extends CI_Controller {

		public function __construct() {
		
			parent::__construct();
			$this->load->model("Users_fx_positions_model");
			
			/*
			$this->load->helper('url');
			$this->load->library('session');
			$this->load->model("Users_model");
			$this->module_name = "dealing";
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
			*/
		}
		
		public function index () {
		
 			//$user_id = $this->user-id ;
			$user_id = 15;
			
			$data['fx_positions'] = $this->Users_fx_positions_model->get_users_fx_positions($user_id);
			$RC = $this->Users_fx_positions_model->get_reporting_currencies();
			
			$percentage = (double)8 / 100 ;
			$user_percentage = (double)24 / 100 ;
			
			/*
				TER : 0    RIK  : 1    HAT : 2 
			   
			    	0 :  buy  TER/RIK
			    	1 :  sell TER/RIK 
			    	2 :  buy  HAT/RIK
			    	3 :  sell HAT/RIK 
			*/ 
			
			$rep_ccy[0][0] = 1 ; 
			$rep_ccy[0][1] = $RC[0]['value'] ; 
			$rep_ccy[0][2] = $RC[0]['value'] * $RC[3]['value'] ; 
			$rep_ccy[1][0] = $RC[1]['value'] ; 
			$rep_ccy[1][1] = 1 ; 
			$rep_ccy[1][2] = $RC[3]['value'] ; 
			$rep_ccy[2][0] = $RC[2]['value'] * $RC[1]['value'] ; 
			$rep_ccy[2][1] = $RC[2]['value'] ; 
			$rep_ccy[2][2] = 1 ; 
			
			
			$data['agg']['reporting_currency'] = 0 ;
			$data['agg']['position_limit'] = $user_percentage * $data['fx_positions'][0]['banks_amount'] ;
			$data['agg']['risk'] = "IN LIMIT" ;
			
			for( $i = 0 ; $i < 3 ; $i++ ) {
			
				$data['fx_positions'][$i]['position_limit'] = $percentage * $data['fx_positions'][$i]['banks_amount'];
			
				$data['fx_positions'][$i]['reporting_currency'] = array() ;
				$data['fx_positions'][$i]['risk'] = array() ;
								
								
				for( $j = 0 ; $j < 3 ; $j++ ) {
					
					
					array_push($data['fx_positions'][$i]['reporting_currency'], $data['fx_positions'][$i]['amount'] * $rep_ccy[$i][$j] ) ; 
					array_push($data['fx_positions'][$i]['risk'], "IN LIMIT");
	
					

					if( abs($data['fx_positions'][$i]['reporting_currency'][$j]) > abs($data['agg']['reporting_currency']))
						$data['agg']['reporting_currency'] = $data['fx_positions'][$i]['reporting_currency'][$j] ;
					
					
					
					if( $i != 1 && abs($data['fx_positions'][$i]['reporting_currency'][$j]) > $data['fx_positions'][$i]['position_limit'] ) 
						array_push($data['fx_positions'][$i]['risk'], "BREAK");
					
				}
			}	
			
			if( abs($data['agg']['reporting_currency']) > $data['agg']['position_limit'] )
				$data['agg']['risk'] = "BREAK" ;
			
			$this->load->view('users_fx_positions/index', $data);	
		}
		
	}
	
