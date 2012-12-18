<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />

  <title>Trading Page - Trading Pit</title>
   <meta name="description" content="" />
  <link rel="stylesheet" href="<?php print base_url() ?>css/style.css" />
 
  <script> var base_url = "<?= base_url() ?>"; </script>
  <script src="<?php print base_url() ?>js/jquery-1.8.2.min.js"></script>
  <script src="<?php print base_url() ?>js/jquery-ui-1.9.1.custom.js"></script>
  <script src="<?php print base_url() ?>js/jquery.js"></script>

 
  <script src="<?php print base_url () ?>js/observable.js"></script>
  <script src="<?php print base_url () ?>js/corporate_clients.js"></script>
  <script src="<?php print base_url () ?>js/retail_clients.js"></script>
   <script src="<?php print base_url() ?>js/blotters.js"></script>
  <link href="<?php print base_url() ?>css/jquery-ui-1.9.1.custom.css" rel="stylesheet">
   
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  
  <!--
  <script>
  $(function() {
    $( "#datepickerStart, #datepickerEnd" ).datepicker({
      showOn: "button",
      buttonImage: "images/calendar-icon.png",
      buttonImageOnly: true
    });
  });
  </script>
  -->
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
					<?php include_once ($_SERVER['DOCUMENT_ROOT']."/tradingpit/application/views/menu.php"); ?>
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
            	<span id = "TRPA"> <?php echo $spot_positions[0]['position_amount'] ?> </span>
              	<span id = "TRPR" class="gray">@<?php echo round($spot_positions[0]['position_rate'],4) ?> </span></li>
              
              <li>
              	<span class="green">HAT/RIK</span> 
              	<span id = "HRPA"> <?php echo $spot_positions[1]['position_amount'] ?> </span>
                <span id = "HRPR" class="gray">@<?php echo round($spot_positions[1]['position_rate'],4) ?> </span></li>
              
              <li class="last">
              	<span class="green">HAT/TER</span>
              	<span id = "HTPA"> <?php echo $spot_positions[2]['position_amount'] ?> </span>
                <span id = "HTPR" class="gray">@<?php echo round($spot_positions[2]['position_rate'],4) ?> </span></li>
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
             			$i = -1 ; 
             			foreach ( $fx_positions as $row ) : 
             			$i++ ; 
             		?>
             		
				<script>
					var par = "<?=$i?>" ; 
					
					if( par % 2 == 0 ) 
						document.write("<tr class ='odd' > ") ;
					else
						document.write("<tr class = 'even' > ") ;
				</script>
				
				
					<td id = "PCN<?=$i?>"> <?php echo $row['ccy_name'] ?> </td> 
					<td id = "PAM<?=$i?>" class="dark-bg"> <?php echo $row['amount'] ?> </td> 
					<td>
						<div id = "PRC<?=$i?>0" class = "hide TER"> <?php echo $row['rep_ccy'][0] ?> </div>
						<div id = "PRC<?=$i?>1" class = "hide RIK" hidden = "hidden"> <?php echo $row['rep_ccy'][1] ?> </div>
						<div id = "PRC<?=$i?>2" class = "hide HAT" hidden = "hidden"> <?php echo $row['rep_ccy'][2] ?> </div>
					</td>
					<td class="dark-bg"> 
						<div id = "PL<?=$i?>0" class = "hide TER"> <?php echo $row['limit'][0] ?> </div> 
						<div id = "PL<?=$i?>1" class = "hide RIK" hidden = "hidden"> <?php echo $row['limit'][1] ?> </div>
						<div id = "PL<?=$i?>2" class = "hide HAT" hidden = "hidden"> <?php echo $row['limit'][2] ?> </div>
					</td> 
			
					<td> 
						<div id = "PRT<?=$i?>0" class = "hide TER"> <?php echo $row['rate'][0] ?> </div>
						<div id = "PRT<?=$i?>1" class = "hide RIK" hidden = "hidden"> <?php echo $row['rate'][1] ?> </div>
						<div id = "PRT<?=$i?>2" class = "hide HAT" hidden = "hidden"> <?php echo $row['rate'][2] ?> </div>
					</td>
					<td class="dark-bg"> 
						<div id = "PRK<?=$i?>0" class = "hide TER"> <?php echo $row['risk'][0] ?> </div>
						<div id = "PRK<?=$i?>1" class = "hide RIK" hidden = "hidden"> <?php echo $row['risk'][1] ?> </div>
						<div id = "PRK<?=$i?>2" class = "hide HAT" hidden = "hidden"> <?php echo $row['risk'][2] ?> </div>
					</td> 
				</tr>
			<?php endforeach ?> 
	
			<tr class="even agg"> 
				<td> AGG </td>
				<td class="dark-bg"></td>
				<td> 
					<div id = "ARC0" class = "hide TER"> <?php echo $agg['rep_ccy'][0] ?> </div>
					<div id = "ARC1" class = "hide RIK" hidden = "hidden"> <?php echo $agg['rep_ccy'][1] ?> </div>
					<div id = "ARC2" class = "hide HAT" hidden = "hidden"> <?php echo $agg['rep_ccy'][2] ?> </div>
				</td>
				<td class="dark-bg"> 
					<div id = "AL0" class = "hide TER"> <?php echo $agg['limit'][0] ?> </div>
					<div id = "AL1" class = "hide RIK" hidden = "hidden"> <?php echo $agg['limit'][1] ?> </div>
					<div id = "AL2" class = "hide HAT" hidden = "hidden"> <?php echo $agg['limit'][2] ?> </div>
				</td>
				<td> N/A </td>
				<td class="dark-bg"> 
					<div id = "ARK0" class = "hide TER"> <?php echo $agg['risk'][0] ?> </div>
					<div id = "ARK1" class = "hide RIK" hidden = "hidden"> <?php echo $agg['risk'][1] ?> </div>
					<div id = "ARK2" class = "hide HAT" hidden = "hidden"> <?php echo $agg['risk'][2] ?> </div>
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
                        	<div class = "hide TER"> TER </div>
				<div class = "hide RIK" hidden = "hidden"> RIK </div>
				<div class = "hide HAT" hidden = "hidden"> HAT </div>
                        </td>
                        <td class="last"> 
                        	<div id = "TERCAP" class = "hide TER"> <?php echo $capital[0] ?> </div>
				<div id = "RIKCAP" class = "hide RIK" hidden = "hidden"> <?php echo $capital[1] ?> </div>
				<div id = "HATCAP" class = "hide HAT" hidden = "hidden"> <?php echo $capital[2] ?> </div>
			</td>
                      </tr>
                      
                      <tr class="last">
                        <td>OWN FUNDS</td>
                        <td>
                                <div class = "hide TER"> TER </div>
				<div class = "hide RIK" hidden = "hidden"> RIK </div>
				<div class = "hide HAT" hidden = "hidden"> HAT </div>

                        </td>
                        <td class="last"> 
                        	<div id = "TERFUND" class = "hide TER"> <?php echo $funds[0] ?> </div>
				<div id = "RIKFUND" class = "hide RIK" hidden = "hidden"> <?php echo $funds[1] ?> </div>
				<div id = "HATFUND" class = "hide HAT" hidden = "hidden"> <?php echo $funds[2] ?> </div>
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
                    
                      <tr class = "odd">
                      	<td id = "TERGR"> TER </td>
                      	<td id = "TERACB" class='second'> <?php echo $banks_balances[0]['banks_ccy_amount'] ?> </td>
                      	<td id = "TEROVD"> </td>
                      </tr>

		     <tr class = "even">
                      	<td id = "RIKGR"> RIK </td>
                      	<td id = "RIKACB" class ='second'> <?php echo $banks_balances[1]['banks_ccy_amount'] ?> </td>
                      	<td id = "RIKOVD"> </td>
                      </tr>
                      
		      <tr class = "odd">
                      	<td id = "HATGR"> HAT </td>
                      	<td id = "HATACB" class='second'> <?php echo $banks_balances[2]['banks_ccy_amount'] ?> </td>
                      	<td id = "HATOVD"> </td>
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

 			<?php 
 				$i = -1 ; 
 				foreach ( $fx_deals as $deal ) : 
 				$i++;
 			?> 
		
				<tr>
					<td id = "FX<?=$i?>0"> <?php echo $deal['period_name'] ?> </td>
					<td id = "FX<?=$i?>1"> <?php echo $deal['first_currency'].'/'.$deal['second_currency'] ?> </td>
					<td id = "FX<?=$i?>2"> <?php echo $deal['amount_base_ccy'] ?> </td>
					<td id = "FX<?=$i?>3"> <?php echo $deal['price'] ?> </td>
					<td id = "FX<?=$i?>4"> <?php echo -$deal['amount_base_ccy'] * $deal['price']  ?> </td>
					<td id = "FX<?=$i?>5"> <?php echo $deal['counter_party_name'] ?> </td>
					<td id = "FX<?=$i?>6"> <?php echo date('d.m.y',$deal['value_date']) ?> </td>
					<td id = "FX<?=$i?>7"> <?php echo date('d.m.y',$deal['trade_date']) ?> </td>
					<td id = "FX<?=$i?>8"> <?php echo $deal['deal_id'] ?> </td>
					<td id = "FX<?=$i?>9"> <?php echo $deal['user_name'] ?> </td>
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

                	<?php 
                		$i = -1 ; 
                		foreach ( $mm_deals as $deal ) : 
                		$i++;
                	?> 
		
				<tr>
					<td id = "MM<?=$i?>0"> <?php echo $deal['period_name'] ?> </td>
					<td id = "MM<?=$i?>1"> <?php echo $deal['ccy_name'] ?> </td>
					<td id = "MM<?=$i?>2"> <?php echo $deal['amount_base_ccy'] ?> </td>
					<td id = "MM<?=$i?>3"> <?php echo $deal['price'] ?> </td>
					<td id = "MM<?=$i?>4"> <?php echo -$deal['amount_base_ccy'] * $deal['price']  ?> </td>
					<td id = "MM<?=$i?>5"> <?php echo $deal['counter_party_name'] ?> </td>
					<td id = "MM<?=$i?>6"> <?php echo date('d.m.y',$deal['value_date']) ?> </td>
					<td id = "MM<?=$i?>7"> <?php echo date('d.m.y',$deal['trade_date']) ?> </td>
					<td id = "MM<?=$i?>8"> <?php echo $deal['deal_id'] ?> </td>
					<td id = "MM<?=$i?>9"> <?php echo $deal['user_name'] ?> </td>
				</tr>
			
			<?php endforeach ?>
	
                </tbody>
                
              </table>
            </div><!-- end table-container brown-gradient -->

        </section><!-- end main-section -->

      </article><!-- end main-content -->

    </article><!-- end container -->

</body>

</html>
