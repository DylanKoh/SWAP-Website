<?php
header("Content-Security-Policy: default-src 'self'");

include 'connection.php'; //connect to db
require_once 'sessionInitialise.php'; //start session
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

$stmt=$conn->prepare("DELETE FROM sales WHERE usersFkid=?"); //sql query statement to delete the users payment information
$stmt->bind_param("i", $userFkid);
$res=$stmt->execute(); //executes sql query statement
if($res) {
    echo "<form id='checkout' class='checkout' Action='checkout.php' method='post'>";
    echo "<input hidden value='$authToken' name='authToken'>";
    echo "<input hidden value='$checkoutToken' name='checkoutToken'>";
    echo "Payment information has been successfully deleted.<br>";
    echo "<button value='submit'>Back to Payment?</button><br>";
    echo "</form>";
} else {
    echo "<form action='checkout.php?error=deleteFailed' method='post' id='deleteFailed'>";
    echo"<input hidden value='$authToken' name='authToken'>";
    echo"<input hidden value='$checkoutToken' name='checkoutToken'>";
    echo"</form>";
    echo "<script>document.getElementById('deleteFailed').submit();</script>";
    exit();;
}
?>