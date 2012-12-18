var retail_client_class = function () {
	
	this.name = 'retail_client';

	this.delay = 1000;
	this.timeout = 2000;
	
	this.pull = new Object ();
	this.pull['check_next_client'] = 0; // nu trimitem nimic la server pentru pull
}

get_price = function (bf, pips) {
	while (pips.length < 3)
	 pips = "0" + pips;

	return bf + pips;
}

retail_client_class.prototype.set_exchange_rate = function (pair_id) {

	url = base_url+"trading/clients/set_exchange_rate";
	sell = get_price ( $('#bf_sell_'+pair_id).val(),  $('#pips_sell_'+pair_id).val());
	buy = get_price ( $('#bf_buy_'+pair_id).val(),  $('#pips_buy_'+pair_id).val ());
	
	if( !validate_price(sell) || !validate_price(buy) || !validate(pair_id) ) {
		return ;
	}
	
	data_in = {
		'pair_id' : pair_id,
		'sell' : sell,
		'buy' : buy 
	}	

	$.ajax({
		type: 'POST',
		url: url,
		data: data_in,
		success: function (data, textStatus, jqXHR) {                    
    	alert ("changed");  	
		}, 
		error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    },
	});

}

retail_client_class.prototype.update = function (data) {
	data = data['check_next_client'];
	$('#retail_sell_1').text (data['amount'][1]['sell']);
	$('#retail_buy_1').text (data['amount'][1]['buy']);
	$('#retail_sell_2').text (data['amount'][2]['sell']);
	$('#retail_buy_2').text (data['amount'][2]['buy']);
	$('#total_volume_1').text ( parseFloat (data['amount'][1]['sell']) + parseFloat (data['amount'][1]['buy']));
	$('#net_position_1').text ( parseFloat (-data['amount'][1]['sell']) +  parseFloat ( data['amount'][1]['buy']));
	$('#total_volume_2').text ( parseFloat (data['amount'][2]['sell']) +  parseFloat ( data['amount'][2]['buy']));
	$('#net_position_2').text ( parseFloat (-data['amount'][2]['sell']) +  parseFloat ( data['amount'][2]['buy']));
}

function swap (pair) {
	pair2 = 1;
	if (pair == 1) pair2 = 2;	
	
	$('#pair_'+pair2).hide ();	
	$('#pair_'+pair).show ();
}


var retail_client = new retail_client_class ();
Observable.subscribe (retail_client);
