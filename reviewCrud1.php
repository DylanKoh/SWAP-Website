<?php
    //Connecting to Mysql Database
    include 'connection.php';
    
    //Sessions
    session_start();

    $rateUpdate = $_POST['ratingUpdate'];
    $comUpdate = $_POST['commentUpdate'];
    $revId = $_POST['reviewId'];
    $usId = $_SESSION['userId'];
    
    $specChar= htmlspecialchars($comUpdate, ENT_QUOTES);
    $specChar1 = htmlentities($specChar, ENT_QUOTES);
    echo $specChar . "</br>";
    echo $specChar1 . "</br>";
   
    $intSplit = strpos($specChar1, "&quot;");
    echo $intSplit;
    $comValue = substr($specChar1, 0, $intSplit);
    echo 'String: '.$comValue . "</br>";
    
    
    //Regular expression patterns:
    $rateVal = '/^[1-5]$/'; //Rating only accepts values between 1 and 5
    $comVal = '/^([0-9A-Za-z\s]+[.!_-]*)*+$/';
    $invalidChar = "/^['\"<>;]*$/";
    
    if (preg_match($rateVal, $rateUpdate) && preg_match($comVal, $comUpdate)){
            if(isset($_POST["revUpdateBtn"])){ 
                $query= $conn->prepare("UPDATE reviews SET rating= ?, comments = ? WHERE reviewsId = ? AND usersFkid = ?");
                $query->bind_param('isii', $rateUpdate, $comUpdate, $revId, $usId); //bind the parameters
                if ($query->execute()){ //execute query
                    echo "<br>Successfully added!";
                    //header('Location: ' . $_SERVER['HTTP_REFERER']);
                    echo "<script language='javascript'>;alert('Adding successful');</script>";
                    exit();
                }else{
                    echo "<br>Adding unsuccessful";
                    //header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit();
                    echo "<script language='javascript'>;alert('Adding unsuccessful');</script>";
                }
            }
            
            else if (isset($_POST['revDeleteBtn'])){
                $query = $conn->prepare("DELETE FROM reviews WHERE reviews.reviewsId =$revId AND reviews.usersFkid=$usId");
                if ($query->execute()){ //execute query
                    echo "Successfully deleted";
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit();
                }else{
                    echo "Delete unsuccessful";
                }
            }
    }
    else if(preg_match($invalidChar, $comUpdate)) {
        echo "<script language='javascript'>;alert('Please only add correct characters!');</script>";
    }
    
    //else {
    //    echo "<script language='javascript'>;alert('Please only add correct characters!');</script>";
    //}
    
    
?>