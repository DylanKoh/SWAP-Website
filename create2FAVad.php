<?php
require_once 'PHPGangsta/GoogleAuthenticator.php';
require_once 'sessionInitialise.php';
$ga=new PHPGangsta_GoogleAuthenticator();
if (isset($_POST['createAccountToken']) && $_POST['createAccountToken'] == $_SESSION['createAccountToken'] && $googleSecret=$_SESSION['googleSecret']){
    $tokenCreateAccount=time()-$_SESSION['createAccountToken'];
    if ($tokenCreateAccount <= 300 ){ //If token is still below to 5mins old, allow code logic to run
        $googleSecret=$_SESSION['googleSecret'];
        $keyedCode=$_POST['verificationCode'];
        $isVerified=$ga->verifyCode($googleSecret, $keyedCode,0);
        if ($isVerified){
            destroySession();
            echo "Successfully Created an account!";
            echo "<a href='index.php'>Back to Home</a>";
            exit();
        }
        else{
            $createAccountToken=$_POST['createAccountToken'];
            echo "<form action='createAccountDo.php' method='post'>";
            echo "<input hidden name='createAccountToken' value='$createAccountToken'>";
            echo "</form>";
            header('Location:createAccountDo.php?error=incorrectcode');
            exit();
        }
    }
    else{
        unsetVariable('createAccountToken');
        unsetVariable('createAccountTokenTime');
        header("Location:createAccount.php?createAcc=sessionExpired");
        exit();
    }
}
else{
    
}
    
?>