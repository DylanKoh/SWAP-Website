<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php';
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
$provId = $_SESSION['providersID'];
$provId = '1';
$updateVar = '1';

    if(isset($_POST["acc-offer"])){
        $query= $conn->prepare("UPDATE orders INNER JOIN services ON services.servicesId = orders.servicesFkid
                                SET orders.isAccepted =? WHERE orders.ordersId =? AND services.providersFkid =?;");
        $query->bind_param('iii', $updateVar, $orderId, $provId); //bind the parameters
        if ($query->execute()){ //execute query
            echo "<script language='javascript'>;alert('Update successful'); window.location.href = document.referrer;</script>";
            exit();
        }else{
            echo "<script language='javascript'>;alert('Update unsuccessful'); window.location.href = document.referrer;</script>";
            exit();
        }
    }
    
    if(isset($_POST["comp-offer"])){
        $query= $conn->prepare("UPDATE orders INNER JOIN services ON services.servicesId = orders.servicesFkid
                                SET orders.isCompleted =? WHERE orders.ordersId =? AND services.providersFkid =?");
        $query->bind_param('iii', $updateVar, $orderId, $provId); //bind the parameters
        if ($query->execute()){ //execute query
            echo "<script language='javascript'>;alert('Update successful'); window.location.href = document.referrer;</script>";
            exit();
        }else{
            echo "<script language='javascript'>;alert('Update unsuccessful'); window.location.href = document.referrer;</script>";
            exit();
        }
    }
?>