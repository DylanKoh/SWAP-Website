<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php';
if (!isset($_SESSION['providersID'])){ //Check if token for creating account is not valid
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

$orderId = htmlentities($_POST['orderId']);
$servID = htmlentities($_POST['servIDS']);
$provId = $_SESSION['providersID'];
$updateVar = '1';

    if(isset($_POST["acc-offer"])){
        $query= $conn->prepare("UPDATE orders INNER JOIN services ON services.servicesId = orders.servicesFkid
                                SET orders.isAccepted =? WHERE orders.ordersId =? AND services.providersFkid =?;");
        $query->bind_param('iii', $updateVar, $orderId, $provId); //bind the parameters
        if ($query->execute()){ //execute query
            promptMessage('Update successful');
            echo "<form action='providerOffer.php' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo"<input type='hidden' name='serviceIDS' value='$servID'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
        }else{
            promptMessage('Update unsuccessful');
            echo "<form action='providerOffer.php' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo"<input type='hidden' name='serviceIDS' value='$servID'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
        }
    }
    
    if(isset($_POST["comp-offer"])){
        $query= $conn->prepare("UPDATE orders INNER JOIN services ON services.servicesId = orders.servicesFkid
                                SET orders.isCompleted =? WHERE orders.ordersId =? AND services.providersFkid =?");
        $query->bind_param('iii', $updateVar, $orderId, $provId); //bind the parameters
        if ($query->execute()){ //execute query
            promptMessage('Complete order successful');
            echo "<form action='providerOffer.php' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo"<input type='hidden' name='serviceIDS' value='$servID'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
        }else{
            promptMessage('Complete order unsuccessful');
            echo "<form action='providerOffer.php' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo"<input type='hidden' name='serviceIDS' value='$servID'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";;
        }
    }
?>