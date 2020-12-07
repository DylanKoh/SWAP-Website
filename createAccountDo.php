<?php
require 'connection.php';
require_once 'PHPGangsta/GoogleAuthenticator.php';
session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE); //Sets session only visible in HTTPS
session_start(); //Starts session
$ga=new PHPGangsta_GoogleAuthenticator();
if (isset($_POST["btnCreate"])){
    $fullName=$_POST['fullname'];
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $rePassword=$_POST['rePassword'];
    if (empty($fullName) || empty($username) || empty($email) || empty($password) || empty($rePassword) || !isset($_POST['rbType'])){
        header("Location:createAccount.php?error=emptyfields&fullname=$fullName&username=$username&email=$email");
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
                            $getQRCodeURL=$ga->getQRCodeGoogleUrl("SWAPWebsite ($username)",$googleSecret);
                            echo "<form action='create2FAVad.php' method='post'>";
                            echo "<img src='$getQRCodeURL' title='Scan on Google 2FA Application' />";
                            echo "<br><input name='verificationCode'>";
                            echo "<br><input type='submit' value='Verify'>";
                            echo "</form>";
                            $_SESSION['googleSecret']=$googleSecret;
                            $_SESSION['username']=$username;
                        }
                        else{
                            header("Location:createAccount.php?createAcc=success");
                            exit();
                        }
                        
                    }
                    else{
                        //header("Location:createAccount.php?error=createErr&fullname=$fullName&username=$username&email=$email");
                        echo var_dump($salt_1);
                        echo var_dump($salt_2);
                        echo var_dump($finalPassword);
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
                            $getQRCodeURL=$ga->getQRCodeGoogleUrl("SWAPWebsite ($username)",$googleSecret);
                            echo "<form action='create2FAVad.php' method='post'>";
                            echo "<img src='$getQRCodeURL' title='Scan on Google 2FA Application' />";
                            echo "<br><input name='verificationCode'>";
                            echo "<br><input type='submit' value='Verify'>";
                            echo "</form>";
                            $_SESSION['googleSecret']=$googleSecret;
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
        
    }
    
}
else{
    if (isset($_GET['error']) && $_GET['error']=='incorrectcode'){
        $googleSecret=$_SESSION['googleSecret'];
        $username=$_SESSION['username'];
        $getQRCodeURL=$ga->getQRCodeGoogleUrl("SWAPWebsite ($username)",$googleSecret);
        echo "<form action='create2FAVad.php' method='post'>";
        echo "<img src='$getQRCodeURL' title='Scan on Google 2FA Application' />";
        echo "<br><input name='verificationCode'>";
        echo "<br><input type='submit' value='Verify'>";
        echo "</form>";
        echo "<p style='color: red;'>Incorrect code!</p>";
    }
}

?>