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

