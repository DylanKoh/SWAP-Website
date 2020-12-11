<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
require_once 'PHPGangsta/GoogleAuthenticator.php';
$ga=new PHPGangsta_GoogleAuthenticator();

//start session
require_once 'sessionInitialise.php';
$_SESSION['userID'] ='3';

//connect to db
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
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

$creditCard = htmlentities($_POST["creditCard"]);
$expiryDate = htmlentities($_POST["expiryDate"]);
$fourDigits = htmlentities($_POST["fourDigits"]);
$fourDigitsCheck = substr($creditCard, -4);
$pin = $_POST["paymentPin"];
$userFkid = $_SESSION['userID'];
$textToHash = $creditCard . $expiryDate;

$key = $pin;
$salt1 = $ga->createSecret();
$salt2 = $ga->createSecret();

$secret = base64_encode(hash('sha256', $pin));
$password1 = $salt1.$pin;
$hash_1 = hash('sha256', $password1);
$password2 = $hash_1.$salt2;
$hash_2 = hash('sha256', $password2);

if($noAdd) {
    
    
    echo "Credit Card Number: " . $creditCard . "<br>";
    echo "Expiry Date: " . $expiryDate . "<br>";
    echo "Last 4 digits: " . $fourDigits . "<br>";
    echo "Your UserID: " . $userId . "<br>";
    echo "Hash: " . $secret . "<br>";
    //echo "Decrypted Hash: " . $decSecret . "<br>";
    echo "Salt1: " . $hash_1 . "<br>";
    echo "Salt2: " . $hash_2 . "<br>";
    
    
    
    echo "Payment has been completed! Payment information has not been saved.";
    } else if($isAdd) { /*add function*/
    if(empty($creditCard)) { //check for empty credit card field
        ?><script>alert('credit card field blank'); window.location.href='checkout.php'</script> <?php
    } else if(!preg_match('/^[0-9]{15,16}$/', $creditCard)) { //only allow numbers in credit card field
        ?><script>alert('invalid credit card format'); window.location.href='checkout.php'</script> <?php
    } else if(empty($expiryDate)) { //check for empty expiry date field
        ?><script>alert('expiry date field blank'); window.location.href='checkout.php'</script> <?php
    } else if(!preg_match('/^\d{2}\/\d{2}$/', $expiryDate)) { //only allow __/__ format in expiry date field
        ?><script>alert('invalid date format'); window.location.href='checkout.php'</script> <?php    
    } else if(empty($fourDigits)) { //check for empty four digit field
        ?><script>alert('four digits field blank'); window.location.href='checkout.php'</script> <?php
    } else if(!preg_match('/^[0-9]{4}$/', $fourDigits)) { //only allow 4 numbers in four digit field
        ?><script>alert('invalid four digits format'); window.location.href='checkout.php'</script> <?php
    } else if($fourDigits != $fourDigitsCheck) { //check if four digit and credit card match
        ?><script>alert('four digits do not match credit card number'); window.location.href='checkout.php'</script><?php
    } else if(empty($pin)) { //check for empty pin field
        ?><script>alert('pin field blank'); window.location.href='checkout.php'</script> <?php
    } else if(!preg_match('/^[0-9]{6}$/', $pin)) { //only allow 6 numbers in pin field
        ?><script>alert('invalid pin format'); window.location.href='checkout.php'</script> <?php
    } else {
    $stmt=$conn->prepare("INSERT INTO `sales` (`creditCard`, `expiryDate`, `fourDigits`, `usersFkid`, `secret`, `hash_1`, `hash_2`) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("isiisss", $creditCard, $expiryDate, $fourDigits, $userFkid, $secret, $hash_1, $hash_2);
    $res=$stmt->execute();
    if($res) {
        echo "Inserted successfully";
    } else {
        echo "Unable to insert";
    }
   }
   
} else if($isUpdate) { /*Update Fuction*/
    if(empty($creditCard)) { //check for empty credit card field
        ?><script>alert('credit card field blank'); window.location.href='checkout.php'</script> <?php
    } else if(!preg_match('/^[0-9]{15,16}$/', $creditCard)) { //only allow numbers in credit card field
        ?><script>alert('invalid credit card format'); window.location.href='checkout.php'</script> <?php
    } else if(empty($expiryDate)) { //check for empty expiry date field
        ?><script>alert('expiry date field blank'); window.location.href='checkout.php'</script> <?php
    } else if(!preg_match('/^\d{2}\/\d{2}$/', $expiryDate)) { //only allow __/__ format in expiry date field
        ?><script>alert('invalid date format'); window.location.href='checkout.php'</script> <?php    
    } else if(empty($fourDigits)) { //check for empty four digit field
        ?><script>alert('four digits field blank'); window.location.href='checkout.php'</script> <?php
    } else if(!preg_match('/^[0-9]{4}$/', $fourDigits)) { //only allow 4 numbers in four digit field
        ?><script>alert('invalid four digits format'); window.location.href='checkout.php'</script> <?php
    } else if($fourDigits != $fourDigitsCheck) { //check if four digit and credit card match
        ?><script>alert('four digits do not match credit card number'); window.location.href='checkout.php'</script><?php
    } else if(empty($pin)) { //check for empty pin field
        ?><script>alert('pin field blank'); window.location.href='checkout.php'</script> <?php
    } else if(!preg_match('/^[0-9]{6}$/', $pin)) { //only allow 6 numbers in pin field
        ?><script>alert('invalid pin format'); window.location.href='checkout.php'</script> <?php
    } else {
    $stmt=$conn->prepare("UPDATE sales SET creditCard=?, expiryDate=?, fourDigits=?, secret=?, hash_1=?, hash_2=? WHERE UsersFkid=?");
    $stmt->bind_param("isisssi", $creditCard, $expiryDate, $fourDigits, $secret, $hash_1, $hash_2, $userFkid);
    $res=$stmt->execute();
    if($res) {
        echo "Updated successfully!";
    } else {
        echo "Unable to update!";
    }
    }
} else if($isDelete) {
    echo "<form action='checkoutDelete.php' method='post'><br>";
    echo "Are you sure you want to delete your payment information, user " . $userFkid . "? <br><br>";
    echo "<input type='hidden' name='userFkid' value='".$creditCard."'>";
    echo "<input type='submit' value='Delete'>";
    echo "</form>";
}




?>