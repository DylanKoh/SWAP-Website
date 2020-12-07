<?php
include 'connection.php';
$users='userId';
$username='name';
$username = $_GET['u']; //to display username
?>

<html>
<head>
<title>Chat</title>
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
<form method="post" onSubmit='return false;' id='message'>
<input type='hidden' id='usersId' value='<?php echo $username ;?>'/> 
<input type='text' name='messageContent' id='messageContent' value='' placeholder='type your chat message' />
<input type='submit' name='submit' value='Post' />

<?php 
include 'connection.php';
//Getting messages
$query=$conn->prepare("SELECT * FROM message");
$query->execute();

//Fetch
while($fetch=$query->fetch(PDO::FETCH_ASSOC)){
    $username = $fetch['username'];
    $messageContent = $fetch['messageContent'];
    
    echo "<li class='cm'><b>".ucwords($name)."</b> - ".$text."</li>";

//Secure the chat
    if(isset($_POST['messageContent']) && isset($_POST['username'])){
        $messageContent = strip_tags(stripslashes(($_POST['messageContent'])));
        $username = strip_tags(stripslashes(($_POST['username'])));
        
        if(empty($messageContent) OR empty($username))
        {
            $insert=$conn->prepare("INSERT INTO message VALUES('',.$messageContent.,'','','','','')");
            $insert->execute();
            
            echo "<li class='cm'><b>".ucwords($username)."</b> - ".$messageContent."</li>";
        }
    }  
?>
</form>
</div>
</div>
</body>
</html>