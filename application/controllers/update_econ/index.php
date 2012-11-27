<?php
	include 'PHPExcel/IOFactory.php';
	
	$inputFileName = 'econ.xlsx';
	$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray();
	
	$colums = array ();
	$num_colums = 0;
	foreach ($sheetData[0] as $item) {
		if ($item == '') break;
		array_push ($colums, $item);
		$num_colums++;
	}
	
	$i = -1;
	foreach ($sheetData as $item ) {
		$i++;
		if ($i == 0) continue;
		$col = 3;
		for ($cout = 0; $cout < 3; $cout++) {
			$econ = array ();
			for ($i = 3; $i < 12; $i++, $col++) 
				$econ[ $colums[$i] ] = $item[$col];
			print_r ($econ);
			print '<hr />';
		}
	}

?>
