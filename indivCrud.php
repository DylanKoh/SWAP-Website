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
                header('Location:providerLogin.php?error=errToken');
                exit();
            }
            else{
                destroySession();
                header('Location:login.php?error=errToken');
                exit();
            }
        }
    }
    else{
        if (isset($_SESSION['providersID'])){
            destroySession();
            header('Location:providerLogin.php?error=errToken');
            exit();
        }
        else{
            destroySession();
            header('Location:login.php?error=errToken');
            exit();
        }
        
    }
    $authToken = $_POST['authToken'];
}
    //Connecting to Mysql Database
    include 'connection.php';

    //Sessions
    
    $servId = $_POST['servId'];
    $nameUpdate= $_POST['serName'];
    $descUpdate = $_POST['serDesc'];
    $provId = $_SESSION['providersID'];
    $priceUpdate = $_POST['serPrice'];

    
    //Regular expression Patterns:
    $nameVal ='/^([0-9A-Za-z\s]+[!-]*)+$/';//To allow characters, numbers, spaces, exclamation marks and dashes.
    $descVal = '/^([0-9A-Za-z\s]+[,.!_-]*)*+$/'; //Allowing Characters, numbers, spaces and some special characters.
    $priceVal ='/^[0-9]+$/';//Allows for integers only
    
    
    //Update function:
    if (preg_match($nameVal, $nameUpdate) && preg_match($descVal, $descUpdate) && preg_match($priceVal, $priceUpdate)){
        if(isset($_POST["updatebtn"])){
            $query= $conn->prepare("UPDATE services SET serviceName = ?, serviceDesc = ?, price = ? WHERE servicesId=? AND providersFkid = ?");
            $query->bind_param('ssiii',$nameUpdate, $descUpdate, $priceUpdate, $servId, $provId); //bind the parameters
            if ($query->execute()){
                promptMessage('Service has been successfully editted!');
                echo "<form action='storeIndiv.php?id=$servId' id='returnForm' method='post'>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "</form>";
                echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
            }
            else{
                promptMessage('Service update is unsuccessful!');
                echo "<form action='storeIndiv.php?id=$servId' id='returnForm' method='post'>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "</form>";
                echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
            }
        }
       
        //Delete function:
        else if (isset($_POST['deletebtn'])){
            $query = $conn->prepare("DELETE FROM services WHERE services.servicesId=$servId AND providersFkid=$provId");
            if ($query->execute()){ //execute query
                promptMessage('Service has been successfully deleted!');
                echo "<form action='storeIndiv.php?id=$servId' id='returnForm' method='post'>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "</form>";
                echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
            }
            else{
                promptMessage('Service has been unsuccessfully deleted!');
                echo "<form action='storeIndiv.php?id=$servId' id='returnForm' method='post'>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "</form>";
                echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
            }
        }
    }
    
    else {
        promptMessage('Please only add correct characters!');
        echo "<form action='storeIndiv.php?id=$servId' id='returnForm' method='post'>";
        echo "<input hidden name='authToken' value='$authToken'>";
        echo "</form>";
        echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
    }
?>