<?php
header("Content-Security-Policy: script-src 'https://localhost/SWAPWebsite'; "); //Starts Content Security Policy to protect any remote code execution
include 'connection.php';
$usersId='usersId';
$username = $_GET['username']; //to display username
?>

<html>
<head>
<title>Chat</title>
<link rel='stylesheet' href='communicationPage.css' />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
<div class="chatContainer">
<div class="chatHeader">
<h3>Welcome <?php echo ucwords($username)?></h3>
<div class="chatMessages">
<div class="chatBottom">
<form method="post" onSubmit="return false;" id="message">
<input type="hidden" name="usersId" id="usersId" value="<?php echo $username;?>">
<input type="text" name="messageContent" id="messageContent" value="" placeholder="Type your chat message">
<input type="submit" name="submit" value="POST">
<?php
//Getting messages
$query=$conn->prepare("SELECT * FROM message WHERE messageContent=?");
$query->execute();

//Fetch
while ($fetch=$query->fetch()) {
    $username = $fetch['username'];
    $messageContent = $fetch['messageContent'];
    
    echo "<li class='cm'><b>".ucwords($username)."</b> - ".$messageContent."</li>";
    
    //Secure the chat
    if (isset($_POST['messageContent']) && isset($_POST['username'])) {
        $messageContent = strip_tags(stripcslashes($_POST['messageContent']));
        $username = strip_tags(stripcslashes($_POST['username']));
        
        if(empty($messageContent)) {
            $insert=$conn->prepare("INSERT INTO message VALUES (?,?,?,?,?,?,?)");
            $insert->execute();
            
            echo "<li class='cm'><b>".ucwords($username)."</b> - ".$messageContent."</li>";
        }
    }
}
?>
</form>
</div>
</div>
</div>
</div>
</body>
</html>