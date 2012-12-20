<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />

  <title>Messages</title>
  
	<script>
		var username = "<?= $username  ?>";
		var base_url = "<?= base_url() ?>";
	</script>

  <meta name="description" content="" />
  <link href="<?php print base_url () ?>css/jquery-ui-1.9.1.custom.css" rel="stylesheet">
  
  <script src="<?php print base_url () ?>js/jquery-1.8.2.min.js"></script>
  <script src="<?php print base_url () ?>js/jquery-ui-1.9.1.custom.js"></script>
  <script src="<?php print base_url () ?>js/date.js"></script>

  <script src="<?php print base_url () ?>js/observable.js"></script>
	
	<script src="<?php print base_url () ?>js/messages/conversations.js"></script>
	<script src="<?php print base_url () ?>js/messages/messages.js"></script>
	<script src="<?php print base_url () ?>js/corporate_clients.js"></script>
	<script src="<?php print base_url () ?>js/retail_clients.js"></script>

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
	<hr />

	New conversation
	<div id="new_conversation">
		User : <input type="text" id="username" />	<br />
		Subject : <input type="text" id="subject" /> <br />
		Message : <textarea id="message"></textarea> <br />
		<button onclick=add_conversation()>Send</button>
	</div>
</body>

</html>
