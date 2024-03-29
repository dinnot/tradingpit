var matching_class = function () {
	this.name = "matching";
	this.delay = 500;
	this.timeout = 1000;
	
	this.pull = new Object ();
	this.pull['get_user_prices'] = 0;
	this.pull['get_user_deals'] = 0;

	this.prices_hash = new Object ();
	this.prices = [];
}

matching_class.prototype = {
	display_user_price : function (price) {
		var match_id = $('#matching');
		if (price['pairs_id'] == 1) pair = 'TER/RIK'; // urattt
		else pair = 'HAT/RIK';
		match_id.prepend (
			'<tr id="matching_'+price['id']+'"><td>'+price['deal']+'</td><td>'+pair+'</td><td id="amount_'+price['id']+'">'+ display_amount(parseInt (price['amount']) * 1000000) +'</td><td>SPOT</td><td>'+price['price']+'</td><td class="light-blue"></td><td onclick="matching.remove_user_price('+price['id']+')">CANCEL</td></tr>'
			);	
	},
	hide_user_price : function (price_id) {
		$('#matching_'+price_id).remove ();
	},
	remove_user_price : function (price_id) {
		this.prices_hash[price_id] = 0;
		this.hide_user_price (price_id);		
		
		$.ajax ({
			url: base_url+'trading/ebroker/remove_user_price',
			type : 'POST',
			data : {'price_id': price_id},
			price_id : price_id,
			_this : this,
			dataType : 'json',
			success : function (data, textStatus, jqXHR) {
					console.log ('dsadas');
					this._this.hide_user_price (price_id);						
			}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			} 
		});
	},
	update_user_prices :function (prices) {

		var len = prices.length;
		var hash = Object ();

		for (var i = 0; i < len; i++) {
			hash[ prices[i]['id'] ] = 1;
			if (this.prices_hash[ prices[i]['id'] ] == 1) {
				amount_id = $('#amount_'+prices[i]['id']);
				prices[i]['amount']*= 1000000;
				if (amount_id.text () != prices[i]['amount'] )
					amount_id.text( display_amount(prices[i]['amount']));
			}
			else {
				this.prices_hash[prices[i]['id']] = 1;
				this.prices.push ( prices[i]['id'] );
				this.display_user_price (prices[i]);
			}
		}

		len = this.prices.length;
		for (var i = 0; i < len; i++) {
			if (this.prices_hash[this.prices[i]] == 1 && hash[ this.prices[i] ] != 1) {
				this.prices_hash[this.prices[i]] = 0;
				this.hide_user_price (this.prices[i]);
			}
		}
	},		
	update_user_deals : function (data) {
		
		deals_id = $('#deals');
		deals_id.text ('');
		for (i = 0; i < data.length; i++) {
			
			if (data[i]['ccy_pair'] == 1) pair = 'TER/RIK'; // urattt
			else pair = 'HAT/RIK';
			
			deals_id.append (
			'<tr><td></td><td>'+pair+'</td><td>'+display_amount(data[i]['amount_base_ccy'])+'</td><td>SPOT</td><td>'+data[i]['price']+'</td><td class="light-blue"></td><td>DONE</td></tr>'
			);	
		}
	},
	get_user_prices : function () {
		$.ajax ({
			url: base_url+'trading/ebroker/get_user_prices',
			type : 'POST',
			_this: this,
			dataType: 'json',    		
			success : function (data, textStatus, jqXHR) {
				this._this.update_user_prices (data);
			}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}  
		});	
	},
	get_user_deals : function () {
		$.ajax ({
			url: base_url+'trading/ebroker/get_user_deals',
			type : 'POST',
			_this: this,
			dataType: 'json',    		
			success : function (data, textStatus, jqXHR) {
				this._this.update_user_deals (data);
			}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}  
		});
	},
	update : function (data) {
		this.update_user_prices (data['get_user_prices']);
		this.update_user_deals (data['get_user_deals']);
	}
}

var matching = new matching_class ();
matching.get_user_prices ();
matching.get_user_deals ();
Observable.subscribe (matching);
