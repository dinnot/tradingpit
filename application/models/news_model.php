<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class News_model extends CI_Model {
		
		public function __construct() {
			
			$this->load->database();

		    	parent::__construct();

		}
	
		public function get_news() {
			
			$day = 24*3600 ;
			$current_date = time() ;
			$last_date = $current_date - 7*$day ; 
									
			$what = array() ;
			 
			$what[] = "date" ; 
			$what[] = "headline" ; 
			$what[] = "body" ; 
			$what[] = "countries_id" ;
			$what[] = "countries.name as country_name" ; 			  
			
			$this->db->select($what);
			$this->db->from("news");
			$this->db->join("countries"," countries.id = countries_id ","left") ;
			
			$this->apply_body_filter() ;
			$this->apply_country_filter() ;
			
			$this->db->where("date >=",$last_date) ;
			$this->db->where("date <=",$current_date) ;
			$this->db->order_by("date","desc");
			$query = $this->db->get() ; 
			return $query->result_array();
		}
		
		public function apply_body_filter() {
			
			$body_filter = $this->input->get_post("body_filter");
			
			if( $body_filter ) 
				$this->db->like('body',$body_filter);
		}
		
		public function apply_country_filter() {
			
			
			$country_filter = $this->input->get_post("country_filter");
			
			if( $country_filter != 0 ) 
				$this->db->where("countries.id",$country_filter);
		}
		
		public function insert_news() {
		
			$day = 24 * 3600 ;
			$start_date = 1353369600 ;  // 20.11.2012  00 : 00 : 00  
	
		
			$myFile = "application/controllers/news/news_feed.txt" ;
			$fh = fopen($myFile,'r') ;
			$content = fread($fh,filesize($myFile)) ;
			fclose($fh) ;
			$data = explode("\n",$content) ;
	
	
			$N = count($data) - 1 ;
			
			$row = array() ;
			for( $i = 0 ; $i < $N ; $i += 3 ) {
		
				$date =  (int)substr($data[$i],4)  ; 
				$date = $start_date + $date * $day ;	
					
				$row['date'] = $date ;
				$row['headline'] = mysql_real_escape_string($data[$i+1]) ;
				$row['body'] =  mysql_real_escape_string($data[$i+2]) ;
				$row['countries_id'] = 5 ; 
		
				$this->db->insert('news',$row) ;
				
			}
		}
		
		public function update_news() {
		
			$day = 24 * 3600 ;
			$start_date = 1353369600 ;  // 20.11.2012  00 : 00 : 00 
			$days = -30 ; 
			
			$add = $days * $day ;
			
			$this->db->set('date', "date + '$add' " , FALSE);
			$this->db->update('news');
		}
	}
	
?>

