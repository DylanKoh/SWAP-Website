<?php

header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
require_once 'PHPGangsta/GoogleAuthenticator.php';
require_once 'alertMessageFunc.php';
$ga = new PHPGangsta_GoogleAuthenticator();

//start session
require_once 'sessionInitialise.php';
require_once 'validateToken.php';

if (isset($_SESSION['usersID'])) {
    $userFkid = $_SESSION['usersID'];
}

if(!isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){
    destroySession();
    header('Location:login.php?error=notloggedin');
    exit();
}
else{
    if (isset($_POST['authToken']) && $_POST['authToken'] == $_SESSION['authToken']){
        $sessionAge=time()-$_SESSION['authTokenTime'];
        if ($sessionAge > 1200){
            if (isset($_SESSION['providersID'])){
                destroySession();
                header('Location:providerLogin.php?error=sessionExpired');
                exit();
            }
            else{
                destroySession();
                header('Location:login.php?error=sessionExpired');
                exit();
            }
        }
        else{
            $authToken = $_POST['authToken'];
            if (!verifyToken('checkoutToken',300)){
                unsetVariable('checkoutToken');
                unsetVariable('checkoutTokenTime');
                echo "<form action='storePage.php?error=checkoutTimeout' method='post' id='returnForm'>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "</form>";
                echo "<script>document.getElementById('returnForm').submit();</script>";
                exit();
            } 
            $checkoutToken = $_POST['checkoutToken'];
        }
    }
    else{
        if (isset($_SESSION['providersID'])){
            destroySession();
            header('Location:providerLogin.php?error=invalidToken');
            exit();
        }
        else{
            destroySession();
            header('Location:login.php?error=invalidToken');
            exit();
        }
        
    }
}


//connect to db
include 'connection.php';


$hasAction  = isset($_POST["action"]);
$isAdd      = false;
$noAdd      = false;
$isUpdate   = false;
$isDelete   = false;
$isExisting = false;

if ($hasAction) { //check if a radio button was selected
    $action     = $_POST["action"];
    $isAdd      = $action === "yes";
    $noAdd      = $action === "no";
    $isUpdate   = $action === "update";
    $isDelete   = $action === "delete";
    $isExisting = $action == 'existing';
} else {
    echo "No option was selected!";
}

//defining variables
$creditCard      = htmlentities($_POST["creditCard"]); //htmlentities to sanitise user input data
$expiryDate      = htmlentities($_POST["expiryDate"]);
$fourDigits      = htmlentities($_POST["fourDigits"]);
$fourDigitsCheck = substr($creditCard, -4);
$pin             = $_POST["paymentPin"];

$hash_1 = $ga->createSecret(); //salt 1
$hash_2 = $ga->createSecret(); //salt 2

$hashedSecret = hash('sha256', $hash_1 . $pin);
$secret       = base64_encode(hash('sha256', $hashedSecret . $hash_2));


if ($noAdd) { //if the option to not save information to database is selected
    
    
    echo "Credit Card Number: " . $creditCard . "<br>";
    echo "Expiry Date: " . $expiryDate . "<br>";
    echo "Last 4 digits: " . $fourDigits . "<br>";
    echo "Your UserID: " . $userFkid . "<br><br>";
    echo "Payment has been completed! Payment information has not been saved.";
    
} else if ($isAdd) { //if the option to save to database is selected
    $checkId = $conn->query("SELECT * FROM sales WHERE usersFkid='$userFkid'"); //check if that user already has a card saved in the database
    
    if ($checkId->num_rows > 0) { //check for existing card
        echo "<form action='checkout.php?error=existingCard' method='post' id='existingCard'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('existingCard').submit();</script>";
        exit();
    } else if (empty($creditCard)) { //check for empty credit card field
        echo "<form action='checkout.php?error=emptyCard' method='post' id='emptyCard'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('emptyCard').submit();</script>";
        exit();
    } else if (!preg_match('/^[0-9]{15,16}$/', $creditCard)) { //only allow numbers in credit card field
        echo "<form action='checkout.php?error=wrongCard' method='post' id='wrongCard'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('wrongCard').submit();</script>";
        exit();
    } else if (empty($expiryDate)) { //check for empty expiry date field
        echo "<form action='checkout.php?error=emptyDate' method='post' id='emptyDate'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('emptyDate').submit();</script>";
        exit();
    } else if (!preg_match('/^\d{2}\/\d{2}$/', $expiryDate)) { //only allow __/__ format in expiry date field
        echo "<form action='checkout.php?error=wrongDate' method='post' id='wrongDate'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('wrongDate').submit();</script>";
        exit();
    } else if (empty($fourDigits)) { //check for empty four digit field
        echo "<form action='checkout.php?error=blankFour' method='post' id='blankFour'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('blankFour').submit();</script>";
        exit();
    } else if (!preg_match('/^[0-9]{4}$/', $fourDigits)) { //only allow 4 numbers in four digit field
        echo "<form action='checkout.php?error=wrongFour' method='post' id='wrongFour'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('wrongFour').submit();</script>";
        exit();
    } else if ($fourDigits != $fourDigitsCheck) { //check if four digit and credit card match
        echo "<form action='checkout.php?error=matchFour' method='post' id='matchFour'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('matchFour').submit();</script>";
        exit();
    } else if (empty($pin)) { //check for empty pin field
        echo "<form action='checkout.php?error=blankPin' method='post' id='blankPin'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('blankPin').submit();</script>";
        exit();
    } else if (!preg_match('/^[0-9]{6}$/', $pin)) { //only allow 6 numbers in pin field
        echo "<form action='checkout.php?error=wrongPin' method='post' id='wrongPin'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('wrongPin').submit();</script>";
        exit();
    } else {
        $stmt = $conn->prepare("INSERT INTO `sales` (`creditCard`, `expiryDate`, `fourDigits`, `usersFkid`, `secret`, `hash_1`, `hash_2`) VALUES (?,?,?,?,?,?,?)"); //sql query statement to add the users payment information 
        $stmt->bind_param("isiisss", $creditCard, $expiryDate, $fourDigits, $userFkid, $secret, $hash_1, $hash_2); //inserts these variables into the db
        $res = $stmt->execute(); //executes sql query statement
        if ($res) {
            echo "<form id='checkout' class='checkout' Action='storePage.php' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo "<input hidden value='$checkoutToken' name='checkoutToken'>";
            echo "Payment information has been saved.<br>";
            echo "User ID: " . $userFkid ."<br>";
            echo "Card Number: **** **** **** " . $fourDigits ."<br>";
            echo "Expiry Date: " . $expiryDate ."<br>";
            echo "<button value='submit'>Confirm Payment</button><br>";
            echo "</form>";
        } else {
            echo "<form action='checkout.php?error=paymentFailed' method='post' id='paymentFailed'>";
            echo"<input hidden value='$authToken' name='authToken'>";
            echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
            echo"</form>";
            echo "<script>document.getElementById('paymentFailed').submit();</script>";
            exit();
        }
      }
    } else if ($isUpdate) {
    /*Update Fuction*/
    if (empty($creditCard)) { //check for empty credit card field
        echo "<form action='checkout.php?error=emptyCard' method='post' id='emptyCard'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('emptyCard').submit();</script>";
        exit();
    } else if (!preg_match('/^[0-9]{15,16}$/', $creditCard)) { //only allow numbers in credit card field
        echo "<form action='checkout.php?error=wrongCard' method='post' id='wrongCard'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('wrongCard').submit();</script>";
        exit();
    } else if (empty($expiryDate)) { //check for empty expiry date field
        echo "<form action='checkout.php?error=emptyDate' method='post' id='emptyDate'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('emptyDate').submit();</script>";
        exit();
    } else if (!preg_match('/^\d{2}\/\d{2}$/', $expiryDate)) { //only allow __/__ format in expiry date field
        echo "<form action='checkout.php?error=wrongDate' method='post' id='wrongDate'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('wrongDate').submit();</script>";
        exit();
    } else if (empty($fourDigits)) { //check for empty four digit field
        echo "<form action='checkout.php?error=blankFour' method='post' id='blankFour'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('blankFour').submit();</script>";
        exit();
    } else if (!preg_match('/^[0-9]{4}$/', $fourDigits)) { //only allow 4 numbers in four digit field
        echo "<form action='checkout.php?error=wrongFour' method='post' id='wrongFour'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('wrongFour').submit();</script>";
        exit();
    } else if ($fourDigits != $fourDigitsCheck) { //check if four digit and credit card match
        echo "<form action='checkout.php?error=matchFour' method='post' id='matchFour'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('matchFour').submit();</script>";
        exit();
    } else if (empty($pin)) { //check for empty pin field
        echo "<form action='checkout.php?error=blankPin' method='post' id='blankPin'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('blankPin').submit();</script>";
        exit();
    } else if (!preg_match('/^[0-9]{6}$/', $pin)) { //only allow 6 numbers in pin field
        echo "<form action='checkout.php?error=wrongPin' method='post' id='wrongPin'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('wrongPin').submit();</script>";
        exit();
    } else {
        
        $stmt = $conn->prepare("UPDATE sales SET creditCard=?, expiryDate=?, fourDigits=?, secret=?, hash_1=?, hash_2=? WHERE UsersFkid=?"); //sql query statement to update the user's payment information
        $stmt->bind_param("isisssi", $creditCard, $expiryDate, $fourDigits, $secret, $hash_1, $hash_2, $userFkid); //updates these variables in the db
        
        
        $stmt1 = $conn->prepare("SELECT * FROM sales WHERE usersFkid=?");
        $stmt1->bind_param("i", $userFkid);
        $stmt1->execute(); //executes sql query statement
        $stmt1->store_result();
        $stmt1->bind_result($salesIda, $creditCarda, $expiryDatea, $fourDigitsa, $userFkida, $secreta, $hash_1a, $hash_2a);
        while ($stmt1->fetch()) { //run pin check
            $pin1       = $hash_1a . $pin; //append stored salt to user inputted pin
            $salt_1     = hash('sha256', $pin1); //hash $pin1
            $pin2       = $salt_1 . $hash_2a; //append stored salt to hash of $pin1
            $salt_2     = hash('sha256', $pin2); //hash $pin2
            $confirmPin = base64_encode($salt_2); //encode hash of $pin2 using base64
            if ($confirmPin != $secreta) { //check if stored pin = user inputted pin
                echo "<form action='checkout.php?error=matchPin' method='post' id='matchPin'>";
                echo"<input hidden value='$authToken' name='authToken'>";
                echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
                echo"</form>";
                echo "<script>document.getElementById('matchPin').submit();</script>";
                exit();
            } else if ($stmt->execute()) { //executes sql query statement
                echo "<form id='checkout' class='checkout' Action='checkout.php' method='post'>";
                echo "<input hidden value='$authToken' name='authToken'>";
                echo "<input hidden value='$checkoutToken' name='checkoutToken'>";
                echo "Payment information has been successfully updated.<br>";
                echo "<button value='submit'>Back to Payment?</button><br>";
                echo "</form>";
            } else { //if update fails
                echo "<form action='checkout.php?error=updateFailed' method='post' id='updateFailed'>";
                echo"<input hidden value='$authToken' name='authToken'>";
                echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
                echo"</form>";
                echo "<script>document.getElementById('updateFailed').submit();</script>";
                exit();
            }
        }
    }
} else if ($isDelete) {
    $stmt = $conn->prepare("SELECT * FROM sales WHERE usersFkid=?");
    $stmt->bind_param("i", $userFkid);
    $stmt->execute(); //executes sql query statement
    $stmt->store_result();
    $stmt->bind_result($salesIda, $creditCarda, $expiryDatea, $fourDigitsa, $userFkida, $secreta, $hash_1a, $hash_2a);
    $stmt->fetch(); //information to display
    $pin1       = $hash_1a . $pin; //append stored salt to user inputted pin
    $salt_1     = hash('sha256', $pin1); //hash $pin1
    $pin2       = $salt_1 . $hash_2a; //append stored salt to hash of $pin1
    $salt_2     = hash('sha256', $pin2); //hash $pin2
    $confirmPin = base64_encode($salt_2); //encode hash of $pin2 using base64
    if ($confirmPin != $secreta) { //check if stored pin = user inputted pin
        echo "<form action='checkout.php?error=matchPin' method='post' id='matchPin'>";
        echo"<input hidden value='$authToken' name='authToken'>";
        echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
        echo"</form>";
        echo "<script>document.getElementById('matchPin').submit();</script>";
        exit();
        }
      else if ($stmt->execute()) { //executes sql query statement
    echo "<form action='checkoutDelete.php' method='post'><br>"; //form to ask the user to confirm the delete
    echo"<input hidden value='$authToken' name='authToken'>";
    echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
    echo "Are you sure you want to delete your payment information, user " . $userFkid . "? <br><br>";
    echo "<input type='hidden' name='userFkid' value='" . $creditCard . "'>";
    echo "<input type='submit' value='Delete'>";
    echo "</form>";
      } else {
          echo "<form action='checkout.php?error=deleteFailed' method='post' id='deleteFailed'>";
          echo"<input hidden value='$authToken' name='authToken'>";
          echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
          echo"</form>";
          echo "<script>document.getElementById('deleteFailed').submit();</script>";
          exit();
      }
}

else if ($isExisting) { //if the existing card option is selected
    $stmt = $conn->prepare("SELECT * FROM sales WHERE usersFkid=?");
    $stmt->bind_param("i", $userFkid);
    $stmt->execute(); //executes sql query statement
    $stmt->store_result();
    $stmt->bind_result($salesIda, $creditCarda, $expiryDatea, $fourDigitsa, $userFkida, $secreta, $hash_1a, $hash_2a);
    if($stmt->fetch()) { //information to display
        $pin1       = $hash_1a . $pin; //append stored salt to user inputted pin
        $salt_1     = hash('sha256', $pin1); //hash $pin1
        $pin2       = $salt_1 . $hash_2a; //append stored salt to hash of $pin1
        $salt_2     = hash('sha256', $pin2); //hash $pin2
        $confirmPin = base64_encode($salt_2); //encode hash of $pin2 using base64
        if ($confirmPin != $secreta) { //check if stored pin = user inputted pin
            echo "<form action='checkout.php?error=matchPin' method='post' id='matchPin'>";
            echo"<input hidden value='$authToken' name='authToken'>";
            echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
            echo"</form>";
            echo "<script>document.getElementById('matchPin').submit();</script>";
            exit();
        }
        else if ($confirmPin == $secreta) { //executes sql query statement
            
            echo "<form id='checkout' class='checkout' Action='storePage.php' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
            echo "User ID: " . $userFkida ."<br>";
            echo "Card Number: **** **** **** " . $fourDigitsa ."<br>";
            echo "Expiry Date: " . $expiryDatea ."<br>";
            echo "<button value='submit'>Confirm Payment</button>";
            echo "</form>";
        }
       
 } else {
     echo "<form action='checkout.php?error=existingFailed' method='post' id='existingFailed'>";
     echo"<input hidden value='$authToken' name='authToken'>";
     echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
     echo"</form>";
     echo "<script>document.getElementById('existingFailed').submit();</script>";
     exit();
        }
}
?>