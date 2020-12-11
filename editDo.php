<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php'; //Initialise Session
require 'connection.php';
if(!isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){ //If user does not have any ID in their session
    destroySession();
    header('Location:login.php?error=notloggedin');
    exit();
}
else{ //If an ID of sorts is assigned in the session variables
    if (isset($_POST['authToken']) && $_POST['authToken'] == $_SESSION['authToken']){ //If token is valid
        $sessionAge=time()-$_SESSION['authTokenTime'];
        if ($sessionAge > 1200){ //If token age is over lifetime of 20mins
            destroySession();
            if (isset($_SESSION['providersID'])){ //If initial user is a Provider
                header('Location:providerLogin.php?error=sessionExpired');
                exit();
            }
            else{ //If initial user is a Customer
                header('Location:login.php?error=sessionExpired');
                exit();
            }
        }
        else{
            $authToken=$_POST['authToken'];
            $fullName=$_POST['fullname'];
            $username=$_POST['username'];
            $email=$_POST['email'];
            $fullnamePattern='/^^[a-zA-Z\s]+$/'; //Allow only alphabet characters
            $usernamePattern='/^([a-zA-Z0-9]+[_?!]*)+$/'; //Regex expression for username
            $emailPattern='/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i'; //Regex expression for emails
            if (empty($fullName) || empty($username) || empty($email)){ //If any required fields are empty
                header("Location:profilePage.php?error=emptyfields");
                exit();
            }
            elseif (!preg_match($fullnamePattern, $fullName) || !preg_match($usernamePattern, $username)){ //If username or fullname contains illegal characters
                header("Location:profilePage.php?error=illegalCharacters");
                exit();
            }
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match($emailPattern, $email)){ //If email is not a valid email
                header("Location:profilePage.php?error=notEmail");
                exit();
            }
            else{
                if (isset($_SESSION['providersID'])){ //If user is a Provider;
                    $providersID=$_SESSION['providersID'];
                    $emailCheck=$conn->prepare('SELECT * FROM providers where email=? and providersID!=?');
                    $emailCheck->bind_param('si', $email,$providersID);
                    $emailCheck->execute();
                    $emailResult=$emailCheck->get_result();
                    if ($emailResult->num_rows > 0){ //If email to be used exist
                        header("Location:profilePage.php?error=emailTaken&fullname=$fullName&username=$username");
                        exit();
                    }
                    else{ //If email to be used does not exist
                        $emailCheck->close();
                        $usernameCheck=$conn->prepare('SELECT * FROM providers where username=? and providersID!=?');
                        $usernameCheck->bind_param('si', $username, $providersID);
                        $usernameCheck->execute();
                        $usernameResult=$usernameCheck->get_result();
                        if ($usernameResult->num_rows > 0){ //If username to be used exist
                            header("Location:profilePage.php?error=usernameTaken&fullname=$fullName&email=$email");
                            exit();
                        }
                        else{ //If username to be used does not exist
                            $usernameCheck->close();
                            $stmt=$conn->prepare('UPDATE providers SET username=?,email=?,name=? where providersID=?');
                            $stmt->bind_param('sssi',$username,$email,$fullName, $providersID);
                            if ($stmt->execute()){ //If update user data is successful
                                echo "Successfully updated user data!";
                            }
                            else{ //If update user data is not successful
                                echo "There was an error updated user data!";
                            }
                            $stmt->close();
                        }
                    }
                }
                elseif (isset($_SESSION['usersID'])){ //If user is a Customer
                    $usersID=$_SESSION['usersID'];
                    $emailCheck=$conn->prepare('SELECT * FROM users where email=? and usersID!=?');
                    $emailCheck->bind_param('si', $email,$usersID);
                    $emailCheck->execute();
                    $emailResult=$emailCheck->get_result();
                    if ($emailResult->num_rows > 0){ //If email to be used exist
                        header("Location:profilePage.php?error=emailTaken&fullname=$fullName&username=$username");
                        exit();
                    }
                    else{ //If email to be used does not exist
                        $emailCheck->close();
                        $usernameCheck=$conn->prepare('SELECT * FROM users where username=? and usersID!=?');
                        $usernameCheck->bind_param('si', $username,$usersID);
                        $usernameCheck->execute();
                        $usernameResult=$usernameCheck->get_result();
                        if ($usernameResult->num_rows > 0){ //If username to be used exist
                            header("Location:profilePage.php?error=usernameTaken&fullname=$fullName&email=$email");
                            exit();
                        }
                        else{ //If username to be used does not exist
                            $usernameCheck->close();
                            $stmt=$conn->prepare('UPDATE users SET username=?,email=?,name=? where usersID=?');
                            $stmt->bind_param('sssi',$username,$email,$fullName, $_SESSION['usersID']);
                            if ($stmt->execute()){ //If update user data is successful
                                echo "Successfully updated user data!";
                            }
                            else{ //If update user data is not successful
                                echo "There was an error updated user data!";
                            }
                            $stmt->close();
                        }
                    }
            
                }
                echo "<br><br><form action ='profilePage.php' method='post'>";
                echo "<input hidden value='$authToken' name='authToken'>";
                echo "<input type='submit' value='Return to Profile'>";
                echo "</form>";
                exit();
            }
        }
        
    }
    else{
        destroySession();
        if (isset($_SESSION['providersID'])){
            header('Location:providerLogin.php?error=invalidToken');
            exit();
        }
        else{
            header('Location:login.php?error=invalidToken');
            exit();
        }
        
    }
}
?>