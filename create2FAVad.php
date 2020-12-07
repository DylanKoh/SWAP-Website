<?php
require_once 'PHPGangsta/GoogleAuthenticator.php';
session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE); //Sets session only visible in HTTPS
session_start(); //Starts session
$ga=new PHPGangsta_GoogleAuthenticator();
$googleSecret=$_SESSION['googleSecret'];
$keyedCode=$_POST['verificationCode'];
$isVerified=$ga->verifyCode($googleSecret, $keyedCode,0);
if ($isVerified){
    header('Location:createAccount.php?createAcc=success');
    exit();
}
else{
    header('Location:createAccountDo.php?error=incorrectcode');
    exit();
}
?>