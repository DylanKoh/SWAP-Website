<?php
    //Connecting to Mysql Database
    include 'connection.php';

    //Sessions
    session_start();
    
    $servId = $_POST['servId'];
    $name= $_POST['serName'];
    $desc = $_POST['serDesc'];
    $provId = $_SESSION['provId'];
    $price = $_POST['serPrice'];
    $revId = $_POST['reviewId'];

    
    //Update function:
    if(isset($_POST["updatebtn"])){
        $query= $conn->prepare("UPDATE services SET serviceName = ?, serviceDesc = ?, providersFkid = ?, price = ? WHERE servicesId=$revId");
        $query->bind_param('ssii',$name, $desc, $provId, $price); //bind the parameters
        if ($query->execute()){ //execute query
            echo "Successfully edited";
        }else{
            echo "Delete unsuccessful";
        }
    }
   
    //Delete function:
    else if (isset($_POST['deletebtn'])){
        $query = $conn->prepare("DELETE FROM services WHERE services.servicesId=$revId");
        if ($query->execute()){ //execute query
            echo "Successfully deleted";
            header("Location:http://localhost/Swapcasestudy/storePage.php");
            exit();
        }else{
            echo "Delete unsuccessful";
        }
    }
?>