var blotters_class = function () { 

	this.name = 'blotters' ; 
	this.delay = 100 ; 
	this.timeout = 3000 ; 
	
	this.pull = Object() ; 
	this.pull['get_blotters'] = 0 ; 
	
}


blotters_class.prototype.update = function( data ) {
	display_blotters( data['get_blotters'] ) ;
}	 




$("#TER").click( function () {
				$(".hide").hide();
				$(".TER").show(); 
				$(this).addClass('green');
				$("#RIK").removeClass('green');
				$("#HAT").removeClass('green');
				
		});
	
			
$("#HAT").click( function () {
				$(".hide").hide();
				$(".HAT").show(); 
				$(this).addClass('green');
				$("#TER").removeClass('green');
				$("#RIK").removeClass('green');
				
		});


$("#RIK").click( function () {

				$(".hide").hide();
				$(".RIK").show(); 
				$(this).addClass('green');
				$("#TER").removeClass('green');
				$("#HAT").removeClass('green');
				
		});
		
		
		
		
function get_blotters() {

	var url = base_url + "trading/blotters/get_blotters" ; 
	
	$.ajax({
		action: 'POST',
      		url: url,
     		dataType: 'json',
      
      		success: function (data,textStatus, jqXHR) {                    
				console.log (data);    		
    			        display_blotters (data);
			 }, 
	  
	        error: function(XMLHttpRequest, textStatus, errorThrown) {
        			console.log(textStatus, errorThrown);
      			}

	});
}


function display_blotters ( data ) {

	display_spot_positions( data['spot_positions'] ) ; 
	display_fx_positions( data['fx_positions'] ) ; 
	display_agg( data['agg'] ) ; 
	display_tier1_capital( data['capital'] ) ;
	display_tier1_funds( data['funds'] ) ;
	display_acb( data['banks_balances'] ) ; 
	display_fx_deals( data['fx_deals'] ) ; 
	display_mm_deals( data['mm_deals'] ) ;

}

function display_spot_positions( spot_positions ) { 

	$("#TRPA").text( parseFloat (spot_positions[0]['position_amount']).toFixed(4) ) ;
	$("#TRPR").text( parseFloat (spot_positions[0]['position_rate']).toFixed(4) );

	$("#HRPA").text( parseFloat (spot_positions[1]['position_amount']).toFixed(4) ) ;
	$("#HRPR").text( parseFloat (spot_positions[1]['position_rate']).toFixed(4) ) ;

	$("#HTPA").text( parseFloat (spot_positions[2]['position_amount']).toFixed(4) ) ;
	$("#HTPR").text( parseFloat (spot_positions[2]['position_rate']).toFixed(4) ) ;

}

function display_fx_positions ( fx_positions ) {

	for( var i = 0 ; i < 3 ; i++ ) {
	
		$("#PCN"+i).text( fx_positions[i]['ccy_name'] ) ;
		$("#PAM"+i).text( fx_positions[i]['amount'] ) ;
		
		$("#PRC"+i+"0").text( fx_positions[i]['rep_ccy'][0] ) ;
		$("#PRC"+i+"1").text( fx_positions[i]['rep_ccy'][1] ) ; 
		$("#PRC"+i+"2").text( fx_positions[i]['rep_ccy'][2] ) ;
	
		$("#PL"+i+"0").text( fx_positions[i]['limit'][0] ) ;
		$("#PL"+i+"1").text( fx_positions[i]['limit'][1] ) ; 
		$("#PL"+i+"2").text( fx_positions[i]['limit'][2] ) ;
	
		$("#PRT"+i+"0").text( fx_positions[i]['rate'][0] ) ;
		$("#PRT"+i+"1").text( fx_positions[i]['rate'][1] ) ; 
		$("#PRT"+i+"2").text( fx_positions[i]['rate'][2] ) ;
		
		$("#PRK"+i+"0").text( fx_positions[i]['risk'][0] ) ;
		$("#PRK"+i+"1").text( fx_positions[i]['risk'][1] ) ; 
		$("#PRK"+i+"2").text( fx_positions[i]['risk'][2] ) ;
	
	
	}
	
}

function display_agg( agg ) {

	$("#ARC0").text( agg['rep_ccy'][0] ) ;
	$("#ARC1").text( agg['rep_ccy'][1] ) ;
	$("#ARC2").text( agg['rep_ccy'][2] ) ;
	
	$("#AL0").text( agg['limit'][0] ) ;	
	$("#AL1").text( agg['limit'][1] ) ;	
	$("#AL2").text( agg['limit'][2] ) ;
	
	$("#ARK0").text( agg['risk'][0] ) ;		
	$("#ARK1").text( agg['risk'][1] ) ;		
	$("#ARK2").text( agg['risk'][2] ) ;		

}

function display_tier1_capital ( capital ) {

	$("#TERCAP").text( capital[0] ) ;
	$("#RIKCAP").text( capital[1] ) ;
 	$("#HATCAP").text( capital[2] ) ;

}

function display_tier1_funds ( capital ) {

	$("#TERFUND").text( capital[0] ) ;
	$("#RIKFUND").text( capital[1] ) ;
 	$("#HATFUND").text( capital[2] ) ;

}

function display_acb( banks_balances ) {
	
	$("TERACB").text( banks_balances[0]['banks_ccy_amount'] ) ;
	$("RIKACB").text( banks_balances[1]['banks_ccy_amount'] ) ;
	$("HATACB").text( banks_balances[2]['banks_ccy_amount'] ) ;	
	
	$("#TEROVD").removeClass("overdraft") ;
	$("#TEROVD").text("") ;
	
	$("#RIKOVD").removeClass("overdraft") ;
	$("#RIKOVD").text("") ;
	
	$("#HATOVD").removeClass("overdraft") ; 
	$("#HATOVD").text("") ;
	
	if( banks_balances[0]['banks_ccy_amount'] < 0 ) {
		$("#TEROVD").addClass("overdraft") ;
		$("#TEROVD").text("OVERDRAFT") ; 
		$("#TERGR").addClass("green-td");
	}
	
	if( banks_balances[1]['banks_ccy_amount'] < 0 ) {
		$("#RIKOVD").addClass("overdraft") ;
		$("#RIKOVD").text("OVERDRAFT") ;
		$("#RIKGR").addClass("green-td");
	}
	
	if( banks_balances[2]['banks_ccy_amount'] < 0 ) {
		$("#HATOVD").addClass("overdraft") ;
		$("#HATOVD").text("OVERDRAFT") ;
		$("#HATGR").addClass("green-td");
	}			

}

function display_fx_deals ( fx_deals ) {
	
	for( var i = 0 ; i < fx_deals.length ; i++ ) {
		
			
		var timestamp = fx_deals[i]['value_date'] * 1000;
		date1 = new Date(timestamp).toString('dd/MMM');	
		timestamp = fx_deals[i]['trade_date'] * 1000;
		date2 = new Date(timestamp).toString('dd/MMM');	
		
			
		$("FX"+i+"0").text( fx_deals[i]['period_name'] ) ; 	
		$("FX"+i+"1").text( fx_deals[i]['ccy_name'] ) ; 					
		$("FX"+i+"2").text( fx_deals[i]['amount_base_ccy'] ) ; 					
		$("FX"+i+"3").text( fx_deals[i]['price'] ) ; 					
		$("FX"+i+"4").text( Math.abs(fx_deals[i]['amount_base_ccy'] * fx_deals[i]['price']) ) ; 					
		$("FX"+i+"5").text( fx_deals[i]['counter_party_name'] ) ; 					
		$("FX"+i+"6").text( date1 ) ; 					
		$("FX"+i+"7").text( date2 ) ; 					
		$("FX"+i+"8").text( fx_deals[i]['deal_id'] ) ; 								
		$("FX"+i+"9").text( fx_deals[i]['user_name'] ) ; 										
		
	}
}

function display_mm_deals( mm_deals ) {

	for( var i = 0 ; i < mm_deals.length ; i++ ) {
		
		var timestamp = mm_deals[i]['value_date'] * 1000;
		date1 = new Date(timestamp).toString('dd/MMM');	
		timestamp = mm_deals[i]['trade_date'] * 1000;
		date2 = new Date(timestamp).toString('dd/MMM');	
		
		$("MM"+i+"0").text( mm_deals[i]['period_name'] ) ; 	
		$("MM"+i+"1").text( mm_deals[i]['ccy_name'] ) ; 					
		$("MM"+i+"2").text( mm_deals[i]['amount_base_ccy'] ) ; 					
		$("MM"+i+"3").text( mm_deals[i]['price'] ) ; 					
		$("MM"+i+"4").text( Math.abs(mm_deals[i]['amount_base_ccy'] * mm_deals[i]['price']) ) ; 					
		$("MM"+i+"5").text( mm_deals[i]['counter_party_name'] ) ; 					
		$("MM"+i+"6").text( date1 ) ; 					
		$("MM"+i+"7").text( date2 ) ; 					
		$("MM"+i+"8").text( mm_deals[i]['deal_id'] ) ; 								
		$("MM"+i+"9").text( mm_deals[i]['user_name'] ) ; 										
		
	}
}


blotters = new blotters_class ();

//get_blotters();
Observable.subscribe (blotters);
