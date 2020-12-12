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
            $codePattern='/^[0-9]{6}$/';
            $configure2FAToken=$_POST['configure2FAToken'];
            if (isset($_POST['btnVerify'])){
                $code=$_POST['code'];
                if (!preg_match($codePattern, $code)){
                    echo "<form action='commitConfig2FADo.php?error=invalidCharacters' method='post'>";
                    echo "<input hidden name='authToken' value='$authToken'>";
                    echo "<input hidden name='configure2FAToken' value='$configure2FAToken'>";
                    echo "<input type='submit' name='btnResetYes' value='Yes' id='btnResubmitForm'>";
                    echo "</form>";
                    echo "<script type='text/javascript'>document.getElementById('btnResubmitForm').click();</script>";
                    exit();
                }
                else{
                    $isVerified=$ga->verifyCode($_SESSION['googleSecret'], $code);
                    if ($isVerified){
                        if (isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){
                            $stmt=$conn->prepare('UPDATE users SET googleSecret=? where usersId=?');
                            $stmt->bind_param('ss', $_SESSION['googleSecret'],$_SESSION['usersID']);
                            if ($stmt->execute()){
                                echo 'Successfully set 2FA configuration!<br>';
                            }
                            else{
                                echo "There was an issue setting 2FA configuration!<br>";
                            }
                            $stmt->close();
                        }
                        elseif (!isset($_SESSION['usersID']) && isset($_SESSION['providersID'])){
                            $stmt=$conn->prepare('UPDATE providers SET googleSecret=? where providersId=?');
                            $stmt->bind_param('ss', $_SESSION['googleSecret'],$_SESSION['providersID']);
                            if ($stmt->execute()){
                                echo 'Successfully set 2FA configuration!<br>';
                            }
                            else{
                                echo "There was an issue setting 2FA configuration!<br>";
                            }
                            $stmt->close();
                        }
                    }
                    
                    else{
                        echo "<form action='commitConfig2FADo.php?error=invalidCode' method='post'>";
                        echo "<input hidden name='authToken' value='$authToken'>";
                        echo "<input hidden name='configure2FAToken' value='$configure2FAToken'>";
                        echo "<input type='submit' name='btnResetYes' value='Yes' id='btnResubmitForm'>";
                        echo "</form>";
                        echo "<script type='text/javascript'>document.getElementById('btnResubmitForm').click();</script>";
                        exit();
                    }
                }
                
            }
            elseif (isset($_POST['btnResetYes'])){
                $username=$_SESSION['username'];
                $newGoogleQRUrl=$ga->getQRCodeGoogleUrl("SWAPWebsite ($username)", $_SESSION['googleSecret']);
                //Redirect User to verify code
                echo "<form action='commitConfig2FADo.php' method='POST'>";
                echo "Scan this QR code and verify new code on Google Authenticator Mobile Application<br>";
                echo "<img src='$newGoogleQRUrl'><br>";
                echo "<input name='code'>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "<input hidden name='configure2FAToken' value='$configure2FAToken'>";
                echo "<input type='submit' name='btnVerify' value='Verify Code'>";
                echo "</form>";
                if (isset($_GET['error']) && $_GET['error'] == 'invalidCode'){
                    echo "<p style='color: red;'>Incorrect code!</p>";
                }
                elseif (isset($_GET['error']) && $_GET['error'] == 'invalidCharacters'){
                    echo "<p style='color: red;'>Code consist of only 6 numerical characters!</p>";
                }
                //Redirect User back to Config 2FA page
                echo "<form action='config2FA.php' method='POST'>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "<input type='submit' name='btnBack' value='Back'>";
                echo "</form>";
                exit();
            }
            elseif (isset($_POST['btnRemoveYes'])){ //If user selected "Yes" to remove 2FA
                if (isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){
                    $stmt=$conn->prepare('UPDATE users SET googleSecret=NULL where usersId=?');
                    $stmt->bind_param('s', $_SESSION['usersID']);
                    if ($stmt->execute()){
                        echo 'Successfully removed 2FA configuration!<br>';
                    }
                    else{
                        echo "There was an issue removing 2FA configuration!<br>";
                    }
                    $stmt->close();
                }
                elseif (!isset($_SESSION['usersID']) && isset($_SESSION['providersID'])){
                    $stmt=$conn->prepare('UPDATE providers SET googleSecret=NULL where providersId=?');
                    $stmt->bind_param('s',$_SESSION['providersID']);
                    if ($stmt->execute()){
                        echo 'Successfully removed 2FA configuration!<br>';
                    }
                    else{
                        echo "There was an issue removing 2FA configuration!<br>";
                    }
                    $stmt->close();
                }
            }
            unsetVariable('configure2FAToken');
            unsetVariable('configure2FATokenTime');
            unsetVariable('googleSecret');
            unsetVariable('username');
            echo "<br><br><form action ='profilePage.php' method='post'>";
            echo "<input hidden value='$authToken' name='authToken'>";
            echo "<input type='submit' value='Return to Profile'>";
            echo "</form>";
            exit();
        }
    }
}
?>