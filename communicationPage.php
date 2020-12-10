<?php
include 'connection.php';
$usersId='usersId';
$username="SELECT username FROM users";
$username = $_GET['username']; //to display username
?>

<html>
<head>
<title>Chat</title>
<link rel="stylesheet" href="communicationPage.css">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css">
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
<header>
<div class="container">
<img src="logo.png" alt="logo" class="logo">
<nav>
<ul>
<li><a href="index.php">Home</a></li>
</ul>
</nav>
</div>
</header>

<div class="chatContainer">
<div class="chatHeader">
<h3>Welcome <?php echo ucwords($username)?></h3>
<div class="chatMessages">
<div class="chatBottom">
<form action="communicationPage.php" method="post" onSubmit="return false;">
<input type="hidden" name="usersId" id="usersId" value="<?php echo $username;?>">
<input type="text" name="messageContent" id="messageContent" value="" placeholder="Type your chat message">
<input type="submit" name="submit" value="submit">

<?php
if (isset($_GET['submit'])) {
    $query=$conn->prepare("SELECT 'messageContent' FROM 'message' WHERE messageContent='?'");
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    $res=$query->execute();
    $query->store_result();
    $stmt->bind_result($messageId,$messageContent,$usersFkid,$providersFkid,$isSending,$isReceiving,$messageDate);
    echo $messageContent["messageContent"];
    while ($query->fetch()) {
        echo $messageContent["messageContent"];
    }
}else {
    $messageId = $_POST["messageId"];
    $messageContent = $_POST["messageContent"];
    $usersFkid = $_POST["usersFkid"];
    $providersFkid = $_POST["providersFkid"];
    $isSending = $_POST["isSending"];
    $isReceiving = $_POST["isReceiving"];
    $messageDate = $_POST["messageDate"];
    
    echo "Message:" . $messageContent ."<br>";
    
    if (isset($_POST['submit'])) {
        $query=$conn->prepare("INSERT INTO message VALUES (?,?,?,?,?,?,?)");
        $query->bind_param("itiiiid",$messageId,$messageContent,$usersFkid,$providersFkid,$isSending,$isReceiving,$messageDate);
        $res=$query->execute();
        if ($res){
            echo  "Insert successfully!";
        }else {
            echo "Unable to insert!";
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