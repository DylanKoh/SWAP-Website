<?php
    //Connecting to Mysql Database
    include 'connect.php';
    
    $name= $_POST['serName'];
    $desc = $_POST['serDesc'];
    $provId = '1';
    //$_POST['provId'];
    $price = $_POST['serPrice'];
    
    $query= $con->prepare("INSERT INTO `services` (`serviceName`, `serviceDesc`, `providersFkid`, `price`) VALUES (?,?,?,?)");
    $query->bind_param('ssid', $name, $desc, $provId, $price); //bind the parameters
    if ($query->execute()){ //execute query
        echo "<br>Successfully added!";
    }else{
        echo "<br>Adding unsuccessful";
    }


?>