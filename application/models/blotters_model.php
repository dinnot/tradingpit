<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Blotters_model extends CI_Model {
	
		public function __construct () {
			
			$this->load->database() ;  
			$this->load->model("Game_model");
		}
		
		
		public function get_clients_deals ( $user_id, $type, $limit = 0 ) {
		
			
			$what = array() ;
			$what[] = "deals.id as deal_id"; 
			$what[] = "period" ;
			$what[] = "periods.name as period_name" ;
			$what[] = "ccy_pair" ;
			if( $type == 2 ) 
				$what[] = "currencies.shortname as ccy_name" ;  
			else {
				$what[] = "c1.shortname as first_currency" ;
				$what[] = "c2.shortname as second_currency" ;
			}
			$what[] = "amount_base_ccy" ; 
			$what[] = "price" ; 
			$what[] = "clients.id as counter_party";
			$what[] = "clients.name as counter_party_name" ;
			$what[] = "trade_date" ; 
			$what[] = "value_date" ; 
			$what[] = "user_id" ; 
			$what[] = "users.username as user_name";
			
		
			$where = "deals.counter_party = (-1) * clients.id " ;

		
			$this->db->select($what) ; 
			$this->db->from('deals, clients') ;
			
			if( $type == 1 ) {
				$this->db->join('currency_pairs','currency_pairs.id = ccy_pair','left');  
				$this->db->join('currencies c1','currency_pairs.currency0 = c1.id','left') ;
				$this->db->join('currencies c2','currency_pairs.currency1 = c2.id','left') ;
			}
			else
				$this->db->join('currencies','ccy_pair = currencies.id','left') ;
			
			$this->db->join('users','users.id = user_id','left');
			$this->db->join('periods','periods.id = period','left') ;
			$this->db->where('type',$type);
			$this->db->where('user_id',$user_id) ;
			$this->db->where($where) ;
			$this->db->order_by('trade_date','desc') ; 
			if( $limit) $this->db->limit($limit,0);
			$query = $this->db->get() ;
		
			return $query->result_array() ;
			
		}
		
		public function get_users_deals ( $user_id, $type, $limit = 0 ) {
		
			$what = array() ; 
			$what[] = "deals.id as deal_id";
			$what[] = "period" ;
			$what[] = "periods.name as period_name" ;
			$what[] = "ccy_pair" ; 
			if( $type == 2 ) 
				$what[] = "currencies.shortname as ccy_name" ;  
			else {
				$what[] = "c1.shortname as first_currency" ;
				$what[] = "c2.shortname as second_currency" ;
			}
			$what[] = "amount_base_ccy" ; 
			$what[] = "price" ; 
			$what[] = "counter_party";
			$what[] = "u2.username as counter_party_name";
			$what[] = "trade_date" ; 
			$what[] = "value_date" ; 
			$what[] = "user_id" ; 
			$what[] = "u1.username as user_name" ;

			
			$where = "counter_party > 0 " ;

		
			$this->db->select($what);
			$this->db->from('deals');
			
			if( $type == 1 ) {
				$this->db->join('currency_pairs','currency_pairs.id = ccy_pair','left');  
				$this->db->join('currencies c1','currency_pairs.currency0 = c1.id','left') ;
				$this->db->join('currencies c2','currency_pairs.currency1 = c2.id','left') ;
			}
			else
				$this->db->join('currencies','ccy_pair = currencies.id','left') ;
			
			$this->db->join('periods','periods.id = period','left') ;
			$this->db->join('users u1',' u1.id = user_id','left');
			$this->db->join('users u2', 'u2.id = counter_party','left'); 
			$this->db->where('type',$type);
			$this->db->where('counter_party',$user_id );
			$this->db->where($where);
			$this->db->or_where('user_id',$user_id);
			$this->db->where('type',$type);
			$this->db->where($where);
			$this->db->order_by('trade_date','desc');
			if( $limit) $this->db->limit($limit,0);
			$query = $this->db->get();
			
			return $query->result_array() ;
			
		}
		
		public function get_fx_deals ( $user_id ) {
		
			$clients = $this->get_clients_deals( $user_id, 1, 10 );
			$users   = $this->get_users_deals ( $user_id, 1, 10 ) ;
			
			$db = array() ; 
			
			$c = 0 ;  $c_size = count($clients);
			$u = 0 ;  $u_size = count($users);
			
			for(  $i = 0 ; $i < 10 && $c < $c_size && $u < $u_size ; $i++ ) 
				if( $clients[$c]['trade_date'] > $users[$u]['trade_date'] ) {
					array_push( $db, $clients[$c] ) ;
					$c++;
				}
				else {
				
				 	array_push( $db, $users[$u] ) ;
				 	$u++;
				 }
				 
			while( $i < 10 && $c < $c_size ) {
				array_push( $db, $clients[$c] ) ;
				$c++;
			}
			
			while( $i < 10 && $u < $u_size ) {
				array_push( $db, $users[$u] ) ;
				$u++;
			}
			
			
			$N = count($db) ;
						
			
			for( $i = 0 ; $i < $N ; $i++ ) { 
			 	
			 	$db[$i]['amount_var_ccy'] = round(-$db[$i]['amount_base_ccy'] * $db[$i]['price'],4);
			 	
			 	if ( $user_id != $db[$i]['user_id'] ) {
				

					$aux = $db[$i]['counter_party'] ;
					$db[$i]['counter_party'] = $db[$i]['user_id'] ;
					$db[$i]['user_id'] = $aux;
					
					$aux = $db[$i]['counter_party'] ;
					$db[$i]['counter_party'] = $db[$i]['user_id'] ;
					$db[$i]['user_id'] = $aux;
					
					$db[$i]['amount_base_ccy'] *= (-1) ;
				}
			}
			
			
			return $db ;			
		}

		public function get_mm_deals ( $user_id ) {
		
			$clients = $this->get_clients_deals( $user_id, 2, 10 );
			$users   = $this->get_users_deals ( $user_id, 2, 10 ) ;
			
			$db = array() ; 
			
			$c = 0 ;  $c_size = count($clients);
			$u = 0 ;  $u_size = count($users);
			
			for(  $i = 0 ; $i < 10 && $c < $c_size && $u < $u_size ; $i++ ) 
				if( $clients[$c]['trade_date'] > $users[$u]['trade_date'] ) {
					array_push( $db, $clients[$c] ) ;
					$c++;
				}
				else {
				
				 	array_push( $db, $users[$u] ) ;
				 	$u++;
				 }
			
			while( $i < 10 && $c < $c_size ) {
				array_push( $db, $clients[$c] ) ;
				$c++;
			}
			
			while( $i < 10 && $u < $u_size ) {
				array_push( $db, $users[$u] ) ;
				$u++;
			}
			
			$N = count($db) ;
						
			
			for( $i = 0 ; $i < $N ; $i++ ) {
			
			 	$db[$i]['amount_var_ccy'] = round(-$db[$i]['amount_base_ccy'] * $db[$i]['price'],4);
			 	
				if ( $user_id != $db[$i]['user_id'] ) {
				
					$aux = $db[$i]['counter_party_name'] ;
					$db[$i]['counter_party_name'] = $db[$i]['user_name'] ;
					$db[$i]['user_name'] = $aux;;
					
					$aux = $db[$i]['counter_party'] ;
					$db[$i]['counter_party'] = $db[$i]['user_id'] ;
					$db[$i]['user_id'] = $aux;
					
					$db[$i]['amount_base_ccy'] *= (-1) ;
				}
			}
			
			return $db ;			
		
			
		}
		
		public function get_users_fx_positions ( $user_id ) {
	
			$what = array() ; 
			
			$what[] = "users_fx_positions.amount as position_amount" ;
			$what[] = "sumrate" ;
						
			$this->db->select($what) ;
			$this->db->from('users_fx_positions');
			$this->db->where('users_id',$user_id);
			$this->db->order_by('ccy_pair');
			$query = $this->db->get();
			 
			return $query->result_array() ;
		}
		
		public function get_banks_balances ( $user_id ) {
		
			$what = array() ;
			$what[] = "banks_balances.amount as banks_ccy_amount";
			//$what[] = "currencies.shortname as ccy_name"; 
				
			$this->db->select($what) ;
			$this->db->from('users'); 
			$this->db->join('jobs','jobs.id = jobs_id','left');
			$this->db->join('banks_balances','jobs.banks_id = banks_balances.banks_id','left');
			//$this->db->join('currencies','currencies.id = currencies_id','left');
			$this->db->where('users.id',$user_id);
			$this->db->order_by('currencies_id');
			$query = $this->db->get() ;
			
			return $query->result_array();
		}
		
		public function get_banks_info ( $user_id ) {
			
			$what = array() ; 
			$what[] = "currencies_id" ; 
			$what[] = "capital" ; 
			
			$this->db->select($what) ; 
			$this->db->from('users');
			$this->db->join('jobs','jobs.id = jobs_id','left');
			$this->db->join('banks','jobs.banks_id = banks.id','left');
			$this->db->join('countries','countries.id = banks.countries_id');
			$this->db->where('users.id',$user_id);
			$query = $this->db->get() ;
			
			
			return $query->result_array() ;	
			
		}
		
		public function get_users_fx_pnl ( $user_id ) {
		
			$what = array() ;
			$wha[] = "amount" ;
			
			$this->db->select($what) ; 
			$this->db->from('users_fx_pnl');
			$this->db->where('users_id',$user_id) ;
			$this->db->order_by('currencies_id');
			$query = $this->db->get() ;
			
			return $query->result_array() ;
		}
		
		public function get_users_mm_pnl ( $user_id ) {
		
			$what = array() ;
			$wha[] = "amount" ;
			
			$this->db->select($what) ; 
			$this->db->from('users_mm_pnl');
			$this->db->where('users_id',$user_id) ;
			$this->db->order_by('currencies_id');
			$query = $this->db->get() ;
			
			return $query->result_array() ;
		}
		
		
		public function compute_spot_positions( $user_id ) {
		
			$spot_positions = $this->get_users_fx_positions($user_id);
			

			for( $i = 0 ; $i < 3 ; $i++ ) {  
				$spot_positions[$i]['position_rate'] = $spot_positions[$i]['sumrate'] ;
				if( $spot_positions[$i]['position_rate'] )
					$spot_positions[$i]['position_rate'] /= $spot_positions[$i]['position_amount'] ; 	
						
			}	
		
			return $spot_positions ;
		}
			 	
			 	
		public function compute_banks_capital ( $user_id ) { 
			
			$banks_info = $this->get_banks_info($user_id) ;
			
			$banks_capital  = $banks_info[0]['capital'] ;
			$banks_currency = $banks_info[0]['currencies_id'] - 1 ; 
						 
			if( $banks_currency == 0 ) {

				$capital[0] =  $banks_capital ; 
				$capital[1] =  round( $banks_capital * $this->Game_model->getSettingValue('bot_bprice1'), 4 ) ;
				$capital[2] =  round( $banks_capital / $this->Game_model->getSettingValue('bot_sprice3'), 4 ) ;
			}
			elseif ( $banks_currency == 1 ) {
				$capital[0] =  round( $banks_capital / $this->Game_model->getSettingValue('bot_sprice1'), 4 ) ; 
				$capital[1] =  $banks_capital ;
				$capital[2] =  round( $banks_capital / $this->Game_model->getSettingValue('bot_sprice2'), 4 ) ;
			}
			else {
				$capital[0] =  round( $banks_capital * $this->Game_model->getSettingValue('bot_bprice3'), 4 ) ; 
				$capital[1] =  round( $banks_capital * $this->Game_model->getSettingValue('bot_bprice2'), 4 ) ; 
				$capital[2] =  $banks_capital ;
			}
			
			return $capital ;
		}
		
		public function compute_banks_funds ( $user_id ) {

			$fx_pnl = $this->get_users_fx_pnl($user_id) ;
			$mm_pnl = $this->get_users_mm_pnl($user_id) ;
			$capital = $this->compute_banks_capital ($user_id) ; 		
			
			$pnl[0] = $fx_pnl[0]['amount'] + 
				  $fx_pnl[1]['amount'] * $this->Game_model->getSettingValue('bot_bprice1') +
				  $fx_pnl[2]['amount'] / $this->Game_model->getSettingValue('bot_sprice3') + 
				  $mm_pnl[0]['amount'] + 
				  $mm_pnl[1]['amount'] * $this->Game_model->getSettingValue('bot_bprice1') +
				  $mm_pnl[2]['amount'] / $this->Game_model->getSettingValue('bot_sprice3') + 
				  
			$pnl[1] = $fx_pnl[0]['amount'] / $this->Game_model->getSettingValue('bot_sprice1') +  
				  $fx_pnl[1]['amount'] +
				  $fx_pnl[2]['amount'] / $this->Game_model->getSettingValue('bot_sprice2') + 
				  $mm_pnl[0]['amount'] / $this->Game_model->getSettingValue('bot_sprice1') +  
				  $mm_pnl[1]['amount'] +
				  $mm_pnl[2]['amount'] / $this->Game_model->getSettingValue('bot_sprice2') + 
			
			
			$pnl[2] = $fx_pnl[0]['amount'] * $this->Game_model->getSettingValue('bot_bprice3') + 
				  $fx_pnl[1]['amount'] * $this->Game_model->getSettingValue('bot_bprice2') +
				  $fx_pnl[2]['amount'] + 
				  $mm_pnl[0]['amount'] * $this->Game_model->getSettingValue('bot_bprice3') +  
				  $mm_pnl[1]['amount'] * $this->Game_model->getSettingValue('bot_bprice2') +
				  $mm_pnl[2]['amount'] ; 
			
				  
			for( $i = 0 ; $i < 3 ; $i++ )
				$funds[$i] = $capital[$i] + $pnl[$i] ; 
				
			return $funds ;
		}
	
		
		public function compute_fx_positions( $user_id ) {
			
		
			$spot_positions = $this->compute_spot_positions($user_id) ;
			$funds = $this->compute_banks_funds($user_id);
			$banks_info = $this->get_banks_info($user_id) ;
			
			$banks_currency = $banks_info[0]['currencies_id'] - 1 ; 
			
							
			///////////////////// hardcodat ///////////////////////////
			$percentage = (double)8 / 100 ;
			////////////////////////////////////////////////////////////

			$fx_positions[0]['ccy_name'] = "TER";
			$fx_positions[1]['ccy_name'] = "RIK";
			$fx_positions[2]['ccy_name'] = "HAT";
									
		
		
		//////////////////////////////////      AMOUNT     ///////////////////////////////////////
			
			$fx_positions[0]['amount'] =  $spot_positions[0]['position_amount'] - 
					              $spot_positions[2]['position_amount'] * 
						      $spot_positions[2]['position_rate']   ; 
		
			$fx_positions[1]['amount'] = -$spot_positions[0]['position_amount'] *
						      $spot_positions[0]['position_rate']   - 
					              $spot_positions[1]['position_amount'] * 
						      $spot_positions[1]['position_rate']   ; 
			

			$fx_positions[2]['amount'] =  $spot_positions[1]['position_amount'] + 
						      $spot_positions[2]['position_amount'] ; 
		
									     
		/////////////////////////////////////////   REPORTING CURRENCY       //////////////////////////////////////////////

			$bank_buys_ter_rik  = $this->Game_model->getSettingValue('bot_bprice1') ; 
			$bank_sells_ter_rik = $this->Game_model->getSettingValue('bot_sprice1') ; 
			$bank_buys_hat_rik  = $this->Game_model->getSettingValue('bot_bprice2') ;
			$bank_sells_hat_rik = $this->Game_model->getSettingValue('bot_sprice2') ;  
			$bank_buys_hat_ter  = $this->Game_model->getSettingValue('bot_bprice3') ;
			$bank_sells_hat_ter = $this->Game_model->getSettingValue('bot_sprice3') ;  

			$user_buys_ter_rik  = $spot_positions[0]['position_rate'] ;
			$user_sells_ter_rik = $spot_positions[0]['position_rate'] ;	
			$user_buys_hat_rik  = $spot_positions[1]['position_rate'] ;
			$user_sells_hat_rik = $spot_positions[1]['position_rate'] ;	
			$user_buys_hat_ter  = $spot_positions[2]['position_rate'] ;
			$user_sells_hat_ter = $spot_positions[2]['position_rate'] ;	


			if( !$user_buys_ter_rik  ) $user_buys_ter_rik  = $bank_sells_ter_rik ; 
			if( !$user_sells_ter_rik ) $user_sells_ter_rik = $bank_buys_ter_rik  ;
			if( !$user_buys_hat_rik  ) $user_buys_hat_rik  = $bank_sells_hat_rik ;
			if( !$user_sells_hat_rik ) $user_sells_hat_rik = $bank_buys_hat_rik  ;
			if( !$user_buys_hat_ter  ) $user_buys_hat_ter  = $bank_sells_hat_ter ;
			if( !$user_sells_hat_ter ) $user_sells_hat_ter = $bank_buys_hat_ter  ;

		
			$fx_positions[0]['rep_ccy'][0] =   $fx_positions[0]['amount'] ; 
			$fx_positions[0]['rep_ccy'][1] =   $spot_positions[0]['position_amount'] * $user_sells_ter_rik     
						     	  -$spot_positions[2]['position_amount'] * $user_sells_hat_ter * $bank_buys_ter_rik ; 
			$fx_positions[0]['rep_ccy'][2] =   $spot_positions[0]['position_amount'] / $user_buys_hat_ter 
							  +$spot_positions[2]['position_amount'] ;
								   
	
			$fx_positions[1]['rep_ccy'][0] =  -$spot_positions[0]['position_amount'] * $user_sells_ter_rik / $bank_sells_ter_rik 
							  -$spot_positions[1]['position_amount'] * $user_sells_hat_rik / $bank_sells_ter_rik ;
			$fx_positions[1]['rep_ccy'][1] =   $fx_positions[1]['amount'] ;
			$fx_positions[1]['rep_ccy'][2] =  -$spot_positions[0]['position_amount'] * $user_sells_ter_rik / $user_buys_hat_rik
						          -$spot_positions[1]['position_amount'] ;

			$fx_positions[2]['rep_ccy'][0] =   $spot_positions[1]['position_amount'] * $user_sells_hat_rik / $bank_sells_ter_rik  
							  +$spot_positions[2]['position_amount'] * $user_sells_hat_ter ; 
			$fx_positions[2]['rep_ccy'][1] =   $spot_positions[1]['position_amount'] * $user_sells_hat_rik
							  +$spot_positions[2]['position_amount'] * $user_sells_hat_ter * $bank_buys_ter_rik ; 
			$fx_positions[2]['rep_ccy'][2] =   $fx_positions[2]['amount'] ; 
           
            
            
            		////////////////////////////////////// POSITION LIMIT /  RATE /   RISK    /////////////////////////////////////
	
			for( $i = 0 ; $i < 3 ; $i++ ) {
				
				for( $j = 0 ; $j < 3 ; $j++ ) {
					
					$fx_positions[$i]['rep_ccy'][$j] = round( $fx_positions[$i]['rep_ccy'][$j], 4 ) ;

					if( abs($fx_positions[$i]['rep_ccy'][$j]) > abs($fx_positions[$i]['amount']) )
@						$fx_positions[$i]['rate'][$j] = round($fx_positions[$i]['rep_ccy'][$j] / $fx_positions[$i]['amount'],4) ;  
					else
@						$fx_positions[$i]['rate'][$j] = round($fx_positions[$i]['amount'] / $fx_positions[$i]['rep_ccy'][$j],4) ; 
						 				
					$fx_positions[$i]['limit'][$j] = round($percentage * $funds[$j],4) ; 
					$fx_positions[$i]['risk'][$j] = "IN LIMIT" ;
				
					if( $j != $banks_currency && abs($fx_positions[$i]['rep_ccy'][$j]) > $fx_positions[$i]['limit'][$j] )
						$fx_positions[$i]['risk'][$j] = "BREAK" ;
				}	
			}	
			
			return $fx_positions ;				 			
		}

		public function compute_agg ( $user_id ) {
		
			
			$fx_positions = $this->compute_fx_positions($user_id) ;
			
			
			for( $j = 0 ; $j < 3 ; $j++ ) {
				
				$agg['rep_ccy'][$j] = 0 ;
				$agg['risk'] = "IN LIMIT" ;
				
				for( $i = 0 ; $i < 3 ; $i++ ) {
				
					$agg['limit'][$j] = $fx_positions[$i]['limit'][$j] ;
				
					if( abs($fx_positions[$i]['rep_ccy'][$j]) > abs($agg['rep_ccy'][$j]) ) {
						$agg['rep_ccy'][$j] = $fx_positions[$i]['rep_ccy'][$j] ;
						$agg['risk'][$j] = $fx_positions[$i]['risk'][$j] ; 
					}
				}
			} 	
			
			return $agg ;
		}
		
		
		public function get_blotters ( $user_id ) {
			
			$blotters['spot_positions'] = $this->compute_spot_positions($user_id);
			
			$blotters['banks_balances'] = $this->get_banks_balances($user_id);
			
			$blotters['capital'] = $this->compute_banks_capital($user_id);
			$blotters['funds'] = $this->compute_banks_funds($user_id);
			
			$blotters['fx_positions'] = $this->compute_fx_positions($user_id);
			$blotters['agg'] = $this->compute_agg($user_id);
			
			$blotters["fx_deals"] = $this->get_fx_deals($user_id);
			$blotters["mm_deals"] = $this->get_mm_deals($user_id);
			
			
			return $blotters ;
		
		}			
	}
	
