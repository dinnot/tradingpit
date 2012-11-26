<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Blotters_model extends CI_Model {
	
		public function __construct () {
			
			$this->load->database() ;  
		}
		
		
		public function get_clients_deals ( $user_id, $type ) {
		
			
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
			$this->db->limit(10,0);
			$query = $this->db->get() ;
		
			return $query->result_array() ;
			
		}
		
		public function get_users_deals ( $user_id, $type ) {
		
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
			$this->db->join('users u1','u1.id = user_id','left');
			$this->db->join('users u2', 'u2.id = counter_party','left'); 
			$this->db->where('type',$type);
			$this->db->where('counter_party',$user_id );
			$this->db->where($where);
			$this->db->or_where('user_id',$user_id);
			$this->db->where('type',$type);
			$this->db->where($where);
			$this->db->order_by('trade_date','desc');
			$this->db->limit(10,0);
			$query = $this->db->get();
			
			return $query->result_array() ;
			
		}
		
		public function get_fx_deals ( $user_id ) {
		
			$clients = $this->get_clients_deals( $user_id, 1 );
			$users   = $this->get_users_deals ( $user_id, 1 ) ;
			
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
			
			
			return $db ;			
		}

		public function get_mm_deals ( $user_id ) {
		
			$clients = $this->get_clients_deals( $user_id, 2 );
			$users   = $this->get_users_deals ( $user_id, 2 ) ;
			
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
			$what[] = "currencies.shortname as ccy_name"; 
				
			$this->db->select($what) ;
			$this->db->from('users'); 
			$this->db->join('jobs','jobs.id = jobs_id','left');
			$this->db->join('banks_balances','jobs.banks_id = banks_balances.banks_id','left');
			$this->db->join('currencies','currencies.id = currencies_id','left');
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
			 	
	}
	
