function validate_retail_bf ( bf ) { return true;

	if( bf.length != 3 ) {
		alert("BF incorect!") ; 
		return false ; 
	}
	
	if( bf[1] != '.' ) {
		alert("BF incorect!") ;
		return false ; 
	}

	if( bf[0] > '9' || bf[0] < '0' || bf[2] > '9' || bf[2] < '0'  ) {
		alert("BF incorect!") ;
		return false ; 
	}
	
	return true ;
}

function validate_retail_pips ( pips ) { return true;
	
	if( pips.length != 3 ) {
		alert("PIPS incorect!") ; 
		return false ; 
	}
	
	for( var i = 0 ; i < 3 ; i++ ) 
		if( pips[i] > '9' || pips[i] < '0' ) {
			alert("PIPS incorect!") ; 
			return false ; 
		}

	return true ; 
}


function validate_price ( price ) { return true;
	
	if( price.length > 6 ) {
		alert("Incorect PRICE !") ; 
		return false ; 
	}
	
	while( price.length < 6 ) 
		price += "0" ;
	
	if( price[1] != '.' ) { 
		alert("Incorect PRICE!") ; 
		return false ;
	}
	
	for( var i = 0 ; i < 6 ; i++ ) { return true;
		if( i == 1 ) continue ; 
		if( price[i] > '9' || price[i] < '0' ) {
			alert("Incorect PRICE !") ;
			return false ;
		}
	}
	
	return true ;
}		


function validate_pair_id ( pair_id ) {  return true;
	return true ;	
	if( pair_id < 1 || pair_id > 3 ) {
		alert("Invalid currency pair !") ;
		return false ;
	}
	
	return true ;
}

			
		



