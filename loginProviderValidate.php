
<html>
<body>
<p>Please enter your 2FA code from the Google Authenticator Application</p>

<form action="" method="post">
Code: <input name="code" type="text"><br>
<?php
if (isset($_GET['error']) && $_GET['error'] == 'incorrectcode'){
    echo "<p style='color: red;'>Incorrect code!</p>";
}
?>
<input type="submit" name="btnSubmit" value="Verify Code">
</form>
</body>
</html>
<?php
require_once 'PHPGangsta/GoogleAuthenticator.php';
session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE); //Sets session only visible in HTTPS
session_start(); //Starts session
require_once 'connection.php';
if (isset($_SESSION['providersID'])){
    if (isset($_POST['2FAToken']) && $_POST['2FAToken'] == $_SESSION['2FAToken'])  //Check if token valid
    {
        $token2FAAge=time()-$_SESSION['2FATokenTime'];
        if ($token2FAAge <= 180 ){ //If token is still below to 3mins old, allow code logic to run
            if (isset($_POST['btnSubmit'])){
                if (!empty($_POST['code'])){
                    $ga=new PHPGangsta_GoogleAuthenticator();
                    $userGSecret=$_SESSION['googleSecret'];
                    $keyedCode=$_POST['code'];
                    $isVerified=$ga->verifyCode($userGSecret, $keyedCode);
                    if ($isVerified){
                        unset($_SESSION['2FAToken']);
                        unset($_SESSION['2FATokenTime']);
                        $authToken=hash('sha256', uniqid(rand(), TRUE));
                        $_SESSION['authToken']=$authToken;
                        $_SESSION['authTokenTime']=time();
                        echo "<form action='storePage.php' method='post'>";
                        echo "<input hidden name='authToken' value='$authToken'>";
                        echo "</form>";
                        header('Location:storePage.php');
                        exit();
                    }
                    else{
                        header('Location:loginProviderValidate.php?error=incorrectcode');
                        exit();
                    }
                }
            }
        }
        else{
            header('Location:providerLogin.php?error=sessionExpired');
            exit();
        }
        
    }
    else{
        header('Location:providerLogin.php?error=invalidToken');
        exit();
    }
}
else{
    header('Location:providerLogin.php?error=notloggedin');
    exit();
}
?>