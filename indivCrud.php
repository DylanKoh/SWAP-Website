<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php';
if(!isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){
    
}
else{
    
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
            if ($query->execute()){ //execute query
                echo "<script language='javascript'>;alert('Successfully editted!'); window.location.href = document.referrer;</script>";
                //header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }
            else{
                echo "Delete unsuccessful";
            }
        }
       
        //Delete function:
        else if (isset($_POST['deletebtn'])){
            $query = $conn->prepare("DELETE FROM services WHERE services.servicesId=$servId AND providersFkid=$provId");
            if ($query->execute()){ //execute query
                echo "<script language='javascript'>;alert('Successfully deleted!'); window.location.href = 'storePage.php';</script>";
                
            }
            else{
                echo "Delete unsuccessful";
            }
        }
    }
    
    else {
        echo "<script language='javascript'>;alert('Please only add correct characters!');</script>";
    }
?>