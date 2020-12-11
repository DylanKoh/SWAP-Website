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

    $rateUpdate = htmlentities($_POST['ratingUpdate']);
    $comUpdate = htmlentities($_POST['commentUpdate']);
    $revId = htmlentities($_POST['reviewId']);
    $usId = $_SESSION['userId'];
    echo $_SESSION['usersID'];
    
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
                    echo "<script language='javascript'>;alert('Update successful'); window.location.href = document.referrer;</script>";
                    exit();
                }else{
                    echo "<script language='javascript'>;alert('Update unsuccessful'); window.location.href = document.referrer;</script>";
                    exit();
                }
            }
            
            else if (isset($_POST['revDeleteBtn'])){
                $query = $conn->prepare("DELETE FROM reviews WHERE reviews.reviewsId =$revId AND reviews.usersFkid=$usId");
                if ($query->execute()){ //execute query
                    echo "<script language='javascript'>;alert('Review has been successfully deleted!'); window.location.href = document.referrer;</script>";
                    exit();
                }else{
                    echo "<script language='javascript'>;alert('Deleting unsuccessful'); window.location.href = document.referrer;</script>";
                    exit();
                }
            }
    }
    else {
        echo "<script language='javascript'>;alert('Please only add correct characters!'); window.location.href = document.referrer;</script>";
    }
    
    
?>