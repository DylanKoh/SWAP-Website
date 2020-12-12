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
    $authToken = $_POST['authToken'];
}
    //Connecting to Mysql Database
    include 'connection.php';
    
    //Sessions

    $rateUpdate = htmlentities($_POST['ratingUpdate']);
    $comUpdate = htmlentities($_POST['commentUpdate']);
    $revId = htmlentities($_POST['reviewId']);
    $servId = htmlentities($_POST['serv-revId']);
    $usId = $_SESSION['usersID'];
    
    $specChar= htmlspecialchars($comUpdate, ENT_QUOTES);
    $specChar1 = htmlentities($specChar, ENT_QUOTES);
    echo $specChar . "</br>";
    echo $specChar1 . "</br>";
   
    $intSplit = strpos($specChar1, "&"); 
    echo $intSplit;
    $comValue = substr($specChar1, 0, $intSplit);
    echo 'String: '.$comValue . "</br>";
    
    
    //Regular expression patterns:
    $rateVal = '/^[1-5]$/'; //Rating only accepts values between 1 and 5
    $comVal = '/^([0-9A-Za-z\s]+[.!_-]*)*+$/';
    
    if (preg_match($rateVal, $rateUpdate) && preg_match($comVal, $comUpdate)){
            if(isset($_POST["revUpdateBtn"])){ 
                $query= $conn->prepare("UPDATE reviews SET rating= ?, comments = ? WHERE reviewsId = ? AND usersFkid = ?");
                $query->bind_param('isii', $rateUpdate, $comUpdate, $revId, $usId); //bind the parameters
                if ($query->execute()){ //execute query
                    promptMessage('Adding Successful');
                    echo "<form action='storeIndiv.php?id=$servId' id='returnForm' method='post'>";
                    echo "<input hidden name='authToken' value='$authToken'>";
                    echo "</form>";
                    echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
                }else{
                    promptMessage('Adding Unsuccessful');
                    echo "<form action='storeIndiv.php?id=$servId' id='returnForm' method='post'>";
                    echo "<input hidden name='authToken' value='$authToken'>";
                    echo "</form>";
                    echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
                }
            }
            
            else if (isset($_POST['revDeleteBtn'])){
                $query = $conn->prepare("DELETE FROM reviews WHERE reviews.reviewsId =$revId AND reviews.usersFkid=$usId");
                if ($query->execute()){ //execute query
                    promptMessage('Review has been successfully deleted!');
                    echo "<form action='storeIndiv.php?id=$servId' id='returnForm' method='post'>";
                    echo "<input hidden name='authToken' value='$authToken'>";
                    echo "</form>";
                    echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
                }else{
                    promptMessage('Review deleting unsuccessful');
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