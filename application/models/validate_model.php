<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Validate_model extends CI_Model {

		function __construct () {
			parent::__construct();
			
		}
		
		
		function validate_price ( $price ) {
		
			 if( $price < 0 || $price > 9.9999 ) {
			 
			 	$error_code = 1 ; 
			 	$this->load->view("validation_errors", array("error_message" => $this->get_message_error($error_code) ) ) ;
			 	return false ;
			 }
		 
			 return true ;
		}
		
		function validate_pair_id ( $pair_id ) {
		
			if( $pair_id < 1  || $pair_id > 3 ) {
				
				$error_code = 2 ; 
			 	$this->load->view("validation_errors", array("error_message" => $this->get_message_error($error_code) ) ) ;
			 	return false ;
			 }
				
			return true ;
		}
		
		function validate_users_offer( $user_id, $offer_id ) {
		
			$this->db->from("users_has_corporate_offers");
			$this->db->where( array( "offer_id" => $offer_id , "user_id" => $user_id ) )  ;
			$cnt = $this->db->count_all_results(); ;
			
			if( $cnt == 0 ) {
		
				$error_code = 3 ; 
			 	$this->load->view("validation_errors", array("error_message" => $this->get_message_error($error_code) ) ) ;
				return false ;
			}
			
			return true ;
		
		}
		
		function get_message_error ( $error_code ) {
		
			switch ( $error_code ) {
			
			case 1 :  return "Incorect price!" ; 
			case 2 :  return "Incorect currency pair!" ; 
			case 3 :  return "Invalid offer!" ; 
			
			}
		}
		
  }
  
  
  
