<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />

  <title>Messages</title>
   <meta name="description" content="" />
  <link href="<?php print base_url () ?>css/jquery-ui-1.9.1.custom.css" rel="stylesheet">
  <script src="<?php print base_url () ?>js/jquery-1.8.2.min.js"></script>
  <script src="<?php print base_url () ?>js/jquery-ui-1.9.1.custom.js"></script>
	<script src="<?php print base_url () ?>js/messages.js"></script>
  <script src="<?php print base_url() ?>js/date.js"></script>

<style>
#chat_box {
	background: #fff;
	border:1px solid; 
	padding-right:5px; 
	width:300px;
	position: fixed;
	bottom: 10px;
	right: 10px;
	height:0px;
	overflow:auto;
}
</style>

</head>

<body style="background:#ccc">
				Your conversations
				<ul id="conversations">
				</ul> 
				
				<div id="chat_box" >					
				</div>
				
				<div id="new_conversation">
					
				</div>
				
	    </article><!-- end container -->

</body>


<script>
	var username = '<?= $username ?>';
	var base_url = "<?= base_url() ?>";

	get_conversations ();
</script>


</html>
