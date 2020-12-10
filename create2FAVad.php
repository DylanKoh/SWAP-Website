<?php
require_once 'PHPGangsta/GoogleAuthenticator.php';
session_start(); //Starts session
$ga=new PHPGangsta_GoogleAuthenticator();
if (isset($_POST['createAccountToken']) && $_POST['createAccountToken'] == $_SESSION['createAccountToken'] && $googleSecret=$_SESSION['googleSecret']){
    $tokenCreateAccount=time()-$_SESSION['createAccountToken'];
    if ($tokenCreateAccount <= 300 ){ //If token is still below to 5mins old, allow code logic to run
        $googleSecret=$_SESSION['googleSecret'];
        $keyedCode=$_POST['verificationCode'];
        $isVerified=$ga->verifyCode($googleSecret, $keyedCode,0);
        if ($isVerified){
            session_destroy();
            header('Location:createAccount.php?createAcc=success');
            exit();
        }
        else{
            header('Location:createAccountDo.php?error=incorrectcode');
            exit();
        }
    }
    else{
        unset($_SESSION['createAccountToken']);
        unset($_SESSION['createAccountTokenTime']);
        header("Location:createAccount.php?createAcc=sessionExpired");
        exit();
    }
}
    
?>