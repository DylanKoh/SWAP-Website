<?php
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
                header('Location:providerLogin.php?error=errToken');
                exit();
            }
            else{
                destroySession();
                header('Location:login.php?error=errToken');
                exit();
            }
        }
        else{
            if (isset($_POST['reviewToken']) && $_POST['reviewToken'] == $_SESSION['reviewToken']){
                $reviewTokenAge=time()-$_SESSION['reviewTokenTime'];
                if ($reviewTokenAge > 300){
                    echo "<form action='storePage.php?err=timeout' id='returnForm' method='post'>";
                    echo "<input hidden name='authToken' value='$authToken'>";
                    echo "</form>";
                    echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
                }
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
    $comments= htmlentities($_POST['revComments']);
    $rating = htmlentities($_POST['revRating']);
    $servId = htmlentities($_POST['serviceIDS']);
    $orderId = $_SESSION['orderId'];
    $userId = $_SESSION['usersID'];
    
    //Regular expression patterns:
    $rateVal = '/^[1-5]$/'; //Rating only accepts values between 1 and 5
    $comVal = '/^([0-9A-Za-z\s]+[.!_-]*)*+$/';
    
    if (preg_match($rateVal, $rating) && preg_match($comVal, $comments)){
        $query= $conn->prepare("INSERT INTO `reviews` (`ordersFkid`, `usersFkid`, `rating`, `comments`) VALUES (?,?,?,?)");
        $query->bind_param('iiis', $orderId, $userId, $rating, $comments); //bind the parameters
        if ($query->execute()){ //execute query
            promptMessage('Adding Successful');
            echo "<form action='storeIndiv.php?id=$servId' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
        }else{
            promptMessage('Adding unsuccessful');
            echo "<form action='storeIndiv.php?id=$servId' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
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