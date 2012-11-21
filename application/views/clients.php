<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />

  <title>Trading Page - Trading Pit</title>
   <meta name="description" content="" />
  <link rel="stylesheet" href="<?php print base_url () ?>css/style.css" />
  <link href="<?php print base_url () ?>css/jquery-ui-1.9.1.custom.css" rel="stylesheet">
  <script src="<?php print base_url () ?>js/jquery-1.8.2.min.js"></script>
  <script src="<?php print base_url () ?>js/jquery-ui-1.9.1.custom.js"></script>
	<script src="<?php print base_url () ?>js/corporate_clients.js"></script>
		<script src="<?php print base_url () ?>js/retail_clients.js"></script>

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
                <a href="#" class="first-level light-blue current">Clients</a>
              </li>

               <li class="level-one">
                <a href="#" class="first-level light-blue">Blotters</a>
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

        <section class="main-section corporate-clients">

          <div class="section-title">
            <h2>Corporate Clients</h2>
          </div><!-- end section-title -->
          <div class="main-section-inside">

            <div class="table-container light-green">
              <table>
                <thead>
                  <tr>
                    <th class="first">Code</th>
                    <th>Client name</th>
                    <th>Market</th>
                    <th>Amount</th>
                    <th>Ccy (pair)</th>
                    <th>Deal</th>
                    <th>Period</th>
                    <th class="last">Quote</th>
                  </tr>
                </thead>

                <tbody id="corporate_clients">

                </tbody>
              </table>
            </div><!-- end table-container light-green --> 
            <div class="send-box">
              <ul id="status_list">
              </ul>
            </div><!-- end send-box -->
          </div><!-- end main-section-inside -->
        </section><!-- end main-section -->

        <section class="main-section corporate-clients middle">
         	<?php
							include_once ("retail.php");
						?>
						
         </section><!-- end main-section -->

        <section class="main-section corporate-clients dark-bg">
          <div class="main-section-inside">
            <div class="client-position">
              <div class="section-title-table-container">
                <div class="section-title">
                  <h2><span class="text-container"><span class="green-square"></span>Positions</span></h2>
                </div><!-- end section-title -->
                  <div class="ticket-conv-tabs">
                    <ul>
                      <li class="current"><a href="#">Ticket/Conv</a></li>
                      <li><a href="#">Filters</a></li>
                    </ul>
                  </div><!-- end ticket-conv-tabs -->

                  <div class="table-container ticket-conv first">
                    <table>
                      <thead>
                        <tr>
                          <th class="first">Code</th>
                          <th>Date</th>
                          <th>Time</th>
                          <th>User</th>
                          <th>Swift</th>
                          <th>Period</th>
                          <th>Deal</th>
                          <th>Rate</th>
                          <th>Volume</th>
                          <th>Status</th>
                          <th>Maker</th>
                          <th class="last">Taker</th>
                        </tr>
                      </thead>

                      <tbody>

                        <tr class="first">
                          <td>BFBZ</td>
                          <td>05 JUL</td>
                          <td>13:33</td>
                          <td>FLRN</td>
                          <td>HAT/RIK</td>
                          <td>SPOT</td>
                          <td>SELL</td>
                          <td>4.1180</td>
                          <td>2MIO</td>
                          <td>123456</td>
                          <td>BFBZ</td>
                          <td>AATK</td>
                        </tr>

                        <tr class="last">
                          <td>HSAN</td>
                          <td>05 JUL</td>
                          <td>13:11</td>
                          <td>FLRN</td>
                          <td>TER/RIK</td>
                          <td>SPOT</td>
                          <td>BUY</td>
                          <td>3.9245</td>
                          <td>2MIO</td>
                          <td>EB1234</td>
                          <td>AATK</td>
                          <td>HSAN</td>
                        </tr>

                      </tbody>
                    </table>
                  </div><!-- end table-container ticket-conv first -->
                </div><!-- end section-title-table-container -->
                <div class="daily-total-dark-bg">
                  <div class="daily-total-table-container">
                    <table>
                      <thead>
                        <tr>
                          <th class="first"></th>
                          <th class="second">Daily</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="first">Fx</td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td class="first">MM</td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td class="first">PnL</td>
                          <td></td>
                          <td></td>
                        </tr>
                      </tbody>
                    </table>
                  </div><!-- end daily-total-table-container -->
                </div><!-- end daily-total-dark-bg -->
              </div><!-- end client-position -->
          </div><!-- end main-section-inside -->
        </section><!-- end main-section -->

    </article><!-- end container -->

</body>


<script>
	var user_id = <?= $user_id ?>;
	var base_url = "<?= base_url() ?>";

	set_time_difference ();
	get_clients_offers ();
</script>


</html>
