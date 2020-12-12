<?php
include 'connection.php';

session_start();
print_r($_SESSION);
$_SESSION['usersFkid'] = 1;
$_SESSION['providersFkid'] = 1; 
?>

<html>
<head>
<title>Chat</title>
<link rel="stylesheet" href="communicationPage.css">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css">
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel='stylesheet' href='css/communicationPage.css' />
<link href='css/fontsgoogleapiscomfamilyOpenSans400300600700800.css' rel='stylesheet' type='text/css'>
<script src="css/codejquery1110.js"></script>
<script src="css/bootstrapcdn452.js"></script>
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
<h3>Welcome <?php echo $_GET['username']?></h3>
<div class="chatMessages">
<div class="chatBottom">
<form action="communicationPage.php" method="post">
<input type="hidden" name="usersId" id="usersId" value="<?php echo $username;?>">
<input type="text" name="messageContent" value="" placeholder="Type your chat message">
<input type="submit" name="submit" value="submit">

<?php
if (isset($_SESSION)){
    if (isset($_POST['submit'])) {
        if(!empty(htmlspecialchars($_POST['messageContent'])))
    {
            $query=$conn->prepare("INSERT INTO `message`(`messageId`, `messageContent`, `usersFkid`, `providersFkid`, `isSending`, `isReceiving`) VALUES (?,?,?,?,?,?)");
            $query->bind_param("isiiii",$messageId,$_POST["messageContent"],$_SESSION['usersFkid'],$_SESSION['providersFkid'],$isSending,$isReceiving);
            if ($query->execute()){
                $print=$conn->prepare("SELECT messageContent FROM `message` where `usersFkid`=? AND `providersFkid`=? Order By `messageId` DESC");
                $print->bind_param("ii",$_SESSION["usersFkid"],$_SESSION["providersFkid"]);
                $print->execute();
                $print->bind_result($messageContent);
     
                while($print->fetch()){
                echo "<br>". htmlspecialchars($messageContent); //prevents script from running by just echoing script/XSS
                }
                echo $query->error;
            }else {
                echo "Unable to insert!";
            }
        }
    }
    else
    {
        echo "Message cannot be empty<br>";
    }
?>
</form>
<form action="CommunicationPage.php" method="post">
<input type="submit" name="delete" value="delete">
<?php 
if (isset($_POST['delete'])){
    echo $_POST["messageContent"];
    $delete=$conn->prepare("DELETE FROM `message` where `messageContent`='? AND `usersFKid`=?");
    $delete->bind_param("si",$_POST["messageContent"],$_SESSION['userid']);
    $delete->execute();
    $delete->bind_result($messageContent);
    $delete->fetch();
    echo $delete->error;
}
}else{
    die("Access Forbidden");
}
?>
</form>
</div>
</div>
</div>
</div>
</body>
</html>