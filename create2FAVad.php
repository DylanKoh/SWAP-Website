<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'PHPGangsta/GoogleAuthenticator.php'; //Require Google 2FA code
require_once 'sessionInitialise.php'; //Initialise Session
$ga=new PHPGangsta_GoogleAuthenticator(); 

//Check if createAccountToken is valid, if so, run code
if (isset($_POST['createAccountToken']) && $_POST['createAccountToken'] == $_SESSION['createAccountToken'] && $googleSecret=$_SESSION['googleSecret']){ 
    $tokenCreateAccountAge=time()-$_SESSION['createAccountTokenTime'];
    if ($tokenCreateAccountAge <= 300 ){ //If token is still below to 5mins old, allow code logic to run
        if (!empty($_POST['verificationCode']) && preg_match('/^[0-9]{6}$/', $_POST['verificationCode'])){ //Check is 2FA code is 6 numeric numbers
            $googleSecret=$_SESSION['googleSecret'];
            $keyedCode=$_POST['verificationCode'];
            $isVerified=$ga->verifyCode($googleSecret, $keyedCode,0);
            if ($isVerified){ //Check if 2FA code keyed in is correct, if so, run
                destroySession();
                echo "Successfully Created an account!<br><br>";
                echo "<a href='index.php'>Back to Home</a>";
                exit();
            }
            else{ //Check if 2FA code keyed in is not correct, if so, run
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
        else{ //Check is 2FA code is not 6 numeric numbers
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
    else{ //If token expired, redirect user to recreate new token
        unsetVariable('createAccountToken');
        unsetVariable('createAccountTokenTime');
        header("Location:createAccount.php?createAcc=sessionExpired");
        exit();
    }
}
else{ //If token is invalid, redirect user to recreate new token
    unsetVariable('createAccountToken');
    unsetVariable('createAccountTokenTime');
    header("Location:createAccount.php?createAcc=invalidToken");
    exit();
}
    
?>