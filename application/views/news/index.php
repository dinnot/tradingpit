<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />

  <title>News - Trading Pit</title>
   <meta name="description" content="" />
  <link rel="stylesheet" href="<?php print base_url() ?>css/style.css" />
  <link href="<?php print base_url() ?>css/jquery-ui-1.9.1.custom.css" rel="stylesheet">
  <script src="<?php print base_url() ?>js/jquery-1.8.2.min.js"></script>
  <script src="<?php print base_url() ?>js/jquery-ui-1.9.1.custom.js"></script>
  <script src="<?php print base_url() ?>js/date.js"></script>
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <script>
    $(document).ready(function() {
      $('.table-container tbody tr').click(function() {
        $('.table-container tbody tr').removeClass('active');
        $(this).addClass('active');
      })
	  $('#body_filter').focus(function() {
		if($("#body_filter").val() == "SEARCH") {
			$("#body_filter").val("");
		} 
	  });
	  $("#body_filter").focusout(function() {
		if($("#body_filter").val() == "") {
			$("#body_filter").val("SEARCH");
		}
	  });
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

          <div class="search-input-container">
            <input type="text" id = "body_filter" class="silver-gradient search" value = "SEARCH">
          </div><!-- end upload-file-container -->

          <div class="select-container">
            <select id = "country_filter" class="silver-gradient">
            	<option value="0"> All countries</option>
						<option value="2"> Territory </option>		
						<option value="4"> Rikland </option>		
						<option value="5"> Hattonia </option>		
						
              </select>
          </div><!-- end upload-file-container -->
        </section><!-- end top-main -->
        <div class="table-container news">
          <table>
            <thead>
              <tr>
                <th class="first">Date</th>
                <th>Time</th>
                <th>Country</th>
                <th>Headline</th>
              </tr>
            </thead>

            <tbody id = "news_table">
            
           	 <?php foreach ($news as $news_item): ?>

			<tr>
				<td class="first"> <?php echo date("M-d",$news_item["date"]) ; ?>     </td>  
				<td class="red-td"> <?php echo date("H:i",$news_item["date"]) ; ?>     </td>
				<td> <?php echo $news_item["country_name"] ;  ?>     </td>  
				<td>	
					<div class = "show"> <?php echo $news_item["headline"] ; ?> </div>
					<div class = "hide" hidden = "hidden"> <?php echo $news_item["body"] ?> </div> 
				</td>
				
			</tr>
		
		<?php endforeach ?>
              
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

<script src="<?php print base_url() ?>js/jquery.js">  </script>
<script src="<?php print base_url() ?>js/news.js">  </script>

</html>
