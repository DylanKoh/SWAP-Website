<?php

//start session
session_start();
$_SESSION['userID'] ='1';
$_SESSION['isUser'] ='yes';
$isUser = $_SESSION['isUser'];

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

//testing
// $query=$conn->prepare("SELECT * FROM users WHERE usersId=1");
// $query->execute();
// $query->store_result();
// $query->bind_result($userID, $correctPassword, $salt_1, $salt_2, $googleSecret, $passwordDate);

$creditCard = $_POST["creditCard"];
$expiryDate = $_POST["expiryDate"];
$fourDigits = $_POST["fourDigits"];
//$userFkid = $_SESSION['userID'];
$textToHash = $creditCard . $expiryDate;

$key = 'qkwjdiw239&&jdafweihbrhnan&^%$ggdnawhd4njshjwuuO';

function encryptText($data, $key) {
    $encryption_key = base64_decode($key);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

// function decryptText($data, $key) {
//     $encryption_key = base64_decode($key);
//     list(encrypted_data, $iv) = array_pad(explode('::', base64_decode($data), 2), 2, null);
//     return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
// }

$secret = encryptText($textToHash, $key);
// $decSecret = decryptText($secret, $key);

$hash_1 = password_hash($secret, PASSWORD_BCRYPT);
$hash_2 = password_hash($secret, PASSWORD_BCRYPT);

if($noAdd) {
    
    
    echo "Credit Card Number: " . $creditCard . "<br>";
    echo "Expiry Date: " . $expiryDate . "<br>";
    echo "Last 4 digits: " . $fourDigits . "<br>";
    echo "Your UserID: " . $_SESSION['userID'] . "<br>";
    echo "Hash: " . $secret . "<br>";
    //echo "Decrypted Hash: " . $decSecret . "<br>";
    echo "Salt1: " . $hash_1 . "<br>";
    echo "Salt2: " . $hash_2 . "<br>";
    
    
    
    echo "Payment has been completed! Payment information has not been saved.";
}
else if($isAdd) {
    $stmt=$conn->prepare("INSERT INTO sales (`creditCard`, `expiryDate`, `fourDigits`, `usersFkid`, `secret`, `hash_1`, `hash_2`) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("isiisss", $creditCard, $expiryDate, $fourDigits, $_SESSION['userID'], $secret, $hash_1, $hash_2);
    $res=$stmt->execute();
    if($res) {
        echo "Inserted successfully";
    } else {
        echo "Unable to insert";
    }
}




?>