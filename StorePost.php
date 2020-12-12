<?php

header("Content-Security-Policy: default-src 'self'");

    //Connecting to Mysql Database
    include 'connection.php';
    
    //Sessions
    session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE);
    session_start();
    
    $name= $_POST['serName'];
    $desc = $_POST['serDesc'];
    $provId = $_SESSION['provId'];
    $price = $_POST['serPrice'];
    
    //Regular expression patterns:
    $nameVal ='/^([0-9A-Za-z\s]+[!-]*)+$/';//To allow characters, numbers, spaces, exclamation marks and dashes.
    $descVal = '/^([0-9A-Za-z\s]+[.!_-]*)+$/'; //Allowing Characters, numbers, spaces and some special characters.
    $priceVal ='/^[0-9]+$/';//Allows for integers only
    
    if (preg_match($nameVal, $name) && preg_match($descVal, $desc) && preg_match($priceVal, $price)){
        $query= $conn->prepare("INSERT INTO `services` (`serviceName`, `serviceDesc`, `providersFkid`, `price`) VALUES (?,?,?,?)");
        $query->bind_param('ssid', $name, $desc, $provId, $price); //bind the parameters
        if ($query->execute()){ //execute query
            echo "<script language='javascript'>;alert('Posting of service is Successful!'); window.location.href = document.referrer;</script>";
        }else{
            echo "<br>Adding unsuccessful";
        }
    }
    
    else {
        echo "<script language='javascript'>;alert('Please only add correct characters!'); window.location.href = 'storePage.php';</script>";
    }

?>