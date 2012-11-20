$("#body_filter").change (
	function () {
		get_news ();
	}
);

$("#country_filter").change (
	function () {
		get_news ();
	}
);

function get_news () {
	
	var url = base_url+"index.php/news/news/get_news";
	data_in = new Object ();
	
	var body_filter = $("#body_filter").val() ;
	if( body_filter != "SEARCH" ) 
		data_in['body_filter'] = body_filter ; 
		
	var country_filter = $("#country_filter").val() ; 
	if( country_filter != 0 ) 
		data_in['country_filter'] = country_filter ;
		
	
	$.ajax({
		action: 'POST',
      		url: url,
      		data: data_in,
      		async: true,
      		dataType: 'json',
      
      		success: function (data,textStatus, jqXHR) {                    
				console.log ("dada");
				console.log (data);    		
    			        display_news (data);
			 }, 
	  
	        error: function(XMLHttpRequest, textStatus, errorThrown) {
        			console.log(textStatus, errorThrown);
      			}

	});
}

function display_news (news) {

	$('#news_table').text('');
	
	for (var i = 0; i < news.length; i++) {
	
		var timestamp = news[i]['date'] * 1000;
		data1 = new Date(timestamp).toString('dd/MMM');
		data2 = new Date(timestamp).toString('HH:mm');
		
		$("#news_table").append ("<tr>");
		$('#news_table tr:last').append($("<td class=\"first\"></td>").text(data1));
		$('#news_table tr:last').append($("<td class=\"red-td\"></td>").text(data2));
		$('#news_table tr:last').append($("<td> </td>").text(news[i]['country_name']));
		$('#news_table tr:last').append("<td>");
		$('#news_table td:last').append($("<div class=\"show\"></div>").text(news[i]['headline']));
		$('#news_table td:last').append($("<div class=\"hide\" hidden=\"hidden\"></div>").text(news[i]['body']));
		$('#news_table tr:last').append("</td>");
		$("#news_table").append ("</tr>");
	}
}


$(".show").click( function() {
			$(".hide").hide();
			$(".show").show();
			$(this).hide();
			$(this).next().show();
		 });

$(".hide").click( function() {  
			$(this).hide();
			$(".show").show();
		});


function clock () {
	var currentTime = new Date ();
	$("#time").text (currentTime.toString("h:mm:ss tt"));	
	$("#date").text (currentTime.toString("dd/MM/yyyy"));
}

clock ();
setInterval (clock, 1000);
