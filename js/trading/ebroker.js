var ebroker_class = function () {
	this.name = "ebroker";
	this.delay = 500;
	this.timeout = 3000;
	
	this.pull = new Object ();
	this.pull['get_best_prices'] = 0;
	this.hold = 0;
	
	this.available_buy = [0, 0, 0];
	this.available_sell = [0, 0, 0];
}

ebroker_class.prototype = {
	add_price : function (pairs_id, deal, amount, price) {
	
		if (deal == 'sell') {	
			if (this.available_buy[pairs_id] != 0 && parseFloat(price) < this.available_buy[pairs_id]) {
				alert ('It is a better price in the toy!');
				return ;
			}
		}
		else {
			if (this.available_sell[pairs_id] != 0 && parseFloat(price) > this.available_sell[pairs_id]) {
				alert ('It is a better price in the toy!');
				return ;
			}
		}
		
	
		$.ajax ({			
			type: 'POST',
			url: base_url+'trading/ebroker/add_price',
			data : {pairs_id:pairs_id, deal:deal, amount:amount, price:price},
		});
	},
	remove_price : function (id) {
	
	},
	make_deal : function  (pairs_id, deal, price) {
		amount = prompt ("Amount : ");
		if (!amount)
			return ;
		
		$.ajax ({
			url : base_url+'trading/ebroker/make_deal',
			type: 'POST',
			data : {'pairs_id':pairs_id, 'deal':deal, 'price':price, 'amount':amount},
			dataType: 'json',    		
			success : function (data, textStatus, jqXHR) {
				// poate poate testam esecu
			}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}   		
		});
	}
}

ebroker_class.prototype.display_best_prices = function (prices) {
	for (pairs_id = 1; pairs_id <= 2; pairs_id++) {
		buy_price = prices[pairs_id]['available']['buy']['bf'] + prices[pairs_id]['available']['buy']['pips'];		
		sell_price = prices[pairs_id]['available']['sell']['bf'] + prices[pairs_id]['available']['sell']['pips'];
		
		this.available_buy[pairs_id] = parseFloat (buy_price);		
		this.available_sell[pairs_id] = parseFloat (sell_price);
		
		$('#'+pairs_id+'_bf').text ('');
		$('#'+pairs_id+'_bf').append (
			'<li >'+prices[pairs_id]['available']['buy']['bf']+' </li> <li> / </li> <li> '+prices[pairs_id]['available']['sell']['bf']+'</li>'
		);
		$('#'+pairs_id+'_pips').text ('');
		$('#'+pairs_id+'_pips').append ('<a href="#" onclick="ebroker.make_deal('+pairs_id+', \'buy\', \''+buy_price+'\')">'+prices[pairs_id]['available']['buy']['pips']+'</a>'+'/'+		'<a href="#"  onclick="ebroker.make_deal('+pairs_id+', \'sell\', \''+sell_price+'\')">'+prices[pairs_id]['available']['sell']['pips']+'</a>');
						
		$('#'+pairs_id+'_amount').text (prices[pairs_id]['available']['buy']['amount']+' / '+prices[pairs_id]['available']['sell']['amount']);
		
		
		$('#best_'+pairs_id).text (prices[pairs_id]['all']['buy']+' / '+prices[pairs_id]['all']['sell']);
	}
}

ebroker_class.prototype.update  = function (data) {
	this.display_best_prices (data['get_best_prices']);
}

ebroker_class.prototype.cancel_users_prices  = function (pairs_id) {
	$.ajax ({
		_this: this,
		url: base_url+'trading/ebroker/cancel_user_prices',
		data : {'pairs_id':pairs_id},
		success : function (data, textStatus, jqXHR) {
			// !!
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
		}
	});	
}

ebroker_class.prototype.get_best_prices = function () {
	$.ajax ({
		type: 'POST',
		_this: this,
		url: base_url+'trading/ebroker/get_best_prices',
		success : function (data, textStatus, jqXHR) {
			this._this.display_best_prices (data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
		}
	});	
}

ebroker = new ebroker_class ();

function display_form (pairs_id, deal) {
	amount = prompt ("Amount");
	price = prompt ("Price");
	
	if (!amount || !price)
		return ;
	
	ebroker.add_price (pairs_id, deal, amount, price);
}

ebroker.get_best_prices ();
Observable.subscribe (ebroker);
