<?php
    //Connecting to Mysql Database
    include 'connection.php';
    
    //Sessions
    session_start();
    
    $comments= $_POST['revComments'];
    $rating = $_POST['revRating'];
    $orderId = $_SESSION['orderId'];
    $userId = $_SESSION['userId'];
    
        $query= $conn->prepare("INSERT INTO `reviews` (`ordersFkid`, `usersFkid`, `rating`, `comments`) VALUES (?,?,?,?)");
        $query->bind_param('iiis', $orderId, $userId, $rating, $comments); //bind the parameters
        if ($query->execute()){ //execute query
            echo "<br>Successfully added!";
        }else{
            echo "<br>Adding unsuccessful";
        }
?>