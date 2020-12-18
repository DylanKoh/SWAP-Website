<?php
header("Content-Security-Policy:script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");

include 'connection.php';


require_once 'sessionInitialise.php';
require_once 'validateToken.php';

$_SESSION ['isSending'] = 1;
$_SESSION ['isReceiving'] = 0;
$_SESSION ['providersFkid'] = 1;

if(!isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){ //If user does not have any ID in their session
    destroySession();
    header('Location:login.php?error=notloggedin');
    exit();
}
else{ //If an ID of sorts is assigned in the session variables
    if (!verifyToken('authToken', 1200)){ //If token is not valid
        destroySession();
        if (isset($_SESSION['providersID'])){ //If initial user is a Provider
            header('Location:providerLogin.php?error=errToken');
            exit();
        }
        else{ //If initial user is a Customer
            header('Location:login.php?error=errToken');
            exit();
        }
    }
    else{
        $authToken=$_POST['authToken'];
        if (!verifyToken('commToken', 600)){
            unsetVariable('commToken');
            unsetVariable('commToken');
            echo "<form action='storePage.php?error=commTimeout' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
            exit();
        }
        $commToken=$_POST['commToken'];
    }
}
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
<h3>Welcome</h3>
<div class="chatMessages">
<div class="chatBottom">
<form action="communicationPage.php" method="post">
<input type="hidden" name="usersID" id="usersID" value="<?php echo $_SESSION['username'];?>">
<input type="hidden" name="commToken" value="<?php echo $commToken?>">
<input type="hidden" name="authToken" value="<?php echo $authToken?>">
<input type="text" name="messageContent" value="" placeholder="Type your chat message">
<input type="submit" name="update" value="update">
<input type="submit" name="submit" value="submit">

<?php
if (isset($_SESSION)){             //User unable to type message if they are not logged in
    if (isset($_POST['submit'])) {
        if(!empty(htmlspecialchars($_POST['messageContent'])))
    {
        $isSending = 1;
        $isReceiving = 0;
            $query=$conn->prepare("INSERT INTO `message`(`messageId`, `messageContent`, `usersFkid`, `providersFkid`, `isSending`, `isReceiving`) VALUES (?,?,?,?,?,?)");
            $query->bind_param("isiiii",$messageId,$_POST["messageContent"],$_SESSION['usersID'],$_SESSION['providersFkid'],$isSending,$isReceiving);
            if ($query->execute()){
                $print=$conn->prepare("SELECT messageContent FROM `message` where `usersFkid`=? AND `providersFkid`=? AND `isSending`=? AND `isReceiving`=? Order By `messageId` DESC");
                $print->bind_param("iiii",$_SESSION["usersID"],$_SESSION["providersFkid"],$isSending,$isReceiving);
                $print->execute();
                $print->bind_result($messageContent);
     
                while($print->fetch()){
                echo "<br>". htmlspecialchars($messageContent); //prevents script from running by just echoing script/XSS
                }
                echo $query->error;
            }else {
                echo "Unable to insert!";
                echo $query->error;
            }
        }
    }elseif (isset($_POST['update'])){
        $update=$conn->prepare("UPDATE message SET `messageContent`=? WHERE `usersFkid`=?");
        $update->bind_param("si",$_POST["messageContent"],$_SESSION["usersID"]);
        if ($update->execute()){
            $isSending = 1;
            $isReceiving = 0;
            $printout=$conn->prepare("SELECT messageContent FROM `message` where `messageId`=? AND `usersFkid`=? AND `providersFkid`=? AND `isSending`=? AND `isReceiving`=? Order By `messageId` DESC");
            $printout->bind_param("iiiii",$_POST["messageId"],$_SESSION["usersID"],$_SESSION["providersFkid"],$isSending,$isReceiving);
            $printout->execute();
            $printout->bind_result($messageContent);
            while($printout->fetch()){
                echo "<br>". htmlspecialchars($messageContent); //prevents script from running by just echoing script/XSS
            }
            echo $printout->error;
        }
    }
    
    else
    {
        echo "Message cannot be empty<br>";
    }
}
?>
</form>
</div>
</div>
</div>
</div>
<form action="CommunicationPage.php" method="post">
<input type="submit" name="delete" value="delete">
<input type="hidden" name="commToken" value="<?php echo $commToken?>">
<input type="hidden" name="authToken" value="<?php echo $authToken?>">
<input type="hidden" name="messageContent" value="<?php echo $messageContent?>">
<?php 
if (isset($_SESSION)) {
    if (isset($_POST['delete'])){
        $delete=$conn->prepare("DELETE FROM `message` where `messageContent`=? AND `usersFkid`=?");
        $delete->bind_param("si",$_POST["messageContent"],$_SESSION["usersID"]);
        $delete->execute();
        
    }
}else{
    die("Access Forbidden");
    }
?>
</form>

</body>
</html>