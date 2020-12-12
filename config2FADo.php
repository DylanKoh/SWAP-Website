<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; img-src https://api.qrserver.com/v1/create-qr-code/; style-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php'; //Initialise Session
require 'connection.php';
require_once 'validateToken.php';
require_once 'PHPGangsta/GoogleAuthenticator.php';
$ga=new PHPGangsta_GoogleAuthenticator();
if(!isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){ //If user does not have any ID in their session
    destroySession();
    header('Location:login.php?error=notloggedin');
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
        if (!verifyToken('configure2FAToken', 300)){
            unsetVariable('configure2FAToken');
            unsetVariable('configure2FATokenTime');
            echo "<form action='config2FA.php?error=errToken' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
        }
        else{
            $configure2FAToken=$_POST['configure2FAToken'];
            $username='';
            if (isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){
                $stmt=$conn->prepare('SELECT username FROM users where usersId=?');
                $stmt->bind_param('s', $_SESSION['usersID']);
                $stmt->execute();
                $stmt->bind_result($username);
                if ($stmt->fetch()){
                    echo 'Successfully retrieved user information!<br>';
                }
                else{
                    echo "There was an issue retrieving user information!<br>";
                }
                $stmt->close();
            }
            elseif (!isset($_SESSION['usersID']) && isset($_SESSION['providersID'])){
                $stmt=$conn->prepare('SELECT username FROM providers where providersId=?');
                $stmt->bind_param('s', $_SESSION['providersID']);
                $stmt->execute();
                $stmt->bind_result($username);
                if ($stmt->fetch()){
                    echo 'Successfully retrieved user information!<br>';
                }
                else{
                    echo "There was an issue retrieving user information!<br>";
                }
                $stmt->close();
            }
            $newGoogleSecret=$ga->createSecret();
            $newGoogleQRUrl=$ga->getQRCodeGoogleUrl("SWAPWebsite ($username)", $newGoogleSecret);
            $_SESSION['googleSecret']=$newGoogleSecret;
            $_SESSION['username']=$username;
            if (isset($_POST['btnSetUp'])){
                //Redirect User to verify code
                echo "<form action='commitConfig2FADo.php' method='POST'>";
                echo "Scan this QR code and verify new code on Google Authenticator Mobile Application<br>";
                echo "<img src='$newGoogleQRUrl'><br>";
                echo "<input name='code'>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "<input hidden name='configure2FAToken' value='$configure2FAToken'>";
                echo "<input type='submit' name='btnVerify' value='Verify Code'>";
                echo "</form>";
                
                //Redirect User back to Config 2FA page
                echo "<form action='config2FA.php' method='POST'>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "<input type='submit' name='btnBack' value='Back'>";
                echo "</form>";
            }
            elseif (isset($_POST['btnReset'])){
                //Redirect User to reset 2FA
                echo "<form action='commitConfig2FADo.php' method='POST'>";
                echo "Are you sure you want to reset your 2FA configuration?<br>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "<input hidden name='configure2FAToken' value='$configure2FAToken'>";
                echo "<input type='submit' name='btnResetYes' value='Yes'>";
                echo "</form>";
                //Redirect User back to Config 2FA page
                echo "<form action='config2FA.php' method='POST'>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "<input type='submit' name='btnBack' value='Back'>";
                echo "</form>";
            }
            elseif (isset($_POST['btnDelete'])){
                //Redirect User to delete googleSecret if Yes is selected
                echo "<form action='commitConfig2FADo.php' method='POST'>";
                echo "Are you sure you want to delete your 2FA configuration?<br>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "<input hidden name='configure2FAToken' value='$configure2FAToken'>";
                echo "<input type='submit' name='btnRemoveYes' value='Yes'>";
                echo "</form>";
                //Redirect User back to Config 2FA page
                echo "<form action='config2FA.php' method='POST'>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "<input type='submit' name='btnBack' value='Back'>";
                echo "</form>";
            }
        }
        
        
    }
}
    
    

?>