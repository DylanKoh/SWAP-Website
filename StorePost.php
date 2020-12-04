<?php
    //Connecting to Mysql Database
    include 'connection.php';
    
    //Sessions
    session_start();
    
    $name= $_POST['serName'];
    $desc = $_POST['serDesc'];
    $provId = $_SESSION['provId'];
    $price = $_POST['serPrice'];
    
    $query= $conn->prepare("INSERT INTO `services` (`serviceName`, `serviceDesc`, `providersFkid`, `price`) VALUES (?,?,?,?)");
    $query->bind_param('ssid', $name, $desc, $provId, $price); //bind the parameters
    if ($query->execute()){ //execute query
        echo "<br>Successfully added!";
    }else{
        echo "<br>Adding unsuccessful";
    }


?>