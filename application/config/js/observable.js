var Observable_class = function() {
	this.subscribers = [];
}

Observable_class.prototype = {		
	subscribe: function(callback) {
		callback['next_pull'] = new Date ().getTime ();
		this.subscribers.push(callback);
	},
	unsubscribe: function(callback) {
		var i = 0, len = this.subscribers.length;
		for (; i < len; i++) {
			if (this.subscribers[i] === callback) {
				this.subscribers.splice(i, 1);
				return;
			}
		}
	},
	pull: function (ready) {
		data_in = {};
		var i = 0, len = ready.length;
		for (; i < len; i++) 
			data_in[ready[i]['name']] = ready[i]['pull'];
	
		url = base_url + "/pull";
			  	
		$.ajax ({
			url : url,
			type: 'POST',
			data : data_in,
			ready : ready,
			async : true,
			dataType: 'json',    		
			success : function (data, textStatus, jqXHR) {
				for (i = 0; i < this.ready.length; i++) 					
					this.ready[i].update ( data[this.ready[i]['name']] );			
			}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}   		
		});    	
	},
	check: function () {
		var ready = [];
		current_time = new Date ().getTime ();
		right = -1;
		size = this.subscribers.length;    	
	
		for (i = 0; i < size; i++) 
			obj = this.subscribers[i];
			if (right == -1 || right > obj.next_pull + obj.delay)
				right = obj.next_pull + obj.delay;
	
		if (right == -1 || right > current_time)
			return ;    	
	
		for (i = 0; i < size; i++) {
			obj = this.subscribers[i];
			if (obj.next_pull <= right)
				ready.push (obj);
		}
	
		this.pull (ready);
		size = ready.length;
		for (i = 0; i < size; i++) 
			ready[i].next_pull = new Date().getTime () + ready[i].timeout;
	
	}
};

Observable = new Observable_class ();

var that = Observable;
setInterval ( function () {return that.check()}, 100 );
