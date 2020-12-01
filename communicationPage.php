<?php
include 'connect.php';
$users = $_GET['u'];
$users='userId';
?>
<html>
<head>
<title>Chatter</title>
<link rel='stylesheet' href='communicationPage.css' />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
</head>
<body>
<div class='chatContainer'>
<div class='chatHeader'>
<h3>Welcome <?php echo ucwords($users)?></h3>
</div>
<div class='chatMessages'></div>
<div class='chatBottom'>
<form action="#" onSubmit='return false;' id='chatForm'>
<input type='hidden' id='usersId' value='<?php echo $users;?>'/>
<input type='text' name='messageContent' id='messageContent' value='' placeholder='type your chat message' />
<input type='submit' name='submit' value='Post' />
</form>
</div>
</div>
</body>
</html>