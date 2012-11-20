<html>

<head>

<title>Clients</title>

<script src="<?php print base_url () ?>js/jquery.js"></script>
<script src="<?php print base_url () ?>js/date.js"></script>
<script src="<?php print base_url () ?>js/corporate_clients.js"></script>
<script src="<?php print base_url () ?>js/retail_clients.js"></script>

</head>

	<table id="corporate_clients">
		<th>Code</th> <th>Client Name</th> <th>Market</th> <th>Amount</th> <th>Ccy</th> <th>Deal</th> <th>Period</th> <th>Quote</th>
		
	</table>
	<hr />
	Sell: <span id="retail_sell_1"><?php print $amount[1]['sell'] ?></span> <br />
	Buy: <span id="retail_buy_1"><?php print $amount[1]['buy'] ?></span> <br />
	Sell <input type="text" id="sell_1" value="<?php print $retail_rate[1]['sell']; ?>"/> <br />
	Buy <input type="text" id="buy_1" value="<?php print $retail_rate[1]['buy'];?>"/>
	<button onclick=set_exchange_rate(1)>send</button>

	<hr />
	
	Sell: <span id="retail_sell_2"><?php print $amount[2]['sell'] ?></span> <br />
	Buy
	: <span id="retail_buy_2"><?php print $amount[2]['buy'] ?></span> <br />
	Sell <input type="text" id="sell_2" value="<?php print $retail_rate[2]['sell']; ?>"/> <br />
	Buy <input type="text" id="buy_2" value="<?php print $retail_rate[2]['buy'];?>"/>
	<button onClick=set_exchange_rate(2) id="1" >send</button>
</body>

	<hr />
	deals
	<div id="deals">
		
	</div>

<script>
	var user_id = <?= $user_id ?>;
	var base_url = "<?= base_url() ?>";

	set_time_difference ();
	get_clients_offers ();
	check_next_client ();
	get_user_deals ();
</script>

</html>
