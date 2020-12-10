<?php
header("Content-Security-Policy: script-src 'https://localhost/SWAPWebsite'; "); //Starts Content Security Policy to protect any remote code execution
    //Connecting to Mysql Database
    include 'connection.php';
    
    //Sessions
    session_start();
    
    $comments= $_POST['revComments'];
    $rating = $_POST['revRating'];
    $orderId = $_SESSION['orderId'];
    $userId = $_SESSION['userId'];
    
    //Regular expression patterns:
    $rateVal = '/^[1-5]$/'; //Rating only accepts values between 1 and 5
    $comVal = '/^([0-9A-Za-z\s]+[.!_-]*)*+$/';
    $invalidChar = "/^['\"<>;]*$/";
    
    if (preg_match($rateVal, $rating) && preg_match($comVal, $comments)){
        $query= $conn->prepare("INSERT INTO `reviews` (`ordersFkid`, `usersFkid`, `rating`, `comments`) VALUES (?,?,?,?)");
        $query->bind_param('iiis', $orderId, $userId, $rating, $comments); //bind the parameters
        if ($query->execute()){ //execute query
            echo "<br>Successfully added!";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }else{
            echo "<br>Adding unsuccessful";
        }
    }
    else {
        echo "<script language='javascript'>;alert('Please only add correct characters!');</script>";
    }
?>