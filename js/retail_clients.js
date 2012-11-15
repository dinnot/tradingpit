function set_exchange_rate (pair_id) {

	url = base_url+"trading/retail_clients/set_exchange_rate";
	data_in = {
		'user_id' : user_id,
		'pair_id' : pair_id,
		'sell' : $('#sell_'+pair_id).val (),
		'buy' : $('#buy_'+pair_id).val () 
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

	url = base_url+"trading/retail_clients/check_next_client";
	data_in = {
		'user_id' : user_id
	}
	
	$.ajax({
      type: 'POST',
      url: url,
      data: data_in,
      async: true,
      dataType: 'json',
      success: function (data, textStatus, jqXHR) {                    
      	console.log (data);
    		$('#retail_sell_0').text (data['amount'][0]['sell']);
    		$('#retail_buy_0').text (data['amount'][0]['buy']);
    		$('#retail_sell_1').text (data['amount'][1]['sell']);
    		$('#retail_buy_1').text (data['amount'][1]['buy']);
    	}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      },      
		});
}

setInterval (check_next_client, 10000);
