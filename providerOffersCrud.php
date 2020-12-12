<?php


require_once 'sessionInitialise.php';
//check connection to MySql database
include 'connection.php';

$orderId = htmlentities($_POST['orderId']);
echo $orderId;
$provId = $_SESSION['providersID'];
$provId = '1';
$updateVar = '1';

    if(isset($_POST["acc-offer"])){
        $query= $conn->prepare("UPDATE orders INNER JOIN services ON services.servicesId = orders.servicesFkid
                                SET orders.isAccepted =? WHERE orders.ordersId =? AND services.providersFkid =?;");
        $query->bind_param('iii', $updateVar, $orderId, $provId); //bind the parameters
        if ($query->execute()){ //execute query
            echo "<script language='javascript'>;alert('Update successful'); window.location.href = document.referrer;</script>";
            exit();
        }else{
            echo "<script language='javascript'>;alert('Update unsuccessful'); window.location.href = document.referrer;</script>";
            exit();
        }
    }
    
    if(isset($_POST["comp-offer"])){
        $query= $conn->prepare("UPDATE orders INNER JOIN services ON services.servicesId = orders.servicesFkid
                                SET orders.isCompleted =? WHERE orders.ordersId =? AND services.providersFkid =?");
        $query->bind_param('iii', $updateVar, $orderId, $provId); //bind the parameters
        if ($query->execute()){ //execute query
            echo "<script language='javascript'>;alert('Update successful'); window.location.href = document.referrer;</script>";
            exit();
        }else{
            echo "<script language='javascript'>;alert('Update unsuccessful'); window.location.href = document.referrer;</script>";
            exit();
        }
    }
?>