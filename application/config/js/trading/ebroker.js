var ebroker_class = function () {
	this.name = "ebroker";
	this.delay = 500;
	this.timeout = 1000;
	
	this.pull = new Object ();
	this.pull['get_best_prices'] = 0;
	this.hold = 0;
}

ebroker_class.prototype = {
	add_price : function (pairs_id, deal, amount, price) {
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
		buy_price = prices[pairs_id]['buy']['bf'] + prices[pairs_id]['buy']['pips'];		
		sell_price = prices[pairs_id]['sell']['bf'] + prices[pairs_id]['sell']['pips'];
		
		$('#'+pairs_id+'_bf').text ('');
		$('#'+pairs_id+'_bf').append (
			'<li >'+prices[pairs_id]['buy']['bf']+' </li> <li> / </li> <li> '+prices[pairs_id]['sell']['bf']+'</li>'
		);
		$('#'+pairs_id+'_pips').text ('');
		$('#'+pairs_id+'_pips').append ('<a href="#" onclick="ebroker.make_deal('+pairs_id+', \'buy\', \''+buy_price+'\')">'+prices[pairs_id]['buy']['pips']+'</a>'+'/'+		'<a href="#"  onclick="ebroker.make_deal('+pairs_id+', \'sell\', \''+sell_price+'\')">'+prices[pairs_id]['sell']['pips']+'</a>');
						
		$('#'+pairs_id+'_amount').text (prices[pairs_id]['buy']['amount']+' / '+prices[pairs_id]['sell']['amount']);
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
	
	ebroker.add_price (pairs_id, deal, amount, price);
}

ebroker.get_best_prices ();
Observable.subscribe (ebroker);
