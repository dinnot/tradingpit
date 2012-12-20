
var flow_positions_class = function() { 

	
	this.name = 'flow_positions';

	this.delay = 1000;
	this.timeout = 1000;
	
	this.pull = new Object ();
	this.pull['get_flow_positions'] = 0; 

}


flow_positions_class.prototype.update = function(data) { 
	
	display_flow_positions( data['get_flow_positions']['flow_positions'] ) ;
	display_fx_total( data['get_flow_positions']['fx_total'] ) ; 
}


function display_flow_positions( flow ) { 

	$("#deals").text('') ; 
	
	for( var i = 0 ; i < flow.length ; i++ ) {
	
		var timestamp = flow[i]['value_date'] *1000 ; 
		date1 = new Date(timestamp).toString('dd.mm.yy');
		date2 = new Date(timestamp).toString('H:MM');


		$("#deals").append( 
			'<tr>' +
			'<td>' + flow[i]['counter_party_name'] + '</td>' +  
			'<td>' + date1 + '</td>' + 	
			'<td>' + date2 + '</td>' + 	
			'<td> ' + flow[i]['user_name'] +
			'<td>' + flow[i]['first_currency'] + '/' + flow[i]['second_currency'] + '</td>' + 
			'<td>' + flow[i]['period_name'] + '</td>' + 
			'<td>' + flow[i]['price'] + '</td>' + 
			'<td>' + flow[i]['amount_base_ccy'] + '</td>' + 
			'</tr>'
		
		) ; 
	
	}
}

function display_fx_total ( fx_total ) {

	var pair_id = 1 ;
	if( $('#pair_1').is(":hidden") ) 
		pair_id = 2 ;
		
	$("#total_fx").text( fx_total[pair_id] ) ;
	
}
	

var flow_positions = new flow_positions_class() ;
Observable.subscribe(flow_positions);
