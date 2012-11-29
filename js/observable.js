var Observable_class = function() {
    this.subscribers = new Array ();
}

Observable_class.prototype = {		
    subscribe: function(callback) {
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
    pull: function () {
    	data_in = {};
    	var i = 0, len = this.subscribers.length;
    	for (; i < len; i++) 
    		data_in[this.subscribers[i]['name']] = this.subscribers[i]['pull'];
    	
    	url = base_url + "/pull";
    	    	
    	$.ajax ({
    		url : url,
   			type: 'POST',
	 			data : data_in,
        dataType: 'json',    		
				success : function (data, textStatus, jqXHR) {
					var i = 0, len = Observable.subscribers.length;
					for (; i < len; i++) 
						Observable.subscribers[i].update ( data[Observable.subscribers[i]['name']] );
				}, 
				error: function(XMLHttpRequest, textStatus, errorThrown) {
      	  console.log(textStatus, errorThrown);
      	}   		
    	});    	
    }
};

Observable = new Observable_class ();
