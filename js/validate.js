function validate_retail_bf ( bf ) {

	bf = bf.toString() ;
	
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


function validate_deal_bf( bf ) {

	bf = bf.toString() ;	
	
	if( bf.length > 4 ) { 
		alert("PIPS incorect!") ; 
		return false ; 
	}
	
	while( bf.length < 4 ) 	
		bf += "0" ; 

	if( bf[1] != '.' ) {
		alert("BF incorect!") ;
		return false ; 
	}

	if( bf[0] > '9' || bf[0] < '0' || bf[2] > '9' || bf[2] < '0' || bf[3] > '9' || bf[3] < '0' ) {
		alert("BF incorect!") ;
		return false ; 
	}
	
	return true ; 
}



function validate_retail_pips ( pips ) {

	price = price.toString ();
		
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

function validate_deal_pips ( pips ) {
	
	pips = pips.toString() ; 
	
	if( pips.length > 2 ) {
		alert("PIPS incorect!") ; 
		return false ; 
	}
	
	while( pips.length < 2 ) 
		pips += "0" ;
		
	for( var i = 0 ; i < 2 ; i++ ) 
		if( pips[i] > '9' || pips[i] < '0' ) {
			alert("PIPS incorect!") ; 
			return false ; 
		}
		
	return true ;

}

function validate_price ( price ) {
	
	price = price.toString ();
	
	if( price.length == 1 ) 
		price += ".0000";
	
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
	
	for( var i = 0 ; i < 6 ; i++ ) {
		if( i == 1 ) continue ; 
		if( price[i] > '9' || price[i] < '0' ) {
			alert("Incorect PRICE !") ;
			return false ;
		}
	}
	
	return true ;
}		


function validate_pair_id ( pair_id ) { 
	return true ;	
	if( pair_id < 1 || pair_id > 3 ) {
		alert("Invalid currency pair !") ;
		return false ;
	}
	
	return true ;
}

			
