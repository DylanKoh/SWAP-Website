<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
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
        if (!verifyToken('resetPasswordToken', 300)){
            unsetVariable('resetPasswordToken');
            unsetVariable('resetPasswordTokenTime');
            echo "<form action='resetPassword.php?error=errToken' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo "</form";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
        }
        else{
            $oldPassword=$_POST['oldPassword'];
            $newPassword=$_POST['newPassword'];
            $reNewPassword=$_POST['reNewPassword'];
            $passwordPattern='/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[\#\?\!\@\$\%\^\&\*\-\.\,\/]).{8,}$/'; //Regex expression for Password
            if (empty($oldPassword) || empty($newPassword) || empty($reNewPassword)){ //If any required fields are empty
                header("Location:resetPassword.php?error=emptyfields");
                exit();
            }
            elseif (!preg_match($passwordPattern, $newPassword)){ //If password is not valid
                header("Location:resetPassword.php?error=passwordWeak");
                exit();
            }
            elseif ($newPassword!=$reNewPassword){ //If passwords don't match
                header("Location:resetPassword.php?error=passwordNoMatch");
                exit();
            }
            else{
                if (isset($_SESSION['providersID'])){ //If user is a Provider;
                    $providersID=$_SESSION['providersID'];
                    $passwordCheck=$conn->prepare('SELECT salt_1,salt_2,password FROM providers where providersID=?');
                    $passwordCheck->bind_param('s',$providersID);
                    $passwordCheck->execute();
                    $passwordCheck->bind_result($salt_1,$salt_2,$correctPassword);
                    if ($passwordCheck->fetch()){ //If password retrieval was successful
                        $hash1Password=hash('sah256', $salt_1.$oldPassword);
                        $encodedPassword=base64_encode(hash('sah256', $hash1Password.$salt_2));
                        if ($encodedPassword!=$correctPassword){ //If old password is not correct
                            unsetVariable('resetPasswordToken');
                            unsetVariable('resetPasswordTokenTime');
                            echo "<form action='resetPassword.php?error=passwordIncorrect' id='returnForm' method='post'>";
                            echo "<input hidden name='authToken' value='$authToken'>";
                            echo "</form";
                            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
                        }
                        else{ //If old password is not correct
                            $passwordCheck->close();
                            $newSalt_1=$ga->createSecret();
                            $newSalt_2=$ga->createSecret();
                            $hashNewPassword=hash('sah256', $newSalt_1.$newPassword);
                            $encodedNewPassword=base64_encode(hash('sah256', $hashNewPassword.$newSalt_2));
                            $newPasswordDate=date_format(date_create(), 'Y-m-d');
                            $stmt=$conn->prepare('UPDATE providers SET salt_1=?,salt_2=?,password=?,passwordDate=? where providersID=?');
                            $stmt->bind_param('sssss', $newSalt_1, $newSalt_2, $encodedNewPassword, $newPasswordDate, $providersID);
                            if ($stmt->execute()){ //If update user password is successful
                                echo "Successfully resetted user password!";
                            }
                            else{ //If update user password is not successful
                                echo "There was an error resetting user data!";
                            }
                        }
                    }
                    else{ //If password retrieval was not successful
                        unsetVariable('resetPasswordToken');
                        unsetVariable('resetPasswordTokenTime');
                        echo "<form action='resetPassword.php?error=databaseErr' id='returnForm' method='post'>";
                        echo "<input hidden name='authToken' value='$authToken'>";
                        echo "</form";
                        echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
                    }
                }
                elseif (isset($_SESSION['usersID'])){ //If user is a Customer
                    $usersID=$_SESSION['usersID'];
                    $passwordCheck=$conn->prepare('SELECT salt_1,salt_2,password FROM users where usersID=?');
                    $passwordCheck->bind_param('s',$usersID);
                    $passwordCheck->execute();
                    $passwordCheck->bind_result($salt_1,$salt_2,$correctPassword);
                    if ($passwordCheck->fetch()){ //If password retrieval was successful
                        $hash1Password=hash('sah256', $salt_1.$oldPassword);
                        $encodedPassword=base64_encode(hash('sah256', $hash1Password.$salt_2));
                        if ($encodedPassword!=$correctPassword){ //If old password is not correct
                            unsetVariable('resetPasswordToken');
                            unsetVariable('resetPasswordTokenTime');
                            echo "<form action='resetPassword.php?error=passwordIncorrect' id='returnForm' method='post'>";
                            echo "<input hidden name='authToken' value='$authToken'>";
                            echo "</form";
                            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
                        }
                        else{ //If old password is correct
                            $passwordCheck->close();
                            $newSalt_1=$ga->createSecret();
                            $newSalt_2=$ga->createSecret();
                            $hashNewPassword=hash('sah256', $newSalt_1.$newPassword);
                            $encodedNewPassword=base64_encode(hash('sah256', $hashNewPassword.$newSalt_2));
                            $newPasswordDate=date_format(date_create(), 'Y-m-d');
                            $stmt=$conn->prepare('UPDATE providers SET salt_1=?,salt_2=?,password=?,passwordDate=? where usersID=?');
                            $stmt->bind_param('sssss', $newSalt_1, $newSalt_2, $encodedNewPassword, $newPasswordDate, $usersID);
                            if ($stmt->execute()){ //If update user password is successful
                                echo "Successfully resetted user password!";
                            }
                            else{ //If update user password is not successful
                                echo "There was an error resetting user data!";
                            }
                        }
                    }
                    else{ //If password retrieval was not successful
                        unsetVariable('resetPasswordToken');
                        unsetVariable('resetPasswordTokenTime');
                        echo "<form action='resetPassword.php?error=databaseErr' id='returnForm' method='post'>";
                        echo "<input hidden name='authToken' value='$authToken'>";
                        echo "</form";
                        echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
                    }
                }
                unsetVariable('resetPasswordToken');
                unsetVariable('resetPasswordTokenTime');
                echo "<br><br><form action ='profilePage.php' method='post'>";
                echo "<input hidden value='$authToken' name='authToken'>";
                echo "<input type='submit' value='Return to Profile'>";
                echo "</form>";
                exit();
            }
        }
    }
    
    
}
?>