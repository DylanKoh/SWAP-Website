<?php
header("X-Frame-Options: DENY");
require 'connection.php';
require_once 'PHPGangsta/GoogleAuthenticator.php';
require_once 'sessionInitialise.php';
$ga=new PHPGangsta_GoogleAuthenticator();
if (isset($_POST['createAccountToken']) && $_POST['createAccountToken'] == $_SESSION['createAccountToken']){
    $tokenCreateAccountAge=time()-$_SESSION['createAccountTokenTime'];
    if ($tokenCreateAccountAge <= 300 ){ //If token is still below to 5mins old, allow code logic to run
        if (isset($_POST["btnCreate"])){
            $fullName=$_POST['fullname'];
            $username=$_POST['username'];
            $email=$_POST['email'];
            $password=$_POST['password'];
            $rePassword=$_POST['rePassword'];
            $fullnamePattern='/^^[a-zA-Z\s]+$/';
            $usernamePattern='/^([a-zA-Z0-9]+[_?!]*)+$/';
            if (empty($fullName) || empty($username) || empty($email) || empty($password) || empty($rePassword) || !isset($_POST['rbType'])){
                header("Location:createAccount.php?error=emptyfields&fullname=$fullName&username=$username&email=$email");
                exit();
            }
            elseif (!preg_match($fullnamePattern, $fullName) || !preg_match($usernamePattern, $username)){
                header("Location:createAccount.php?error=illegalCharacters&email=$email");
                exit();
            }
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                header("Location:createAccount.php?error=notEmail&fullname=$fullName&username=$username");
                exit();
            }
            elseif (!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[\#\?\!\@\$\%\^\&\*\-\.\,\/]).{8,}$/', $password)){
                header("Location:createAccount.php?error=passwordWeak&fullname=$fullName&username=$username&email=$email");
                exit();
            }
            elseif ($password!=$rePassword){
                header("Location:createAccount.php?error=passwordNoMatch&fullname=$fullName&username=$username&email=$email");
                exit();
            }
            else{
                if ($_POST['rbType']=='provider'){
                    $emailCheck=$conn->prepare('SELECT * FROM providers where email=?');
                    $emailCheck->bind_param('s', $email);
                    $emailCheck->execute();
                    $emailResult=$emailCheck->get_result();
                    if ($emailResult->num_rows > 0){
                        header("Location:createAccount.php?error=emailTaken&fullname=$fullName&username=$username");
                        exit();
                    }
                    else{
                        $emailCheck->close();
                        $usernameCheck=$conn->prepare('SELECT * FROM providers where username=?');
                        $usernameCheck->bind_param('s', $username);
                        $usernameCheck->execute();
                        $usernameResult=$usernameCheck->get_result();
                        if ($usernameResult->num_rows > 0){
                            header("Location:createAccount.php?error=usernameTaken&fullname=$fullName&email=$email");
                            exit();
                        }
                        else{
                            $usernameCheck->close();
                            $stmt=$conn->prepare('INSERT INTO providers (name,email,password,salt_1,salt_2,googleSecret,username)
    VALUES (?,?,?,?,?,?,?)');
                            $googleSecret=NULL;
                            $salt_1=$ga->createSecret();
                            $salt_2=$ga->createSecret();
                            if (@$_POST['cb2FA']!=NULL){
                                $googleSecret=$ga->createSecret();
                            }
                            $hashedPassword=hash('sha256', $salt_1.$password);
                            $finalPassword=base64_encode(hash('sha256', $hashedPassword.$salt_2));
                            $stmt->bind_param('sssssss',$fullName,$email,$finalPassword,$salt_1,$salt_2,$googleSecret,$username);
                            if ($stmt->execute()){
                                if ($googleSecret!=NULL){
                                    $createAccountToken=$_POST['createAccountToken'];
                                    $getQRCodeURL=$ga->getQRCodeGoogleUrl("SWAPWebsite ($username)",$googleSecret);
                                    echo "<form action='create2FAVad.php' method='post'>";
                                    echo "<img src='$getQRCodeURL' title='Scan on Google 2FA Application' />";
                                    echo "<br><input name='verificationCode'>";
                                    echo "<br><input type='submit' value='Verify'>";
                                    echo "<input hidden name='createAccountToken' value='$createAccountToken'>";
                                    echo "</form>";
                                    initialiseSessionVar('googleSecret',$googleSecret);
                                    initialiseSessionVar('username',$username);
                                }
                                else{
                                    header("Location:createAccount.php?createAcc=success");
                                    exit();
                                }
                                
                            }
                            else{
                                header("Location:createAccount.php?error=createErr&fullname=$fullName&username=$username&email=$email");
                                exit();
                            }
                            $stmt->close();
                        }
                    }
                    
                    
                }
                elseif ($_POST['rbType']=='customer'){
                    $emailCheck=$conn->prepare('SELECT * FROM users where email=?');
                    $emailCheck->bind_param('s', $email);
                    $emailCheck->execute();
                    $emailResult=$emailCheck->get_result();
                    if ($emailResult->num_rows > 0){
                        header("Location:createAccount.php?error=emailTaken&fullname=$fullName&username=$username");
                        exit();
                    }
                    else{
                        $emailCheck->close();
                        $usernameCheck=$conn->prepare('SELECT * FROM users where username=?');
                        $usernameCheck->bind_param('s', $username);
                        $usernameCheck->execute();
                        $usernameResult=$usernameCheck->get_result();
                        if ($usernameResult->num_rows > 0){
                            header("Location:createAccount.php?error=usernameTaken&fullname=$fullName&email=$email");
                            exit();
                        }
                        else{
                            $usernameCheck->close();
                            $stmt=$conn->prepare('INSERT INTO users (name,email,password,salt_1,salt_2,googleSecret,username)
    VALUES (?,?,?,?,?,?,?)');
                            $googleSecret=NULL;
                            $salt_1=$ga->createSecret();
                            $salt_2=$ga->createSecret();
                            if (@$_POST['cb2FA']!=NULL){
                                $googleSecret=$ga->createSecret(); // Creates Google Secret
                            }
                            $hashedPassword=hash('sha256', $salt_1.$password);
                            $finalPassword=base64_encode(hash('sha256', $hashedPassword.$salt_2));
                            $stmt->bind_param('sssssss',$fullName,$email,$finalPassword,$salt_1,$salt_2,$googleSecret,$username);
                            if ($stmt->execute()){
                                if ($googleSecret!=NULL){
                                    $createAccountToken=$_POST['createAccountToken'];
                                    $getQRCodeURL=$ga->getQRCodeGoogleUrl("SWAPWebsite ($username)",$googleSecret);
                                    echo "<form action='create2FAVad.php' method='post'>";
                                    echo "<img src='$getQRCodeURL' title='Scan on Google 2FA Application' />";
                                    echo "<br><input name='verificationCode'>";
                                    echo "<br><input type='submit' value='Verify'>";
                                    echo "<input hidden name='createAccountToken' value='$createAccountToken'>";
                                    echo "</form>";
                                    initialiseSessionVar('googleSecret',$googleSecret);
                                    initialiseSessionVar('username',$username);
                                }
                                else{
                                    destroySession();
                                    echo "Successfully Created an account!";
                                    echo "<a href='index.php'>Back to Home</a>";
                                    exit();
                                }
                                
                            }
                            else{
                                header("Location:createAccount.php?error=createErr&fullname=$fullName&username=$username&email=$email");
                                exit();
                            }
                            $stmt->close();
                        }
                    }
                }
                
            }
            
        }
        else{
            $googleSecret=$_SESSION['googleSecret'];
            $username=$_SESSION['username'];
            $createAccountToken=$_POST['createAccountToken'];
            $getQRCodeURL=$ga->getQRCodeGoogleUrl("SWAPWebsite ($username)",$googleSecret);
            echo "<form action='create2FAVad.php' method='post'>";
            echo "<img src='$getQRCodeURL' title='Scan on Google 2FA Application' />";
            echo "<br><input name='verificationCode'>";
            echo "<br><input type='submit' value='Verify'>";
            echo "<input hidden name='createAccountToken' value='$createAccountToken'>";
            echo "</form>";
            if (isset($_GET['error']) && $_GET['error']=='incorrectcode'){
                
                echo "<p style='color: red;'>Incorrect code!</p>";
            }
            elseif (isset($_GET['error']) && $_GET['error']=='invalidCharacters'){
                echo "<p style='color: red;'>Code consist of only 6 numerical characters!</p>";
            }
        }
    }
    else{
        unsetVariable('createAccountToken');
        unsetVariable('createAccountTokenTime');
        header("Location:createAccount.php?createAcc=sessionExpired");
    }
}
else{
    header('HTTP/1.0 403 Forbidden');
    exit();
}


?>