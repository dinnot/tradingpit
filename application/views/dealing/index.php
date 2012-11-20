<div> Blotter FX  <br> </div>

<table border = "1">
	
	<tr> 
		<td> Period </td>
		<td> Ccy Pair </td>
		<td> Amount base Ccy </td>
		<td> Price </td>
		<td> Amount var Ccy </td>
		<td> Counter party </td>
		<td> Value date </td>
		<td> Trade date </td>
		<td> Deal ID </td>
		<td> User ID </td>
	</tr>

	
	<?php foreach ( $fx_deals as $deal ) : ?> 
		
		<tr>
			<td> <?php echo $deal['period_name'] ?> </td>
			<td> <?php echo $deal['first_currency'].'/'.$deal['second_currency'] ?> </td>
			<td> <?php echo $deal['amount_base_ccy'] ?> </td>
			<td> <?php echo $deal['price'] ?> </td>
			<td> <?php echo abs( $deal['amount_base_ccy'] * $deal['price'] ) ?> </td>
			<td> <?php echo $deal['counter_party'] ?> </td>
			<td> <?php echo date('d.m.y',$deal['value_date']) ?> </td>
			<td> <?php echo date('d.m.y',$deal['trade_date']) ?> </td>
			<td> <?php echo $deal['fx_deals_id'] ?> </td>
			<td> <?php echo $deal['user_name'] ?> </td>
		</tr>
		
	<?php endforeach ?>
	
</table>

<div> Blotter MM  <br> </div>

<table border = "1">
	
	<tr> 
		<td> Period </td>
		<td> Ccy Pair </td>
		<td> Amount base Ccy </td>
		<td> Price </td>
		<td> Amount var Ccy </td>
		<td> Counter party </td>
		<td> Value date </td>
		<td> Trade date </td>
		<td> Deal ID </td>
		<td> User ID </td>
	</tr>

	
	<?php foreach ( $mm_deals as $deal ) : ?> 
		
		<tr>
			<td> <?php echo $deal['period_name'] ?> </td>
			<td> <?php echo $deal['first_currency'].'/'.$deal['second_currency'] ?> </td>
			<td> <?php echo $deal['amount_base_ccy'] ?> </td>
			<td> <?php echo $deal['price'] ?> </td>
			<td> <?php echo abs( $deal['amount_base_ccy'] * $deal['price'] ) ?> </td>
			<td> <?php echo $deal['counter_party'] ?> </td>
			<td> <?php echo date('d.m.y',$deal['value_date']) ?> </td>
			<td> <?php echo date('d.m.y',$deal['trade_date']) ?> </td>
			<td> <?php echo $deal['mm_deals_id'] ?> </td>
			<td> <?php echo $deal['user_name'] ?> </td>
		</tr>
		
	<?php endforeach ?>
	
</table>
