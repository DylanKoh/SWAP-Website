<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php';
if (!isset($_SESSION['usersID'])){ //Check if token for creating account is not valid
    header('HTTP/1.0 403 Forbidden');
    exit();
}
if(!isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){
    destroySession();
    header('Location:login.php?error=notloggedin');
    exit();
}
else{
    if (isset($_POST['authToken']) && $_POST['authToken'] == $_SESSION['authToken']){
        $sessionAge=time()-$_SESSION['authTokenTime'];
        if ($sessionAge > 1200){
            if (isset($_SESSION['providersID'])){
                destroySession();
                header('Location:providerLogin.php?error=sessionExpired');
                exit();
            }
            else{
                destroySession();
                header('Location:login.php?error=sessionExpired');
                exit();
            }
        }
    }
    else{
        if (isset($_SESSION['providersID'])){
            destroySession();
            header('Location:providerLogin.php?error=invalidToken');
            exit();
        }
        else{
            destroySession();
            header('Location:login.php?error=invalidToken');
            exit();
        }
        
    }
    $authToken = $_POST['authToken'];
}

//check connection to MySql database
include 'connection.php';


$comments= htmlentities($_POST['orderCom']);
$servId = htmlentities($_POST['servId']);
$userId = $_SESSION['usersID'];
$isAcc = '0';
$isComp = '0';
//echo 'comments:'. $comments . 'servId:'.$servId . 'userId:'. $userId;

if(isset($_POST['checkbox'])){
$confirmation = $_POST['checkbox'];
}
else{
    promptMessage('Please confirm your order.');
    echo "<form action='userOffer.php' id='returnForm' method='post'>";
    echo "<input hidden name='authToken' value='$authToken'>";
    echo"<input type='hidden' name='serviceIDS' value='$servID'>";
    echo "</form>";
    echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";;
}

    if(isset($confirmation)){

        $query= $conn->prepare("INSERT INTO `orders`(`customerFkid`, `isAccepted`, `comments`, `servicesFkid`, `isCompleted`) VALUES (?,?,?,?,?)");
        $query->bind_param('iisii', $userId, $isAcc, $comments, $servId, $isComp); //bind the parameters
        if ($query->execute()){ //execute query
            promptMessage('Order creation successful');
            echo "<form action='userOffer.php' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo"<input type='hidden' name='serviceIDS' value='$servID'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";;
        }else{
            promptMessage('Order creation unsuccessful');
            echo "<form action='userOffer.php' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo"<input type='hidden' name='serviceIDS' value='$servID'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";;
        }
    }
    
        $stmt= $conn->prepare("SELECT ordersId FROM orders WHERE customerFkid = $userId AND servicesFkid = $servId ORDER BY ordersId DESC LIMIT 1;");
        $res = $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($orderId);
        while($stmt->fetch()){ //execute query
            echo "$orderId";
            $_SESSION['orderId'] = $orderId;
            header('Location: userShowOffers.php');
            exit;
        }

        
    
?>