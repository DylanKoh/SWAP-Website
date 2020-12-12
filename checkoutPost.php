<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
require_once 'PHPGangsta/GoogleAuthenticator.php';
$ga=new PHPGangsta_GoogleAuthenticator();

//start session
require_once 'sessionInitialise.php';

if(isset($_SESSION['usersID'])){
    $userFkid= $_SESSION['usersID'];
}

//connect to db
include 'connection.php';

$hasAction = isset($_POST["action"]);
$isAdd=false;
$noAdd=false;
$isUpdate=false;
$isDelete=false;
$isExisting=false;

if($hasAction) { //check if a radio button was selected
    $action = $_POST["action"];
    $isAdd = $action === "yes";
    $noAdd = $action === "no";
    $isUpdate = $action === "update";
    $isDelete = $action === "delete";
    $isExisting = $action == 'existing';
} else {
    echo "No option was selected!";
}

$creditCard = htmlentities($_POST["creditCard"]); //defining variables
$expiryDate = htmlentities($_POST["expiryDate"]);
$fourDigits = htmlentities($_POST["fourDigits"]);
$fourDigitsCheck = substr($creditCard, -4);
$pin = $_POST["paymentPin"];

$hash_1 = $ga->createSecret(); //salt 1
$hash_2 = $ga->createSecret(); //salt 2

$hashedSecret = hash('sha256', $hash_1 . $pin);
$secret = base64_encode(hash('sha256', $hashedSecret . $hash_2));


if($noAdd) { //if the option to not save information to database is selected
    
    
    echo "Credit Card Number: " . $creditCard . "<br>";
    echo "Expiry Date: " . $expiryDate . "<br>";
    echo "Last 4 digits: " . $fourDigits . "<br>";
    echo "Your UserID: " . $userFkId . "<br>";
    echo "Hash: " . $secret . "<br>";
    echo "Salt1: " . $hash_1 . "<br>";
    echo "Salt2: " . $hash_2 . "<br>";
    
    echo "Payment has been completed! Payment information has not been saved.";
    } else if($isAdd) { //if the option to save to database is selected
        $checkId = $conn->query("SELECT * FROM sales WHERE usersFkid='$userFkid'"); //check if that user already has a card saved in the database
        
        if($checkId->num_rows > 0) { //check for existing card
            ?><script>alert('existing card already tied to this account'); window.location.href='checkout.php'</script> <?php
        } else if(empty($creditCard)) { //check for empty credit card field
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
        $stmt->bind_param("isiisss", $creditCard, $expiryDate, $fourDigits, $userFkid, $secret, $hash_1, $hash_2); //inserts these variables into the db
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
        //Prepare a query statement to update the user's credit card credentials
    $stmt=$conn->prepare("UPDATE sales SET creditCard=?, expiryDate=?, fourDigits=?, secret=?, hash_1=?, hash_2=? WHERE UsersFkid=?");
    $stmt->bind_param("isisssi", $creditCard, $expiryDate, $fourDigits, $secret, $hash_1, $hash_2, $userFkid); //updates these variables in the db
    
    
    $stmt1=$conn->prepare("SELECT * FROM sales WHERE usersFkid=?");
    $stmt1->bind_param("i", $userFkid);
    $stmt1->execute();
    $stmt1->store_result();
    $stmt1->bind_result($salesIda, $creditCarda, $expiryDatea, $fourDigitsa, $userFkida, $secreta, $hash_1a, $hash_2a);
    while($stmt1->fetch()) {
     $pin1 = $hash_1a . $pin;
     $salt_1 = hash('sha256', $pin1);
     $pin2 = $salt_1 . $hash_2a;
     $salt_2 = hash('sha256', $pin2);
     $confirmPin = base64_encode($salt_2);
     if($confirmPin!=$secreta) {
         ?><script>alert('pin does not match'); window.location.href='checkout.php'</script> <?php
     } 
     else if($stmt->execute()) {
         echo "Updated successfully!";
     } 
     else {
         echo "Unable to update!";
     }
    }
    }
} else if($isDelete) {
    echo "<form action='checkoutDelete.php' method='post'><br>"; //form to ask the user to confirm the delete
    echo "Are you sure you want to delete your payment information, user " . $userFkid . "? <br><br>";
    echo "<input type='hidden' name='userFkid' value='".$creditCard."'>";
    echo "<input type='submit' value='Delete'>";
    echo "</form>";
} else if($isExisting) { //if the existing card option is selected
    $stmt=$conn->prepare("SELECT usersFkid,fourDigits FROM sales WHERE usersFkid=?");
    $stmt->bind_param("i", $userFkid);
    $res=$stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($rUserFkid, $rfourDigits);
    while($stmt->fetch()) { //information to display
    echo "User ID: " . $rUserFkid . "<br>";
    echo "Card Number: **** " . $rfourDigits;
    }
}
?>