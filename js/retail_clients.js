/*function set_exchange_rate (pair_id) {

	url = base_url+"trading/clients/set_exchange_rate";
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

	url = base_url+"trading/clients/check_next_client";
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
      	
    		$('#retail_sell_1').text (data['amount'][1]['sell']);
    		$('#retail_buy_1').text (data['amount'][1]['buy']);
    		$('#retail_sell_2').text (data['amount'][2]['sell']);
    		$('#retail_buy_2').text (data['amount'][2]['buy']);
    	}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      },      
		});
}

function display_deals (deals) {
	
	$("#deals").text ("");
	for (i = 0; i < deals.length; i++) {
		$("#deals").append (
			'amount : ' + deals[i]['amount_base_ccy'] + ' ccy : ' + deals[i]['ccy_pair'] + ' price : ' + deals[i]['price'] +' counter_party : ' + deals[i]['counter_party'] + ' time : ' + deals[i]['trade_date']
		);
		$("#deals").append ('<hr />');
	}
}

function get_user_deals () {

	url = base_url+"trading/clients/get_user_deals";
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
      	display_deals (data);
    	}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      },      
		});
}

setInterval (get_user_deals, 4000);
setInterval (check_next_client, 4000);
*/

function swap (pair) {
	pair2 = 1;
	if (pair == 1) pair2 = 2;	
	
	$('#pair_'+pair2).hide ();	
	$('#pair_'+pair).show ();
}
