$("#event_filter").change (
	function () {
		get_econforcasts ();
	}
);

$("#type_filter").change (
	function () {
		get_econforcasts ();
	}
);

$("#event_filter").click (
	function () {
		$("#event_filter").val ("");
	}
);

$("#datepickerEnd").change (
	function () {
		if ( $("#datepickerStart").val ().length > 0 )
			get_econforcasts ();
	}
);

$("#datepickerStart").change (
	function () {
		if ( $("#datepickerEnd").val ().length > 0 )
			get_econforcasts ();
	}
);

function get_econforcasts () {
	
	var url = "http://localhost/tradingpit/econ/econ/get_econforcasts";
	data_in = new Object ();
	
	var event_filter = $("#event_filter").val ();
	if (event_filter != "Event Name")
		data_in['event_filter'] = event_filter;

	var type_filter = $("#type_filter").val ();
	if (type_filter != 0)
		data_in['type_filter'] = type_filter;
	
	var start = $("#datepickerStart").val ();
	var end = $("#datepickerEnd").val ();

	if (start.length > 0 && end.length > 0) {
		data_in['date_start_filter'] = start;
		data_in['date_end_filter'] = end;
	}
	
	console.log (data_in);
	
	$.ajax({
			action: 'POST',
      url: url,
      data: data_in,
      async: true,
      dataType: 'json',
      success: function (data,textStatus, jqXHR) {                    
				console.log ("dada");
				console.log (data);    		
    		display_econforcasts (data);
			}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      }

		});
}


function display_econforcasts (econforcasts) {

	$('#econforcasts_table').text('');
	
	for (var i = 0; i < econforcasts.length; i++) {
		var timestamp = econforcasts[i]['date'] * 1000;
		data1 = new Date(timestamp).toString('dd/MMM');
		data2 = new Date(timestamp).toString('HH:mm');
		
		$("#econforcasts_table").append ("<tr>");
		$('#econforcasts_table tr:last').append($("<td class=\"blue-td\"></td>").text(econforcasts[i]['id']));
		$('#econforcasts_table tr:last').append($("<td class=\"blue-td\"></td>").text(data1));
		$('#econforcasts_table tr:last').append($("<td class=\"blue-td\"></td>").text(data2));
		$('#econforcasts_table tr:last').append($("<td class=\"blue-td\"></td>").text(econforcasts[i]['countries_name']));
		$('#econforcasts_table tr:last').append($("<td class=\"blue-td\"></td>").text(""));
		$('#econforcasts_table tr:last').append($("<td class=\"blue-td\"></td>").text(""));
		$('#econforcasts_table tr:last').append($("<td class=\"blue-td\"></td>").text(econforcasts[i]['econindicators_name']));
		$('#econforcasts_table tr:last').append($("<td class=\"blue-td\"></td>").text(""));
		$('#econforcasts_table tr:last').append($("<td class=\"blue-td\"></td>").text(""));
		$('#econforcasts_table tr:last').append($("<td class=\"blue-td\"></td>").text(econforcasts[i]['actual']));
		$('#econforcasts_table tr:last').append($("<td class=\"blue-td\"></td>").text(""));
		$('#econforcasts_table tr:last').append($("<td class=\"blue-td\"></td>").text(econforcasts[i]['forecast']));
		$("#econforcasts_table").append ("</tr>");
	}
}
