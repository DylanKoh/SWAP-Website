<?php
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
    include_once 'alertMessageFunc.php';
    
    //Sessions
    
    $name= $_POST['serName'];
    $desc = $_POST['serDesc'];
    $provId = $_SESSION['providersID'];
    $price = $_POST['serPrice'];
    
    
    //Regular expression patterns:
    $nameVal ='/^([0-9A-Za-z\s]+[!-]*)+$/';//To allow characters, numbers, spaces, exclamation marks and dashes.
    $descVal = '/^([0-9A-Za-z\s]+[.!_-]*)+$/'; //Allowing Characters, numbers, spaces and some special characters.
    $priceVal ='/^[0-9]+$/';//Allows for integers only
    
    if (preg_match($nameVal, $name) && preg_match($descVal, $desc) && preg_match($priceVal, $price)){
        $query= $conn->prepare("INSERT INTO `services` (`serviceName`, `serviceDesc`, `providersFkid`, `price`) VALUES (?,?,?,?)");
        $query->bind_param('ssid', $name, $desc, $provId, $price); //bind the parameters
        if ($query->execute()){ //execute query
            promptMessage('Adding Successful');
            echo "<form action='storePage.php' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
        }else{
            promptMessage('Adding Unsuccessful');
            echo "<form action='storePage.php' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
        }
    }
    
    else {
        promptMessage('Please only add correct characters!');
        echo "<form action='storePage.php' id='returnForm' method='post'>";
        echo "<input hidden name='authToken' value='$authToken'>";
        echo "</form>";
        echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
    }

?>