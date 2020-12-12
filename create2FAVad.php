<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'PHPGangsta/GoogleAuthenticator.php';
require_once 'sessionInitialise.php';
$ga=new PHPGangsta_GoogleAuthenticator();
if (isset($_POST['createAccountToken']) && $_POST['createAccountToken'] == $_SESSION['createAccountToken'] && $googleSecret=$_SESSION['googleSecret']){
    $tokenCreateAccountAge=time()-$_SESSION['createAccountTokenTime'];
    if ($tokenCreateAccountAge <= 300 ){ //If token is still below to 5mins old, allow code logic to run
        if (!empty($_POST['verificationCode']) && preg_match('/^[0-9]{6}$/', $_POST['verificationCode'])){
            $googleSecret=$_SESSION['googleSecret'];
            $keyedCode=$_POST['verificationCode'];
            $isVerified=$ga->verifyCode($googleSecret, $keyedCode,0);
            if ($isVerified){
                destroySession();
                echo "Successfully Created an account!<br><br>";
                echo "<a href='index.php'>Back to Home</a>";
                exit();
            }
            else{
                $createAccountToken=$_POST['createAccountToken'];
                echo "<form action='createAccountDo.php?error=incorrectcode' id='resubmitForm' method='post'>";
                echo "<input hidden name='createAccountToken' value='$createAccountToken'>";
                echo "</form>";
                echo "<script type='text/javascript'>
      document.getElementById('resubmitForm').submit();
    </script>";
                exit();
            }
        }
        else{
            $createAccountToken=$_POST['createAccountToken'];
            echo "<form action='createAccountDo.php?error=invalidCharacters' id='resubmitForm' method='post'>";
            echo "<input hidden name='createAccountToken' value='$createAccountToken'>";
            echo "</form>";
            echo "<script type='text/javascript'>
      document.getElementById('resubmitForm').submit();
    </script>";
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