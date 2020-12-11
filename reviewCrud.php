<?php
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
}
    //Connecting to Mysql Database
    include 'connection.php';
    
    //Sessions
    $comments= htmlentities($_POST['revComments']);
    $rating = htmlentities($_POST['revRating']);
    $orderId = $_SESSION['orderId'];
    $userId = $_SESSION['usersID'];
    
    //Regular expression patterns:
    $rateVal = '/^[1-5]$/'; //Rating only accepts values between 1 and 5
    $comVal = '/^([0-9A-Za-z\s]+[.!_-]*)*+$/';
    
    if (preg_match($rateVal, $rating) && preg_match($comVal, $comments)){
        $query= $conn->prepare("INSERT INTO `reviews` (`ordersFkid`, `usersFkid`, `rating`, `comments`) VALUES (?,?,?,?)");
        $query->bind_param('iiis', $orderId, $userId, $rating, $comments); //bind the parameters
        if ($query->execute()){ //execute query
            echo "<br>Successfully added!";
            //header('Location: ' . $_SERVER['HTTP_REFERER']);
        }else{
            echo "<br>Adding unsuccessful";
        }
    }
    else {
        echo "<script language='javascript'>;alert('Please only add correct characters!'); window.location.href = document.referrer;</script>";
    }
?>