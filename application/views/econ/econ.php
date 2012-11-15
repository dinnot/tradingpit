<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />

  <title>Economic Indicators - Trading Pit</title>
  <meta name="description" content="" />
  <link rel="stylesheet" href="<?php print base_url() ?>css/style.css" />
  <link href="<?php print base_url() ?>css/jquery-ui-1.9.1.custom.css" rel="stylesheet">
  <script src="<?php print base_url() ?>js/jquery-1.8.2.min.js"></script>
  <script src="<?php print base_url() ?>js/date.js"></script>
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
                <a href="#" class="first-level current">Calendars</a>
                <ul>
                  <li><a href="#">Auction calendar</a></li>
                  <li><a href="#">Economic calendar</a></li>
                </ul>
              </li>

              <li class="level-one">
                <a href="#" class="first-level">Settings</a>
              </li>

               <li class="level-one">
                <a href="#" class="first-level">Alerts</a>
              </li>

               <li class="level-one">
                <a href="#" class="first-level">Export</a>
              </li>

               <li class="level-one">
                <a href="#" class="first-level light-blue">Your forecast</a>
              </li>

               <li class="level-one last">
                <a href="#" class="first-level light-blue">Economic Calendars</a>
              </li>

            </ul>
            <div class="date-time-info">
              <ul>
<<<<<<< HEAD
                 <li class="first" id="time"></li>
                <li id="date"></li>
=======
                <li class="first">13:34 PM</li>
                <li>10/14/2012</li>
>>>>>>> 645b72aa00d6309b7bd3881b1d250266081f2d06
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

        <div class="upload-file-container">
          <input type="text" class="silver-gradient" value="Event Name" id="event_filter" />
        </div><!-- end upload-file-container -->

        <div class="select-container">
          <select class="silver-gradient" id="type_filter">
						<option value="0">All types</option>
						<?php foreach ($econlevels as $item): ?>
								<option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>		
						<?php endforeach; ?>
					</select>
        </div><!-- end upload-file-container -->
        
        <div class="datepicker-container">
          <input type="text" id="datepickerStart" class="silver-gradient"/><span>-</span> 
          <input type="text" id="datepickerEnd" class="silver-gradient" />
        </div><!-- end datepicker-container -->
</section><!-- end top-main -->
        <div class="radio-buttons-container">
          <input type="radio">
          <span class="label">Views</span>
          <input type="radio">
          <span class="label">Agenda</span>
          <input type="radio">
          <span class="label last">Weekly</span>
        </div><!-- end radio-buttons-container -->


        <div class="table-container economy">
          <table>
            <thead>
              <tr>
                <th class="first">#</th>
                <th>Date</th>
                <th>Time</th>
                <th>C</th>
                <th>A</th>
                <th>R</th>
                <th>Event</th>
                <th>Period</th>
                <th>Survey</th>
                <th>Actual</th>
                <th>Prior</th>
                <th>Forecast</th>
              </tr>
            </thead>

            <tbody id="econforcasts_table">

		
		<?php foreach ($econforcasts as $item): ?>
		
			<tr>
			<td class="first"><?=$item['id']?></td>
			<td class="blue-td"><?=date('m/d', $item['date'])?></td> 
			<td class="blue-td"><?=date('g:i', $item['date'])?></td>
			<td class="blue-td"><?=$item['countries_name']?></td>
			<td></td>
			<td></span></td>
			<td class="blue-td"><?=$item['econindicators_name']?></td>
			<td></td>
			<td></td>
			<td class="blue-td"><?=$item['actual']?></td>
			<td class="blue-td"><?=$item['prior']?></td>
			<td class="blue-td"><?=$item['forecast']?></td>
			</tr>
		
		<?php endforeach; ?>
		

            </tbody>
          </table>
          <span class="table-bottom-shadow"></span>
        </div><!-- end economy-table-container -->

      </article><!-- end main-content -->

    </article><!-- end container -->

</body>

  <script src="<?php print base_url() ?>js/econ.js"></script>

</html>
