var time_difference;
	
function set_time_difference () {
	
	$.ajax({
	url: base_url+'trading/clients/get_time',
	dataType: 'json',
	success: function (data, textStatus, jqXHR) {                    
		time_difference = data['server_time'] - (new Date().getTime () / 1000);	
	}, 
	
	});
}

var timer_queue = new Array ();
current_offers = new Object ();
current_status = new Object ();

function get_status (status, id) {
	if (status == 0) 
		return '<a href="#" onClick="send('+id+')" class="pending">SEND<span class="counter" id="counter_'+id+'">0</span><span class="status">PENDING</span></a>';
	if (status == 1)
		return '<a href="#" class="pending">SENT<span class="counter" id="counter_'+id+'">0</span><span class="status">PENDING</span></a>';
	if (status == 2)
		return '<a href="#" class="accepted">SENT<span class="counter" id="counter_'+id+'">0</span><span class="status">ACCEPTED</span></a>';
	if (status == 3)
		return '<a href="#" class="no-deal">SENT<span class="counter" id="counter_'+id+'">0</span><span class="status">NO DEAL</span></a>';
}


function update_status (id, status) {

	current_status[id] = status;
	status = get_status (status, id);
	$("#status_"+id).text(" ");
	$("#status_"+id).append(status);
}


function send (id) {
	
	url = base_url+'trading/clients/set_quote';
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
		update_status (id, 1);
	}, 
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus, errorThrown);
	}

	});

	current_status[id] = 1;
}

function display_offer (data) {
		
	current_offers[data['offer_id']] = {'status': data['status']};	
	current_status[data['offer_id']] = data['status']		
			
			
	var status = data['status'];	
	var id = data['offer_id'];
	if (status == 0) {		
		quote = '<input type="text" id="input_quote_'+id+'" class="general-td-input" value="0.0000" style="width:45px;">';		
	}
	else {
		quote = data['quote'];
		action = '';
	}
	
	status = get_status (status, id);
	
	$("#corporate_clients").append (
		'<tr id="offer_'+data['offer_id']+'">'+
		'<td>CLNT</td>'+
		'<td id="name_'+id+'">'+data['name']+'</td>'+
		'<td id="market_'+id+'">'+data['market']+'</td>'+
		'<td id="amount_'+id+'">'+data['amount']+'</td>'+
		'<td id="ccy_'+id+'">'+data['currency']+'</td>'+
		'<td id="deal_'+id+'">'+data['deal']+'</td>'+
		'<td id="period_'+id+'">'+data['period_id']+'</td>'+		
		'<td id="quote_'+id+'">'+quote+'</td>'+
		'</tr>'
	);
	
	$("#status_list").append (
		'<li id="status_'+id+'">'+
			status +
		'</li>'
	);
		
	if (data['status'] <= 1) {
		timer_queue.push ( {'id': data['offer_id'], 'date': data['display_date'], 'status' : data['status']} );
		timer ();
	}
}

function get_clients_offers () {
	
	url = base_url+'trading/clients/get_corporate_offers';
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
      	for (var i = 0; i < data.length; i++) {
      		if ( !current_offers[data[i]['offer_id']] ) display_offer (data[i]);						
      		else {
      			if ( current_status[ data[i]['offer_id'] ] != data[i]['status'] )
      				update_status ( data[i]['offer_id'], data[i]['status'] );
      		}
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
		time = parseInt (new Date ().getTime () / 1000 + time_difference);

		for (i in timer_queue) {
			var id = timer_queue[i]['id'];
			var rem = seconds - (time - timer_queue[i]['date']);
			if (rem < 0)
				rem = 0;
				
			$('#counter_'+id).text ( rem );
			if (rem  == 0) {			
		  if (current_status[id] == 0) 
				  	send (id);				  				  
				  
				  delete timer_queue[i];
			}
		}
	}
}


setInterval (timer , 1000);
setInterval (get_clients_offers, 4000);
