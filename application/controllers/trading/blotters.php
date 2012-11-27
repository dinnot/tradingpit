<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Blotters extends CI_Controller {

		public function __construct() {
		
			parent::__construct();
			$this->load->model("Blotters_model");
			$this->load->model("Game_model");			
			$this->load->helper('url');
			
			$this->load->library('session');
			$this->load->model("Users_model");
			$this->module_name = "blotters";
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
		
		public function compute_banks_balances( &$data ) {
			
			$user_id = $this->user->id ;
			//$user_id = 15;
			
			$data['banks_balances']= $this->Blotters_model->get_banks_balances($user_id);
			
		}
		
		
		public function compute_spot_positions ( &$data ) { 
			
			$user_id = $this->user->id ;
			//$user_id = 15;
			
			$data['spot_positions']= $this->Blotters_model->get_users_fx_positions($user_id);
			
<<<<<<< HEAD
			for( $i = 0 ; $i < 3 ; $i++ ) {  
				$data['spot_positions'][$i]['position_rate'] = $data['spot_positions'][$i]['sumrate'] ;
				if( $data['spot_positions'][$i]['position_rate'] )
					$data['spot_positions'][$i]['position_rate'] /= $data['spot_positions'][$i]['position_amount'] ; 	
=======
			for( $i = 0 ; $i < 3 ; $i++ ) {
				$data['spot_positions'][$i]['position_rate'] = $data['spot_positions'][$i]['sumrate'] ;
				if( $data['spot_positions'][$i]['position_rate'] )
@					$data['spot_positions'][$i]['position_rate'] /= $data['spot_positions'][$i]['position_amount'] ; 		
>>>>>>> 738d50d5cae25203423c91fda551dbdfb6bc786c
			}		
		}
		
		public function compute_banks_capital ( &$data ) { 
			
			$user_id = $this->user->id ;
			//$user_id = 15;
			
			
			$banks_info = $this->Blotters_model->get_banks_info($user_id) ;
			$currency = $banks_info[0]['currencies_id'] - 1 ;
			$capital = $banks_info[0]['capital'] ;
			
			$data['currency'] = $currency ;
			
			if( $currency == 0 ) {
<<<<<<< HEAD
@				$data['capital'][0] =  $capital ; 
=======
				$data['capital'][0] =  $capital ; 
>>>>>>> 738d50d5cae25203423c91fda551dbdfb6bc786c
@				$data['capital'][1] =  round( $capital * $this->Game_model->getSettingValue('bot_bprice1'), 4 ) ;
@				$data['capital'][2] =  round( $capital / $this->Game_model->getSettingValue('bot_sprice3'), 4 ) ;
			}
			elseif ( $currency == 1 ) {
@				$data['capital'][0] =  round( $capital / $this->Game_model->getSettingValue('bot_sprice1'), 4 ) ; 
@				$data['capital'][1] =  $capital ;
@				$data['capital'][2] =  round( $capital / $this->Game_model->getSettingValue('bot_sprice2'), 4 ) ;
			}
			else {
@				$data['capital'][0] =  round( $capital * $this->Game_model->getSettingValue('bot_bprice3'), 4 ) ; 
@				$data['capital'][1] =  round( $capital * $this->Game_model->getSettingValue('bot_bprice2'), 4 ) ; 
@				$data['capital'][2] =  $capital ;
			}
		}
		
		public function compute_banks_funds ( &$data ) {

			$user_id = $this->user->id ;		
			//$user_id = 15;
				
			$fx_pnl = $this->Blotters_model->get_users_fx_pnl($user_id) ;
			$mm_pnl = $this->Blotters_model->get_users_mm_pnl($user_id) ;
			
			
@			$pnl[0] = $fx_pnl[0]['amount'] + 
<<<<<<< HEAD
=======
@				  $fx_pnl[1]['amount'] * $this->Game_model->getSettingValue('bot_bprice1') +
>>>>>>> 738d50d5cae25203423c91fda551dbdfb6bc786c
@				  $fx_pnl[2]['amount'] / $this->Game_model->getSettingValue('bot_sprice3') + 
@				  $mm_pnl[0]['amount'] + 
@				  $mm_pnl[1]['amount'] * $this->Game_model->getSettingValue('bot_bprice1') +
@				  $mm_pnl[2]['amount'] / $this->Game_model->getSettingValue('bot_sprice3') + 
				  
@			$pnl[1] = $fx_pnl[0]['amount'] / $this->Game_model->getSettingValue('bot_sprice1') +  
@				  $fx_pnl[1]['amount'] +
@				  $fx_pnl[2]['amount'] / $this->Game_model->getSettingValue('bot_sprice2') + 
@				  $mm_pnl[0]['amount'] / $this->Game_model->getSettingValue('bot_sprice1') +  
@				  $mm_pnl[1]['amount'] +
@				  $mm_pnl[2]['amount'] / $this->Game_model->getSettingValue('bot_sprice2') + 
			
			
@			$pnl[2] = $fx_pnl[0]['amount'] * $this->Game_model->getSettingValue('bot_bprice3') + 
@				  $fx_pnl[1]['amount'] * $this->Game_model->getSettingValue('bot_bprice2') +
@				  $fx_pnl[2]['amount'] + 
@				  $mm_pnl[0]['amount'] * $this->Game_model->getSettingValue('bot_bprice3') +  
@				  $mm_pnl[1]['amount'] * $this->Game_model->getSettingValue('bot_bprice2') +
@				  $mm_pnl[2]['amount'] ; 
			
				  
			for( $i = 0 ; $i < 3 ; $i++ )
				$data['funds'][$i] = $data['capital'][$i] + $pnl[$i] ; 
		}

		
		public function compute_fx_positions( &$data ) {
			
			$user_id = $this->user->id ;
			//$user_id = 15;
			
			
			///////////////////// hardcodat ///////////////////////////
			$percentage = (double)8 / 100 ;
			////////////////////////////////////////////////////////////

			$data['fx_positions'][0]['ccy_name'] = "TER";
			$data['fx_positions'][1]['ccy_name'] = "RIK";
			$data['fx_positions'][2]['ccy_name'] = "HAT";
									
		
		
		//////////////////////////////////        AMOUNT       ///////////////////////////////////////
			
@			$data['fx_positions'][0]['amount'] =  $data['spot_positions'][0]['position_amount'] - 
@							      $data['spot_positions'][2]['position_amount'] * 
@							      $data['spot_positions'][2]['position_rate'] ; 
			
@			$data['fx_positions'][1]['amount'] = -$data['spot_positions'][0]['position_amount'] *
@							      $data['spot_positions'][0]['position_rate'] - 
@							      $data['spot_positions'][1]['position_amount'] * 
@							      $data['spot_positions'][1]['position_rate'] ; 
			
<<<<<<< HEAD
			$data['fx_positions'][2]['amount'] =  $data['spot_positions'][1]['position_amount'] + 
							      $data['spot_positions'][2]['position_amount'] ; 
		
		
		
							     
		/////////////////////////////////////////   REPORTING CURRENCY       //////////////////////////////////////////////
=======
@			$data['fx_positions'][2]['amount'] = $data['spot_positions'][1]['position_amount'] + 
@							     $data['spot_positions'][2]['position_amount'] ; 
							     
		/////////////////////////////////////////////////////////////////////////////////////////////
>>>>>>> 738d50d5cae25203423c91fda551dbdfb6bc786c
		
@			$data['fx_positions'][0]['rep_ccy'][0] =   $data['fx_positions'][0]['amount']   ; 
@			$data['fx_positions'][0]['rep_ccy'][1] =   $data['spot_positions'][0]['position_amount'] * 
@								   $data['spot_positions'][0]['position_rate']   -   
@							     	   $data['spot_positions'][2]['position_amount'] * 
@							           $data['spot_positions'][2]['position_rate']   *
@							           $this->Game_model->getSettingValue('bot_bprice1') ; 
@			$data['fx_positions'][0]['rep_ccy'][2] =   $data['spot_positions'][0]['position_amount'] /
@								   $data['spot_positions'][2]['position_rate']   -  
@								   $data['spot_positions'][2]['position_amount'] ;
<<<<<<< HEAD
@								   
@	
@			$data['fx_positions'][1]['rep_ccy'][0] =  -$data['spot_positions'][0]['position_amount'] *
@								   $data['spot_positions'][0]['position_rate']   /
@								   $this->Game_model->getSettingValue('bot_sprice1') -
@								   $data['spot_positions'][1]['position_amount'] *
@								   $data['spot_positions'][1]['position_rate']   /
@								   $this->Game_model->getSettingValue('bot_sprice1') ;
@			$data['fx_positions'][1]['rep_ccy'][1] =   $data['fx_positions'][1]['amount']   ;
@			$data['fx_positions'][1]['rep_ccy'][2] =  -$data['spot_positions'][0]['position_amount'] *
@							           $data['spot_positions'][0]['position_rate']   /
@							           $data['spot_positions'][1]['position_rate']   -
@							           $data['spot_positions'][1]['position_amount'] ;
			
@			$data['fx_positions'][2]['rep_ccy'][0] =   $data['spot_positions'][1]['position_amount'] * 
@								   $data['spot_positions'][1]['position_rate'] /
@								   $this->Game_model->getSettingValue('bot_sprice1') + 
@								   $data['spot_positions'][2]['position_amount'] *
@								   $data['spot_positions'][2]['position_rate'] ; 
@			$data['fx_positions'][2]['rep_ccy'][1] =   $data['spot_positions'][1]['position_amount'] *
@								   $data['spot_positions'][1]['position_rate']   +  
@								   $data['spot_positions'][2]['position_amount'] *
@								   $data['spot_positions'][2]['position_rate'] *			   
@								   $this->Game_model->getSettingValue('bot_bprice1') ; 
@			$data['fx_positions'][2]['rep_ccy'][2] =   $data['fx_positions'][2]['amount']   ; 
            
            
            
            		////////////////////////////////////// POSITION LIMIT /  RATE /   RISK    /////////////////////////////////////
	
	
=======
								   
	
@			$data['fx_positions'][1]['rep_ccy'][0] =  -$data['spot_positions'][0]['position_amount'] *
@								   $data['spot_positions'][0]['position_rate']   /
@								   $this->Game_model->getSettingValue('bot_sprice1') -
@								   $data['spot_positions'][1]['position_amount'] *
@								   $data['spot_positions'][1]['position_rate']   /
@								   $this->Game_model->getSettingValue('bot_sprice1') ;
@			$data['fx_positions'][1]['rep_ccy'][1] =   $data['fx_positions'][1]['amount']   ;
@			$data['fx_positions'][1]['rep_ccy'][2] =  -$data['spot_positions'][0]['position_amount'] *
@							           $data['spot_positions'][0]['position_rate']   /
@							           $data['spot_positions'][1]['position_rate']   -
@							           $data['spot_positions'][1]['position_amount'] ;
			
@			$data['fx_positions'][2]['rep_ccy'][0] =   $data['spot_positions'][1]['position_amount'] * 
@								   $data['spot_positions'][1]['position_rate'] /
@								   $this->Game_model->getSettingValue('bot_sprice1') + 
@								   $data['spot_positions'][2]['position_amount'] *
@								   $data['spot_positions'][2]['position_rate'] ; 
@			$data['fx_positions'][2]['rep_ccy'][1] =   $data['spot_positions'][1]['position_amount'] *
@								   $data['spot_positions'][1]['position_rate']   +  
@								   $data['spot_positions'][2]['position_amount'] *
@								   $data['spot_positions'][2]['position_rate'] *			   
@								   $this->Game_model->getSettingValue('bot_bprice1') ; 
@			$data['fx_positions'][2]['rep_ccy'][2] =   $data['fx_positions'][2]['amount']   ; 
            		
            		/////////////////////////////////////////////////////////////////////////////////////////
>>>>>>> 738d50d5cae25203423c91fda551dbdfb6bc786c
	
			for( $i = 0 ; $i < 3 ; $i++ ) {
				
				for( $j = 0 ; $j < 3 ; $j++ ) {
					
@					$data['fx_positions'][$i]['rep_ccy'][$j] = round( $data['fx_positions'][$i]['rep_ccy'][$j], 4 ) ;
					if( abs($data['fx_positions'][$i]['rep_ccy'][$j]) > abs($data['fx_positions'][$i]['amount']) )
@						$data['fx_positions'][$i]['rate'][$j] = round($data['fx_positions'][$i]['rep_ccy'][$j] / $data['fx_positions'][$i]['amount'],4) ;  
					else
@						$data['fx_positions'][$i]['rate'][$j] = round($data['fx_positions'][$i]['amount'] / $data['fx_positions'][$i]['rep_ccy'][$j],4) ; 
						 				
					$data['fx_positions'][$i]['limit'][$j] = $percentage * $data['funds'][$j] ; 
					$data['fx_positions'][$i]['risk'][$j] = "IN LIMIT" ;
				
					if( $j != $data['currency'] && abs($data['fx_positions'][$i]['rep_ccy'][$j]) > $data['fx_positions'][$i]['limit'][$j] )
						$data['fx_positions'][$i]['risk'][$j] = "BREAK" ;
				}	
			}					 			
		}



		
		public function compute_agg ( &$data ) {
		
			//////////////// hardcodat ////////////////////////
			$user_percentage = (double)12 / 100 ;
			///////////////////////////////////////////////////
			
		
			for( $j = 0 ; $j < 3 ; $j++ ) {
				
				$data['agg']['rep_ccy'][$j] = 0 ;
				$data['agg']['limit'][$j] = $user_percentage * $data['funds'][$j] ;
				
				for( $i = 0 ; $i < 3 ; $i++ ) {
				
					if( abs($data['fx_positions'][$i]['rep_ccy'][$j]) > abs($data['agg']['rep_ccy'][$j]) )  						$data['agg']['rep_ccy'][$j] = $data['fx_positions'][$i]['rep_ccy'][$j] ;
				}
				
				$data['agg']['risk'][$j] = "IN LIMIT" ;
				if( abs($data['agg']['rep_ccy'][$j])  > $data['agg']['limit'][$j] )
					$data['agg']['risk'][$j] = "BREAK" ;
			} 	
			
		}

		
			
		public function index() {
			
			
			$user_id = $this->user->id ;
			//$user_id = 15;
			
			$data["fx_deals"] = $this->Blotters_model->get_fx_deals($user_id);
			$data["mm_deals"] = $this->Blotters_model->get_mm_deals($user_id);
			
			$this->compute_banks_capital($data) ;
			$this->compute_banks_funds($data) ;
			$this->compute_banks_balances($data);
			$this->compute_spot_positions($data);
			$this->compute_fx_positions($data) ;
			$this->compute_agg($data);
			
			
			//      TER     : 0       RI  : 1   HAT     : 2 
			//      TER/RIK : 0   HAT/RIK : 1   HAT/TER : 2 
					
			$this->load->view('blotters/index', $data);
			
		}
	}
?>
