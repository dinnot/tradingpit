<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class News_model extends CI_Model {
		
		public function __construct() {
			
			$this->load->database();

		    	parent::__construct();

		}
	
		public function get_news() {
			
			$date = time() - 24*3600*3 ; 
			
			
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
			
			$this->db->where("date >=",$date) ;
			$this->db->order_by("date","desc");
			$query = $this->db->get() ; 
			return $query->result_array();
		}
		
		public function apply_body_filter() {
			$body_filter = $this->input->get_post ("body_filter");
			
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
			$start_date = time() - 5*$day ; 
	
		
			$myFile = "/var/www/news_feed/news_feed.txt" ;
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
			$start_date = time() - 5*$day ; 
	
			$this->db->set('date', 'date + 3 * 24 * 3600 ' , FALSE);
			$this->db->update('news');
		}
	}
	
?>

