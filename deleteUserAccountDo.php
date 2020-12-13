<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php'; //Initialise Session
require 'connection.php';
require_once 'validateToken.php';
include_once 'alertMessageFunc.php';
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
        if (!verifyToken('deleteAccountToken', 300)){ //If token is not valid
            unsetVariable('deleteAccountToken');
            unsetVariable('deleteAccountToken');
            echo "<form action='profilePage.php?error=errToken' id='returnForm' method='post'>";
            echo "<input hidden name='authToken' value='$authToken'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('returnForm').submit();</script>";
            exit();
        }
        else{ //If token is still valid
            if (isset($_POST['btnYes'])){ //Check if user confirms to delete account
                $deleteAccountToken=$_POST['deleteAccountToken'];
                echo "<form action='deleteUserAccountDo.php' method='post'>";
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "<input hidden name='deleteAccountToken' value='$deleteAccountToken'>";
                echo "Enter Password to verify: <br>";
                echo "<input name='password' type='password'>";
                echo "<input type='submit' name='btnVerifyPassword' value='Verify Password'>";
                echo "</form>";
                
                echo "<form action='profile.php' method='post'>"; //Redirect to Profile Page if "Back" is clicked
                echo "<input hidden name='authToken' value='$authToken'>";
                echo "<input value='Back' type='submit'>";
                echo "</form>";
                exit();
            }
            elseif (isset($_POST['btnVerifyPassword'])){ //Check when user click on verify password button when attempt to delete account
                $password=$_POST['password'];
                if (empty($password)){ //If password field is empty
                    echo "<form action='deleteUserAccountDo.php?error=emptyfield' method='post'>";
                    echo "<input hidden name='authToken' value='$authToken'>";
                    echo "<input hidden name='deleteAccountToken' value='$deleteAccountToken'>";
                    echo "<input type='submit' name='btnYes' id='btnReturnForm'>";
                    echo "<script type='text/javascript'>document.getElementById('btnReturnForm').click();</script>";
                    echo "</form>";
                    exit();
                }
                else{ //If password field is not empty
                    $passwordCheck=$conn->prepare('SELECT password,salt_1,salt_2 FROM users WHERE usersId=?');
                    $passwordCheck->bind_param('s', $_SESSION['usersID']);
                    $passwordCheck->execute();
                    $passwordCheck->bind_result($correctPassword,$salt_1,$salt_2);
                    if ($passwordCheck->fetch()){ //If fetching of user's password is successful
                        $hash1=hash('sha256', $salt_1.$password);
                        $encodedFinalPassword=base64_encode(hash('sha256', $hash1.$salt_2));
                        if ($correctPassword!=$encodedFinalPassword){
                            echo "<form action='deleteUserAccountDo.php?error=passwordWrong' method='post'>";
                            echo "<input hidden name='authToken' value='$authToken'>";
                            echo "<input hidden name='deleteAccountToken' value='$deleteAccountToken'>";
                            echo "<input type='submit' name='btnYes' id='btnReturnForm'>";
                            echo "<script type='text/javascript'>document.getElementById('btnReturnForm').click();</script>";
                            echo "</form>";
                            exit();
                        }
                        else{
                            $passwordCheck->close();
                            $stmt=$conn->prepare('DELETE users FROM users where usersId=?');
                            $stmt->bind_param('s', $_SESSION['usersID']);
                            if($stmt->execute()){
                                promptMessage("User account successfully deleted! You will be redirected back to the main page!");
                                destroySession();
                                echo '<a href="index.php">Return to main page</a>';
                                exit();
                            }
                        }
                    }
                    else{
                        echo "<form action='deleteUserAccountDo.php?error=dberror' method='post'>";
                        echo "<input hidden name='authToken' value='$authToken'>";
                        echo "<input hidden name='deleteAccountToken' value='$deleteAccountToken'>";
                        echo "<input type='submit' name='btnYes' id='btnReturnForm'>";
                        echo "<script type='text/javascript'>document.getElementById('btnReturnForm').click();</script>";
                        echo "</form>";
                        exit();
                    }
                }
            }
        }
    }
}
?>
<?php 
require_once 'alertMessageFunc.php';
if (isset($_GET['error']) && $_GET['error'] == 'emptyfield'){
    promptMessage('Please fill in the password field!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'dberror'){
    promptMessage('There was an error contacting the database! Please try again later!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'passwordWrong'){
    promptMessage('The !');
}
?>