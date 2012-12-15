<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  class Validate_model extends CI_Model {

		function __construct () {
			parent::__construct();
		}
		
		
		function validate_price ( $price ) {
		
		 if( $price < 0 || $price > 9.9999 ) 
		 	return false ;
		 
		 return true ;
		}
		
		function validate_pair_id ( $pair_id ) {
		
			if( $pair_id < 1  || $pair_id > 3 ) 
				return false ;
				
			return true ;
		}
		
  }
  
  
  
