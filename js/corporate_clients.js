var time_difference = 0;	
function set_time_difference () {
	
	$.ajax({
		url: base_url+'trading/clients/get_time',
		dataType: 'json',
		success: function (data, textStatus, jqXHR) {                    
			time_difference = data['server_time'] - (new Date().getTime () / 1000);	
		}, 	
	});
};

var corporate_clients_class = function () {

	this.name = "corporate_clients";
	this.delay = 300;
	this.timeout = 1000;
	
	//this.page = ;
	
	this.pull = new Object ();
	this.pull["get_corporate_offers"] = 0; // nu trimitem nimic la server pentru pull
	this.pull["next_corporate_clients"] = 0; // 
	
	this.timer_queue = [];
	this.current_offers = {};
	this.current_status = {};
}

corporate_clients_class.prototype.update_status = function (id, status) {
	this.current_status[id] = status;
	status = this.get_status (status, id);
	$("#status_"+id).text(" ");
	$("#status_"+id).append(status);
}


corporate_clients_class.prototype.get_status = function (status, id) {
	if (status == 0) {
		current_couter = 0;
		current_couter = $('#counter_'+id).text (); // de ce nu merge ?
		return '<a href="#" onClick="corporate_clients.send_quote('+id+')" class="pending">SEND<span class="counter" id="counter_'+id+'">'+current_couter+'</span><span class="status">PENDING</span></a>';
	}
	if (status == 1)
		return '<a href="#" class="pending">SENT<span class="counter" id="counter_'+id+'">0</span><span class="status">PENDING</span></a>';
	if (status == 2)
		return '<a href="#" class="accepted">SENT<span class="counter" id="counter_'+id+'">0</span><span class="status">ACCEPTED</span></a>';
	if (status == 3)
		return '<a href="#" class="no-deal">SENT<span class="counter" id="counter_'+id+'">0</span><span class="status">NO DEAL</span></a>';
}

corporate_clients_class.prototype.send_quote = function (id) {
	
	url = base_url+'trading/clients/set_quote';
	price = $('#input_quote_'+id).val ();
	if (!price) price = '0.0000';
	
	if( !validate_price( price ) ) {
		return ;
	} 
	
	dataIn = {
		offer_id : id,
		quote : price
	};
	
	$.ajax({
	type: 'POST',
	url: url,
	data: dataIn,
	async: true,
	price: price,
	dataType: 'json',
	success: function (data, textStatus, jqXHR) {                    
		action = '';
		
		$('#quote_'+id).text (price);
		corporate_clients.update_status (id, 1);
	}, 
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus, errorThrown);
	}

	});

	this.current_status[id] = 1;
}

corporate_clients_class.prototype.display_offer = function (data) {
		
	this.current_offers[data['offer_id']] = {'status': data['status']};	
	this.current_status[data['offer_id']] = data['status']		
			
			
	var status = data['status'];	
	var id = data['offer_id'];
	if (status == 0) {		
		quote = '<input type="text" id="input_quote_'+id+'" maxlength = "6" class="general-td-input" value="0.0000" style="width:45px;">';		
	}
	else {
		quote = data['quote'];
		action = '';
	}
	
	status = this.get_status (status, id);
	
	$("#corporate_clients").append (
		'<tr id="offer_'+data['offer_id']+'">'+
		'<td>CLNT</td>'+
		'<td id="name_'+id+'">'+data['name']+'</td>'+
		'<td id="market_'+id+'">'+data['market']+'</td>'+
		'<td id="amount_'+id+'">'+display_amount(data['amount'])+'</td>'+
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
		this.timer_queue.push ( {'id': data['offer_id'], 'date': data['display_date'], 'status' : data['status']} );
		timer ();
	}
}

corporate_clients_class.prototype.update_corporate_clients = function (data) {
  	for (var i = 0; i < data.length; i++) {
  		if ( !this.current_offers[data[i]['offer_id']] ) this.display_offer (data[i]);						
  		else {
  			if ( this.current_status[ data[i]['offer_id'] ] != data[i]['status'] )
				this.update_status ( data[i]['offer_id'], data[i]['status'] );
  		}
  	}		
}

corporate_clients_class.prototype.update = function (data) {
	this.update_corporate_clients (data['get_corporate_offers']);
}


function next_corporate_clients () {
	url = base_url+'trading/clients/next_corporate_clients';
	$.ajax ({
		url: url,
		error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
    }
	});
}

var seconds = 60;
function timer () {

	if (corporate_clients.timer_queue.length >= 0) {	
		time = parseInt (new Date ().getTime () / 1000 + time_difference);

		var used = 0;
		for (i in corporate_clients.timer_queue) {		
			var id = corporate_clients.timer_queue[i]['id'];
			var rem = seconds - (time - corporate_clients.timer_queue[i]['date']);
			if (rem < 0)
				rem = 0;
				
			if (!used) {
				if (rem % 2 == 1) {
					var menu_id = $('#clients_menu');
					menu_id.css ('background-color', '#000');
				}
				else {
					var menu_id = $('#clients_menu');
					menu_id.css ('background-color', '');
				}
			}
			used = 1;
				
			$('#counter_'+id).text ( rem );
			if (rem  == 0) {			
		  if (corporate_clients.current_status[id] == 0) 
				  	corporate_clients.send_quote (id);				  				  
				  
				  delete corporate_clients.timer_queue[i];
			}
		}
		
		if (used == 0) {
				var menu_id = $('#clients_menu');
				menu_id.css ('background-color', '');		
		}
	}
}

var corporate_clients = new corporate_clients_class ();
Observable.subscribe (corporate_clients);

setInterval (timer , 1000);
