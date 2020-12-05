<?php
    //Connecting to Mysql Database
    include 'connection.php';
    
    //Sessions
    session_start();

    $rateUpdate = $_POST['ratingUpdate'];
    $comUpdate = $_POST['commentUpdate'];
    $revId = $_POST['reviewId'];
    echo $rateUpdate . $comUpdate . $revId;


    if(isset($_POST["revUpdateBtn"])){
        $query= $conn->prepare("UPDATE reviews SET rating= ?, comments = ? WHERE reviewsId =$revId");
        $query->bind_param('is', $rateUpdate, $comUpdate); //bind the parameters
        if ($query->execute()){ //execute query
            echo "<br>Successfully added!";
        }else{
            echo "<br>Adding unsuccessful";
        }
    }
    
    else if (isset($_POST['revDeleteBtn'])){
        $query = $conn->prepare("DELETE FROM reviews WHERE reviews.reviewId=$revId");
        if ($query->execute()){ //execute query
            echo "Successfully deleted";
            header("Location:http://localhost/Swapcasestudy/storePage.php");
            exit();
        }else{
            echo "Delete unsuccessful";
        }
    }

?>