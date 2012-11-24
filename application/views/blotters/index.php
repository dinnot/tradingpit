<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />

  <title>Trading Page - Trading Pit</title>
   <meta name="description" content="" />
  <link rel="stylesheet" href="<?php print base_url() ?>css/style.css" />
  <link href="<?php print base_url() ?>css/jquery-ui-1.9.1.custom.css" rel="stylesheet">
  <script src="<?php print base_url() ?>js/jquery-1.8.2.min.js"></script>
  <script src="<?php print base_url() ?>js/jquery-ui-1.9.1.custom.js"></script>
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <script>
  $(function() {
    $( "#datepickerStart, #datepickerEnd" ).datepicker({
      showOn: "button",
      buttonImage: "images/calendar-icon.png",
      buttonImageOnly: true
    });
  });
  </script>
  <script>
    $(document).ready(function() {
      $('.table-container tbody tr').click(function() {
        $('.table-container tbody tr').removeClass('active');
        $(this).addClass('active');
      })
    })
  </script>
 
</head>

<body>
  <header>
      <article class="top-section container">
        <section class="top-header">
        </section><!-- end top-header -->
        <section class="navigation-bar">
          <nav>
            <ul>

              <li class="level-one">
                <a href="#" class="first-level light-blue">Traderion dealing</a>
                <ul>
                  <li><a href="#">Auction calendar</a></li>
                  <li><a href="#">Economic calendar</a></li>
                </ul>
              </li>

               <li class="level-one">
                <a href="#" class="first-level light-blue">Clients</a>
              </li>

               <li class="level-one">
                <a href="#" class="first-level light-blue current">Blotters</a>
              </li>

               <li class="level-one last">
                <a href="#" class="first-level light-blue">Cash flow</a>
              </li>

            </ul>
            <div class="date-time-info trading-page">
              <ul>
                <li class="first light-blue"><a href="#">PnL</a></li>
                <li class="current"><a href="#">TER</a></li>
                <li><a href="#">HAT</a></li>
                <li><a href="#">RIK</a></li>
                <li class="last"><a href="#">+753,999</a></li>
              </ul>
            </div><!-- end date-time-info -->
          </nav>
          <span class="nav-bar-bottom-bg"></span>
        </section><!-- end navigation-bar -->

        </article><!-- end top-section container -->

      </header>

    <article class="container">

      <article class="main-content">

        <section class="top-main">
          <div class="spot-positions">
            <ul>
              <li class="first green-gradient"><span>SPOT POSITIONS</span></li>
              <li class="second">
              	<span class="light-green">TER/RIK</span> 
              	<?php echo $spot_positions[0]['position_amount'] ?> 
              	<span class="gray">@<?php echo $spot_positions[0]['position_rate'] ?> </span></li>
              
              <li><span class="green">HAT/RIK</span> 
              <?php echo $spot_positions[1]['position_amount'] ?>
              <span class="gray">@<?php echo $spot_positions[1]['position_rate'] ?> </span></li>
              
              <li class="last"><span class="green">HAT/TER</span>
              <?php echo $spot_positions[2]['position_amount'] ?>
              <span class="gray">@<?php echo $spot_positions[2]['position_rate'] ?> </span></li>
            </ul>
          </div><!-- end spot-positions -->
        
        </section><!-- end top-main -->

         <section class="main-section blotters">
          <div class="main-section-content">

            <div class="table-container ticket-conv second">
              <div class="table-container-title">
                <h2>FX OPEN POSITIONS</h2>
              </div><!-- end table-container-title -->
              <table>
                <thead>
                  <tr>
                    <th class="first">CCY</th>
                    <th class="dark-bg">Open Amount<br><span>Currency</span></th>
                    <th>Open Amount<br><span>Reporting currency</span></th>
                    <th class="dark-bg">Position Limit</th>
                    <th>Rate</th>
                    <th class="last">RISK</th>
                  </tr>

                  <tr class="second-row">
                    <th class="first" colspan="6">
                      <ul>
                        <li id = "TER" class="green"><a href="#">TER</a></li>
                        <li id = "HAT"> <a href="#">HAT</a></li>
                        <li id = "RIK" class="last"><a href="#">RIK</a></li>
                      </ul>
                    </th>
                  </tr>
                </thead>

                <tbody>
                
             		<?php 
             			$i = 0 ; 
             			foreach ( $fx_positions as $row ) : 
             			$i ^= 1 ; 
             		?>
             		
				<script>
					var par = "<?=$i?>" ; 
					
					if( par == 0 ) 
						document.write("<tr class ='odd' > ") ;
					else
						document.write("<tr class = 'even' > ") ;
				</script>
				
				
					<td> <?php echo $row['ccy_name'] ?> </td> 
					<td class="dark-bg"> <?php echo $row['amount'] ?> </td> 
					<td>
						<div class = "hide TER"> <?php echo $row['rep_ccy'][0] ?> </div>
						<div class = "hide RIK" hidden = "hidden"> <?php echo $row['rep_ccy'][1] ?> </div>
						<div class = "hide HAT" hidden = "hidden"> <?php echo $row['rep_ccy'][2] ?> </div>
					</td>
					<td class="dark-bg"> 
						<div class = "hide TER"> <?php echo $row['limit'][0] ?> </div> 
						<div class = "hide RIK" hidden = "hidden"> <?php echo $row['limit'][1] ?> </div>
						<div class = "hide HAT" hidden = "hidden"> <?php echo $row['limit'][2] ?> </div>
					</td> 
			
					<td> 
						<div class = "hide TER"> <?php echo $row['rate'][0] ?> </div>
						<div class = "hide RIK" hidden = "hidden"> <?php echo $row['rate'][1] ?> </div>
						<div class = "hide HAT" hidden = "hidden"> <?php echo $row['rate'][2] ?> </div>
					</td>
					<td> 
						<div class = "hide TER"> <?php echo $row['risk'][0] ?> </div>
						<div class = "hide RIK" hidden = "hidden"> <?php echo $row['risk'][1] ?> </div>
						<div class = "hide HAT" hidden = "hidden"> <?php echo $row['risk'][2] ?> </div>
					</td> 
				</tr>
			<?php endforeach ?> 
	
			<tr class="even agg"> 
				<td> AGG </td>
				<td class="dark-bg"></td>
				<td> 
					<div class = "hide TER"> <?php echo $agg['rep_ccy'][0] ?> </div>
					<div class = "hide RIK" hidden = "hidden"> <?php echo $agg['rep_ccy'][1] ?> </div>
					<div class = "hide HAT" hidden = "hidden"> <?php echo $agg['rep_ccy'][2] ?> </div>
				</td>
				<td class="dark-bg"> 
					<div class = "hide TER"> <?php echo $agg['limit'][0] ?> </div>
					<div class = "hide RIK" hidden = "hidden"> <?php echo $agg['limit'][1] ?> </div>
					<div class = "hide HAT" hidden = "hidden"> <?php echo $agg['limit'][2] ?> </div>
				</td>
				<td> N/A </td>
				<td class="dark-bg"> 
					<div class = "hide TER"> <?php echo $agg['risk'][0] ?> </div>
					<div class = "hide RIK" hidden = "hidden"> <?php echo $agg['risk'][1] ?> </div>
					<div class = "hide HAT" hidden = "hidden"> <?php echo $agg['risk'][2] ?> </div>
				</td>	
			</tr>
                  
                </tbody>
              </table>
            </div><!-- end table-container ticket-conv first -->

          </div><!-- end main-section-content --> 

          <div class="sidebar">
            <div class="widget tier1">

              <div class="widget-title">
                <h2>TIER 1<span>(Last update 03:20 AM)</span></h2>
              </div><!-- end widget-title -->

              <div class="widget-content">
                <div class="widget-tier1-table">
                  <table>
                    <tbody>
                      <tr>
                      	<td>OWN CAPITAL</td>
                        <td>  
                        	<div class = "hide TER"> <?php echo $fx_positions[0]['ccy_name'] ?> </div>
				<div class = "hide RIK" hidden = "hidden"> <?php echo $fx_positions[1]['ccy_name'] ?> </div>
				<div class = "hide HAT" hidden = "hidden"> <?php echo $fx_positions[2]['ccy_name'] ?> </div>
                        </td>
                        <td class="last"> 
                        	<div class = "hide TER"> <?php echo $capital[0] ?> </div>
				<div class = "hide RIK" hidden = "hidden"> <?php echo $capital[1] ?> </div>
				<div class = "hide HAT" hidden = "hidden"> <?php echo $capital[2] ?> </div>
			</td>
                      </tr>
                      
                      <tr class="last">
                        <td>OWN FUNDS</td>
                        <td>
                                <div class = "hide TER"> <?php echo $fx_positions[0]['ccy_name'] ?> </div>
				<div class = "hide RIK" hidden = "hidden"> <?php echo $fx_positions[1]['ccy_name'] ?> </div>
				<div class = "hide HAT" hidden = "hidden"> <?php echo $fx_positions[2]['ccy_name'] ?> </div>

                        </td>
                        <td class="last"> <div class = "hide TER"> <?php echo $funds[0] ?> </div>
				<div class = "hide RIK" hidden = "hidden"> <?php echo $funds[1] ?> </div>
				<div class = "hide HAT" hidden = "hidden"> <?php echo $funds[2] ?> </div>
			 </td>
                      </tr>
                    </tbody>
                  </table>
                </div><!-- end widget-tier1-table -->

              </div><!-- end widget-content -->

            </div><!-- end widget tier1 -->

            <div class="widget-separator-two"></div>

             <div class="widget acc-central-bank">

              <div class="widget-title">
                <h2>Accounts with the <span>Central bank</span></h2>
              </div><!-- end widget-title -->

              <div class="widget-content">
                <div class="widget-acc-central-bank-table">
                  <table>
                    <tbody>
                    
                      
                      <?php for( $i = 0 ; $i < 3 ; $i++ ) { ?>
                                          
                      	<script> 
                      		var idx = <?=$i?> ; 
                      		
                      		if( idx % 2 == 0 ) 
                      			document.write( "<tr class = 'odd' >") ;
                      		else
                      			document.write( "<tr class = 'even' > ") ;
                      			
                      		var amount = <?=$banks_balances[$i]['banks_ccy_amount']?> ; 
                      		
                      		if( amount < 0 ) 
                      			document.write( "<td class = 'green-td' > <?php echo $banks_balances[$i]['ccy_name'] ?> </td> ") ; 
                      		else
                      			document.write( "<td> <?php echo $banks_balances[$i]['ccy_name'] ?> </td>" ) ;
                      			
                      		document.write( "<td class='second'> <?php echo $banks_balances[$i]['banks_ccy_amount'] ?> </td>" ) ;
                      		
                      		if( amount < 0 ) 
                      			document.write( "<td class = 'overdraft'> OVERDRAFT </td>" ) ;
                      		else
                      			document.write( "<td> </td>") ;
                      		
                      		document.write("</tr>");
                      	</script> 
                      
                      <?php } ?> 
                      
                      </tr>
                    </tbody>
                  </table>
                </div><!-- end widget-acc-central-bank-table -->

              </div><!-- end widget-content -->

            </div><!-- end widget acc-central-bank -->

          </div><!-- end sidebar -->

        </section><!-- end main-section -->

        <section class="main-section blotters">

          <div class="section-title">
            <h2>Blotter FX</h2>
            <select>
              <option>Filter</option>
              <option>Filter 1</option>
              <option>Filter 2</option>
              <option>Filter 3</option>
            </select>
          </div><!-- end section-title -->

            <div class="table-container blotter brown-gradient">
              <table>
                <thead>
                  <tr>
                    <th class="first">Period</th>
                    <th>CCY Pair</th>
                    <th>Amount base ccy</th>
                    <th>Price</th>
                    <th>Amount var ccy</th>
                    <th>Counter party</th>
                    <th>Value date</th>
                    <th>Trade date</th>
                    <th>Deal ID</th>
                    <th class="last">User ID</th>
                  </tr>
                </thead>

                <tbody>

 			<?php foreach ( $fx_deals as $deal ) : ?> 
		
				<tr>
					<td> <?php echo $deal['period_name'] ?> </td>
					<td> <?php echo $deal['first_currency'].'/'.$deal['second_currency'] ?> </td>
					<td> <?php echo $deal['amount_base_ccy'] ?> </td>
					<td> <?php echo $deal['price'] ?> </td>
					<td> <?php echo abs( $deal['amount_base_ccy'] * $deal['price'] ) ?> </td>
					<td> <?php echo $deal['counter_party_name'] ?> </td>
					<td> <?php echo date('d.m.y',$deal['value_date']) ?> </td>
					<td> <?php echo date('d.m.y',$deal['trade_date']) ?> </td>
					<td> <?php echo $deal['deal_id'] ?> </td>
					<td> <?php echo $deal['user_name'] ?> </td>
				</tr>
		
			<?php endforeach ?>
	
                </tbody>
              </table>
            </div><!-- end table-container brown-gradient -->

        </section><!-- end main-section -->

         <section class="main-section blotters">

          <div class="section-title">
            <h2>Blotter MM</h2>
            <select>
              <option>Filter</option>
              <option>Filter 1</option>
              <option>Filter 2</option>
              <option>Filter 3</option>
            </select>
          </div><!-- end section-title -->

            <div class="table-container blotter dark-brown-gradient">
              <table>
                <thead>
                  <tr>
                    <th class="first">Period</th>
                    <th>CCY </th>
                    <th>Amount base ccy</th>
                    <th>Price</th>
                    <th>Amount var ccy</th>
                    <th>Counter party</th>
                    <th>Value date</th>
                    <th>Trade date</th>
                    <th>Deal ID</th>
                    <th class="last">User ID</th>
                  </tr>
                </thead>

                <tbody>

                	<?php foreach ( $mm_deals as $deal ) : ?> 
		
				<tr>
					<td> <?php echo $deal['period_name'] ?> </td>
					<td> <?php echo $deal['ccy_name'] ?> </td>
					<td> <?php echo $deal['amount_base_ccy'] ?> </td>
					<td> <?php echo $deal['price'] ?> </td>
					<td> <?php echo abs( $deal['amount_base_ccy'] * $deal['price'] ) ?> </td>
					<td> <?php echo $deal['counter_party_name'] ?> </td>
					<td> <?php echo date('d.m.y',$deal['value_date']) ?> </td>
					<td> <?php echo date('d.m.y',$deal['trade_date']) ?> </td>
					<td> <?php echo $deal['deal_id'] ?> </td>
					<td> <?php echo $deal['user_name'] ?> </td>
				</tr>
			
			<?php endforeach ?>
	
                </tbody>
                
              </table>
            </div><!-- end table-container brown-gradient -->

        </section><!-- end main-section -->

      </article><!-- end main-content -->

    </article><!-- end container -->

</body>

<script src="<?php print base_url() ?>js/jquery.js"></script>

<script>
	
	$("#TER").click( function () {
					$(".hide").hide();
					$(".TER").show(); 
					$(this).addClass('green');
					$("#RIK").removeClass('green');
					$("#HAT").removeClass('green');
				});
	
			
	$("#HAT").click( function () {
					$(".hide").hide();
					$(".HAT").show(); 
					$(this).addClass('green');
					$("#TER").removeClass('green');
					$("#RIK").removeClass('green');
				});
	

	$("#RIK").click( function () {
					$(".hide").hide();
					$(".RIK").show(); 
					$(this).addClass('green');
					$("#TER").removeClass('green');
					$("#HAT").removeClass('green');
				});	
</script>			

</html>
