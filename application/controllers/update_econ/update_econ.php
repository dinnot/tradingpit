<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_econ extends CI_Controller {
	
	public function __construct () {
		
		parent::__construct ();
				
    $this->load->database();
	}
	
	public function index () {
  	include 'PHPExcel/IOFactory.php';
	
		$inputFileName = '/var/www/tradingpit/econ_ind.xlsx';
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		$sheetData = $objPHPExcel->getActiveSheet()->toArray();
	
		$colums = array ();
		$num_colums = 0;
		array_push ($colums, 'day');
		foreach ($sheetData[0] as $item) {
			if ($item == '') continue;
			print_r ($item);
			print '<br />';
			array_push ($colums, $item);
			$num_colums++;
		}
		
		$this->db->from ('econlevels');
		$query = $this->db->get ();
		$results = $query->result_array ();
		$levels = array ();
		foreach ($results as $item) {
			$levels[$item['name']] = $item['id'];
		}
		
		print_r ($levels);
		print '<hr />';
		$i = -1;
		foreach ($sheetData as $item ) {
			$i++;
			if ($i == 0) continue;
			$col = 3;
			for ($cout = 0; $cout < 3; $cout++) {
				$econ = array ();
				for ($i = 3; $i <= 12; $i++, $col++) 
					$econ[ $colums[$i] ] = $item[$col];
				print_r ($econ);
				print '<hr />';
			}
		}

  }
   
};

