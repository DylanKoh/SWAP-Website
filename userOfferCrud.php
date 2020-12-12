<?php

require_once 'sessionInitialise.php';
//check connection to MySql database
include 'connection.php';

$comments= htmlentities($_POST['orderCom']);
$servId = htmlentities($_POST['servId']);
$userId = $_SESSION['usersID'];
$isAcc = '0';
$isComp = '0';
//echo 'comments:'. $comments . 'servId:'.$servId . 'userId:'. $userId;

if(isset($_POST['checkbox'])){
$confirmation = $_POST['checkbox'];
}
else{
    echo "<script language='javascript'>;alert('Please confirm your order.'); window.location.href = document.referrer;</script>";
}

    if(isset($confirmation)){

        $query= $conn->prepare("INSERT INTO `orders`(`customerFkid`, `isAccepted`, `comments`, `servicesFkid`, `isCompleted`) VALUES (?,?,?,?,?)");
        $query->bind_param('iisii', $userId, $isAcc, $comments, $servId, $isComp); //bind the parameters
        if ($query->execute()){ //execute query
            echo "<br>Successfully added!<br>" ;
        }else{
            echo "<br>Adding unsuccessful";
        }
    }
    
        $stmt= $conn->prepare("SELECT ordersId FROM orders WHERE customerFkid = $userId AND servicesFkid = $servId ORDER BY ordersId DESC LIMIT 1;");
        $res = $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($orderId);
        while($stmt->fetch()){ //execute query
            echo "$orderId";
            $_SESSION['orderId'] = $orderId;
            echo $_SESSION['orderId'];
            header('Location: /userShowOffers.php');
            exit;
        }

        
    
?>