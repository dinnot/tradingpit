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
          
					<?php include_once ($_SERVER['DOCUMENT_ROOT']."/tradingpit/application/views/menu.php"); ?>
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

		

            </tbody>
          </table>
          <span class="table-bottom-shadow"></span>
        </div><!-- end economy-table-container -->

      </article><!-- end main-content -->

    </article><!-- end container -->

</body>
	<script>
		var base_url = "<?= base_url() ?>";
	</script>
  <script src="<?php print base_url() ?>js/econ.js"></script>

</html>
