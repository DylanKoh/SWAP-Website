<?php

//start session
session_start();

//connect to db
include 'connection.php';

$hasAction = isset($_POST["action"]);
$isAdd=false;
$noAdd=false;
$isUpdate=false;
$isDelete=false;

if($hasAction) {
    $action = $_POST["action"];
    $isAdd = $action === "yes";
    $noAdd = $action === "no";
    $isUpdate = $action === "update";
    $isDelete = $action === "delete";
} else {
    echo "No option was selected!";
}

$query=$conn->prepare("SELECT * FROM users WHERE usersId=1");
$query->execute();
$query->store_result();
$query->bind_result($userID, $correctPassword, $salt_1, $salt_2, $googleSecret, $passwordDate);

$creditCard = $_POST["creditCard"];
$expiryDate = $_POST["expiryDate"];
$fourDigits = $_POST["fourDigits"];
//$userFkid = $_SESSION['userID'];
$secretHash = $creditCard . $expiryDate;
$secret = hash('sha256', $secretHash);
$hash_1 = password_hash($secret, PASSWORD_BCRYPT);
$hash_2 = password_hash($secret, PASSWORD_BCRYPT);

if($noAdd) {
    
    
    echo "Credit Card Number: " . $creditCard . "<br>";
    echo "Expiry Date: " . $expiryDate . "<br>";
    echo "Last 4 digits: " . $fourDigits . "<br>";
    echo "userFkid: " . $userFkid . "<br>";
    
    
    echo "Payment has been completed! Payment information has not been saved.";
}
else if($isAdd) {
    $stmt=$conn->prepare("INSERT INTO sales (`creditCard`, `expiryDate`, `fourDigits`, `usersFkid`, `secret`, `hash_1`, `hash_2`) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("isiisss", $creditCard, $expiryDate, $fourDigits, $userFkid, $secret, $hash_1, $hash_2);
    $res=$stmt->execute();
    if($res) {
        echo "Inserted successfully";
    } else {
        echo "Unable to insert";
        echo $conn->error();
    }
}




?>