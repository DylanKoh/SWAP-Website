<?php
header("Content-Security-Policy: script-src 'https://localhost/SWAPWebsite'; "); //Starts Content Security Policy to protect any remote code execution
    $customerFkId= $_SESSION['userId'];
    $servicesFkid = $_SESSION['serviceId'];
    $comments = $_POST['orderComments'];
    $status2 = $_POST['completedStatus'];

    $query= $conn->prepare("INSERT INTO `orders` (`customerFkid`,`comments`,'servicesFkid','isCompleted') VALUES (?,?,?,?)");
    $query->bind_param('isis', $customerFkid, $comments, $servicesFkid, $status2); //bind the parameters
    if ($query->execute()){ //execute query
        echo "<br>Successfully added!";
    }else{
        echo "<br>Adding unsuccessful";
    }
?>