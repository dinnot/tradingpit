
function validate_bf ( bf ) {

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

function validate_pips ( pips ) {
	
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


function validate_price ( price ) {
	
	if( price.length != 6 ) {
		alert("PRICE incorect!") ; 
		return false ; 
	}
	
	if( price[1] != '.' ) { 
		alert("PRICE incorect!") ; 
		return false ;
	}
	
	for( var i = 0 ; i < 6 ; i++ ) {
		if( i == 1 ) continue ; 
		if( price[i] > '9' || price[i] < '0' ) {
			alert("PRICE incorect!") ;
			return false ;
		}
	}
	
	return true ;
}		


function validate_pair_id ( pair_id ) { 
	
	if( pair_id < 1 || pair_id > 3 ) 
		return false ;
	return true ;
}

			
		



