var conversations_class = function () {
	this.convs = Object ();
	this.last_conv = 0;
	
	this.name = 'conversations';
	this.delay = 500;
	this.timeout = 1000;
	
	this.pull = Object ();
	this.pull['get_conversations'] = {'last_conv': 0}; 
}

conversations_class.prototype = {
	add_conv : function (conv_id, last_activity) {
		this.convs[conv_id] = last_activity;
	},	
	get_last_activity : function (conv_id) {
		if (conv_id in this.convs)
			return this.convs[conv_id];
		return 0;		
	},
	update_last_activity : function (conv_id, last_activity) {
		this.convs[conv_id] = last_activity;
	}
}

conversations_class.prototype.update_conversations = function (conv) {
	len = conv.length;
	for (i = 0; i < len; i++) {
		this.pull['get_conversations']['last_conv'] = Math.max (
				this.pull['get_conversations']['last_conv'], conv[i]['last_activity']);
		
		con_id = conv[i]['conversations_id'];
		last_activity = conv[i]['last_activity'];
		last = this.get_last_activity ( con_id );
		if (last == 0 || last != last_activity) {
			this.update_last_activity ( con_id, last_activity );
			if (last != 0) {
				$('#conversation_'+con_id).remove ();
			}
			
			co_id = $('#conversations');			
			co_id.prepend (
				'<li id="conversation_'+con_id+'">'+
					conv[i]['title'] +
				' -> <a href="#" onClick=display_current_conv('+con_id+')>show</a>'+
				'</li>'
			);
		}
	}
}

conversations_class.prototype.update = function (data) {
	this.update_conversations (data['get_conversations']);
}

conversations_class.prototype.get_conversations = function () {
	$.ajax ({
		url : base_url+'messages/get_conversations',
		this_conv : this,
		type: 'POST',
		data : {'last_conv': this.last_conv},
		async : true,
		dataType: 'json',    		
		success : function (data, textStatus, jqXHR) {
			this.this_conv.update_conversations (data);
		}, 
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}   		
	});

}

conversations = new conversations_class ();

conversations.get_conversations();
Observable.subscribe (conversations);
