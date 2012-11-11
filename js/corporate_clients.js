var timer_queue = new Array ();
current_offers = new Object ();

function send (id) {
	
	url = base_url+'index.php/clients/set_quote';
	dataIn = {
		user_id: user_id,
		offer_id : id,
		quote : $('#input_quote_'+id).val ()
	}
	
	$.ajax({
	type: 'POST',
	url: url,
	data: dataIn,
	async: true,
	dataType: 'json',
	success: function (data, textStatus, jqXHR) {                    
		action = '';
		$('#quote_'+id).text ($('#input_quote_'+id).val ());
		$('#action_'+id).text (' ');		
		$('#result_'+id).text ('pending');
	}, 
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus, errorThrown);
	}

	});

}

function update_status (id, status) {

	console.log ('status');
	console.log (status);
	current_offers[id]['status'] = status;

	if (status == 1) result = 'pending';
	if (status == 2) result = 'accepted';
	if (status == 3) result = 'no deal';
	
	$("#result_"+id).text (result);
}

function display_offer (data) {
		
	current_offers[data['offer_id']] = {'status': data['status']};	
			
			
	var status = data['status'];
	if (status == 0) {		
		quote = '<input id="input_quote_'+data['offer_id']+'" value=0.00 />';		
		action = '<button onclick=send('+data['offer_id']+')>send</button>';
	}
	else {
		quote = data['quote'];
		action = '';
	}
	
	result = '';
	if (status == 1) result = 'pending';
	if (status == 2) result = 'accepted';
	if (status == 3) result = 'no deal';
	
	var id = data['offer_id'];
	$("#corporate_clients").append (
		'<tr id="offer_'+data['offer_id']+'">'+
		'<td>CLNT</td>'+
		'<td id="name_'+id+'">'+data['name']+'</td>'+
		'<td id="market_'+id+'">'+data['market']+'</td>'+
		'<td id="amount_'+id+'">'+data['amount']+'</td>'+
		'<td id="ccy_'+id+'">'+data['first_ccy']+'/'+data['second_ccy']+'</td>'+
		'<td id="deal_'+id+'">'+data['deal']+'</td>'+
		'<td id="period_'+id+'">'+data['period_id']+'</td>'+		
		'<td id="quote_'+id+'">'+quote+'</td>'+
		'<td id="action_'+id+'">'+action+'</td>'+
		'<td id="timer_'+id+'">0</td>'+		
		'<td id="result_'+id+'">'+result+'</td>'+
		'</tr>'
	);
		
	if (data['status'] <= 1) {
		timer_queue.push ( {'id': data['offer_id'], 'date': data['display_date'], 'status' : data['status']} );
		timer ();
	}
}

function get_clients_offers () {
	
	url = base_url+'index.php/clients/get_new_clients_offers';
	dataIn = {
		user_id: user_id,
		time: new Date().getTime ()
	}

	$.ajax({
      type: 'POST',
      url: url,
      data: dataIn,
      async: true,
      dataType: 'json',
      success: function (data, textStatus, jqXHR) {                    
      	console.log (data);
      	for (var i = 0; i < data.length; i++) {
      		if ( !current_offers[data[i]['offer_id']] ) display_offer (data[i]);						
      		else if ( current_offers[ data[i]['offer_id'] ]['status'] != data[i]['status'] )
      			update_status ( data[i]['offer_id'], data[i]['status'] );
      	}
			}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      },      
		});
}

var seconds = 60;
function timer () {

	if (timer_queue.length >= 0) {	
		time = parseInt (new Date ().getTime () / 1000);
		for (i in timer_queue) {
			var id = timer_queue[i]['id'];
			var rem = seconds - (time - timer_queue[i]['date']);
			if (rem < 0)
				rem = 0;
				
			$('#timer_'+id).text ( rem );
			if (rem  == 0) {			
				  if (timer_queue[i]['status'] == 0) send (id);				  				  
				  delete timer_queue[i];
			}
		}
	}
}

setInterval (timer , 1000);
setInterval (get_clients_offers, 10000);
