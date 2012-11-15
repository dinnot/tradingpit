<div> Fx Open Positions </div> 

<table border = "1"> 
	<tr> 	
		<td> Ccy			      </td>
		<td> Open Amount (Currency)           </td>
		<td> Open Amount (Reporting Currency) </td>
		<td> Position Limit 		      </td>
		<td> Rate 			      </td>
		<td> RISK 			      </td>
	</tr>
	
	
	<?php foreach ( $fx_positions as $row ) : ?>
		
		<tr>	
			<td> <?php echo $row['currency_name'] ?> </td> 
			<td> <?php echo $row['amount'] ?> </td> 
			<td>
				<div class = "hide TER"> <?php echo $row['reporting_currency'][0] ?> </div>
				<div class = "hide RIK" hidden = "hidden"> <?php echo $row['reporting_currency'][1] ?> </div>
				<div class = "hide HAT" hidden = "hidden"> <?php echo $row['reporting_currency'][2] ?> </div>
			</td>
			<td> <?php echo $row['position_limit'] ?> </td> 
			<td> <?php echo $row['rate'] ?> </td> 
			<td> 
				<div class = "hide TER"> <?php echo $row['risk'][0] ?> </div>
				<div class = "hide RIK" hidden = "hidden"> <?php echo $row['risk'][1] ?> </div>
				<div class = "hide HAT" hidden = "hidden"> <?php echo $row['risk'][2] ?> </div>
			</td> 
		</tr>
	<?php endforeach ?> 
	
	<tr> 
		<td> AGG </td>
		<td></td>
		<td> <?php echo $agg['reporting_currency'] ?> </td>
		<td> <?php echo $agg['position_limit'] ?> </td>
		<td> N/A </td>
		<td> <?php echo $agg['risk'] ?> </td>
	</tr>
</table> 

<br><br>

<div id = "TER"> TER </div>
<div id = "RIK"> RIK </div>
<div id = "HAT"> HAT </div>


<script src="<?php print base_url() ?>js/jquery.js"></script>

<script>
	$("#TER").click( function () {
					alert("TER");
					$(".hide").hide();
					$(".TER").show(); 
				});
	
			
	$("#HAT").click( function () {
					alert("HAT");
					$(".hide").hide();
					$(".HAT").show(); 
				});
	

	$("#RIK").click( function () {
					alert("RIK");
					$(".hide").hide();
					$(".RIK").show(); 
				});	

</script>			

