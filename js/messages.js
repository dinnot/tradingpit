var current_conv = '';

function add_conversation () {
	
	url = base_url + "messages/add_conversation";
	data_in = {
		'message' : $('#message').val (),
		'username' : $('#username').val (),
		'subject': $('#subject').val()
	};
	
	$.ajax ({
		url: url,
    dataType: 'json',
    data: data_in,
    success: function (data, textStatus, jqXHR) {                    
    	display_current_conv (data['conv_id']);
  	}, 
		error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
    }
 	});
}

function get_new_messages () {
	
	url = base_url+"messages/get_new_messages";
	data_in = {'conv_id':current_conv};
	$.ajax({
      url: url,
      dataType: 'json',
      data: data_in,
      success: function (data, textStatus, jqXHR) {                    
      	display_messages (data);
    	}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      },      
	});
}

function add_message (message, conv_id) {

	url = base_url+"messages/add_message";
	
	data_in = {
		'message' : message,
		'conv_id' : conv_id
	};
	
	$.ajax({
      url: url,
      data: data_in,     
	});
}

function send_reply_message () {
	message = $('#reply_message').val ();
	add_message (message, current_conv);	
	display_message (message);
	$('#reply_message').attr ('value', ' ');
}

function display_message (message) {
		$("#current_messages").append (
			'<li>'+
				username + ' : '+message +
			'</li>'
		);
			
	$('#chat_box').scrollTop (999999);
}

function display_messages (messages) {
	
	for (i = 0; i < messages.length; i++) {
		$("#current_messages").append (
			'<li>'+
				messages[i]['username'] + ' : '+messages[i]['message'] +
			'</li>'
		);
	}
		
	$('#chat_box').scrollTop (999999);
}

function display_current_conv (conv_id) {
	url = base_url+"messages/get_messages";
	data_in = {'conv_id':conv_id};
	current_conv = conv_id;
	$('#chat_box').css ('height', 250);
	$.ajax({
      url: url,
      dataType: 'json',
      data: data_in,
      success: function (data, textStatus, jqXHR) {                    
    		console.log (data);
				$("#chat_box").text ('  ');
				$("#chat_box").append ('<ul id="current_messages"></ul>	<textarea id="reply_message"></textarea>	<button onclick=send_reply_message()>reply</button>');
	
      	display_messages (data);
      	
				$('#chat_box').scrollTop (999999);
    	}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      },      
	});	
}

function display_conversations (convs) {
	
	for (i = 0; i < convs.length; i++) {
		
		$('#conversations').append (
			'<li id="conversation_'+convs[i]['conversations_id']+'">'+
			convs[i]['title'] +
			' -> <a href="#" onClick=display_current_conv('+convs[i]['conversations_id']+')>show</a>'+
			'</li>'
		);
	}
}

function get_conversations () {

	url = base_url+"messages/get_conversations";

	$.ajax({
      url: url,
      dataType: 'json',
      success: function (data, textStatus, jqXHR) {                    
      	
      	display_conversations (data);
    	}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
      },      
		});
}

setInterval (get_new_messages, 2000);
