<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php'; //Initialise Session
require 'connection.php';
require_once 'validateToken.php';
if(!isset($_SESSION['usersID'])){ //If user does not have any ID in their session
    header('HTTP/1.0 403 Forbidden');
    exit();
}
else{ //If an ID of sorts is assigned in the session variables
    if (!verifyToken('authToken', 1200)){ //If token is not valid
        destroySession();
        if (isset($_SESSION['providersID'])){ //If initial user is a Provider
            header('Location:providerLogin.php?error=errToken');
            exit();
        }
        else{ //If initial user is a Customer
            header('Location:login.php?error=errToken');
            exit();
        }
    }
    else{
        $authToken=$_POST['authToken'];
        $deleteAccountToken=hash('sha256', uniqid(rand(), TRUE));
        $_SESSION['deleteAccountToken']=$deleteAccountToken;
        $_SESSION['deleteAccountTokenTime']=time();
        echo "<form action='deleteUserAccountDo.php' method='post'>"; //Redirect to self to delete User account
        echo "Are you sure you want to delete your account? (Cannot be undone)<br><br>";
        echo "<input hidden name='authToken' value='$authToken'>";
        echo "<input hidden name='deleteAccountToken' value='$deleteAccountToken'>";
        echo "<input name='btnYes' value='Yes' type='submit'>";
        echo "</form>";
        echo "<form action='profile.php' method='post'>"; //Redirect to Profile Page if "No" is clicked
        echo "<input hidden name='authToken' value='$authToken'>";
        echo "<input value='No' type='submit'>";
        echo "</form>";
    }
}
?>