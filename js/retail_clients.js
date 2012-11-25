
function get_price (bf, pips) {
	while (pips.length < 3)
	 pips = "0" + pips;
	 
	return bf + pips;
}

function set_exchange_rate (pair_id) {

	url = base_url+"trading/clients/set_exchange_rate";
	sell = get_price ( $('#bf_sell_'+pair_id).val(),  $('#pips_sell_'+pair_id).val());
	buy = get_price ( $('#bf_buy_'+pair_id).val(),  $('#pips_buy_'+pair_id).val ());
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

// pool
function check_next_client () {

	url = base_url+"trading/clients/check_next_client";
	$.ajax({
      url: url,
      async: true,
      dataType: 'json',
      success: function (data, textStatus, jqXHR) {                    
    		$('#retail_sell_1').text (data['amount'][1]['sell']);
    		$('#retail_buy_1').text (data['amount'][1]['buy']);
    		$('#retail_sell_2').text (data['amount'][2]['sell']);
    		$('#retail_buy_2').text (data['amount'][2]['buy']);
    		$('#total_volume_1').text ( parseFloat (data['amount'][1]['sell']) + parseFloat (data['amount'][1]['buy']));
    		$('#net_position_1').text ( parseFloat (-data['amount'][1]['sell']) +  parseFloat ( data['amount'][1]['buy']));
    		$('#total_volume_2').text ( parseFloat (data['amount'][2]['sell']) +  parseFloat ( data['amount'][2]['buy']));
    		$('#net_position_2').text ( parseFloat (-data['amount'][2]['sell']) +  parseFloat ( data['amount'][2]['buy']));
    	}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      },      
		});
}

function display_deals (deals) {
	
	$("#deals").text ("");
	for (i = 0; i < deals.length; i++) {
		code = 'CLNT';
		if (deals[i]['counter_party'] == 0) code = "RTL";
		Time = new Date ( deals[i]['trade_date'] * 1000 );
		time = Time.toString("h:mm:ss");	
		data = Time.toString("dd/MM");
		$("#deals").append (
			'<tr>'+
			'<td>'+code+'</td>'+
			'<td>'+data+'</td>'+
			'<td>'+time+'</td>'+		
			'<td>'+deals[i]['price']+'</td>'+
			'<td>'+deals[i]['amount_base_ccy']+'</td>'+
			'</tr>'
		);
	}
}

function get_user_deals () {

	url = base_url+"trading/clients/get_user_deals";
	
	$.ajax({
      url: url,
      async: true,
      dataType: 'json',
      success: function (data, textStatus, jqXHR) {                    
      	display_deals (data);
    	}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      },      
		});
}

setInterval (get_user_deals, 4000);
setInterval (check_next_client, 4000);

function swap (pair) {
	pair2 = 1;
	if (pair == 1) pair2 = 2;	
	
	$('#pair_'+pair2).hide ();	
	$('#pair_'+pair).show ();
}
