<?php 
header("Content-Security-Policy: default-src 'self'");
header("X-Frame-Options: DENY");
?>
<html>
<body>
<p>Please enter your 2FA code from the Google Authenticator Application</p>

<form action="" method="post">
Code: <input name="code" type="text"><br>
<input hidden name='2FAToken' value="<?php echo $_POST["2FAToken"]?>">
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
require_once 'sessionInitialise.php';
require_once 'alertMessageFunc.php';
if (isset($_SESSION['providersID'])){
    if (isset($_POST['2FAToken']) && $_POST['2FAToken'] == $_SESSION['2FAToken'])  //Check if token valid
    {
        $token2FAAge=time()-$_SESSION['2FATokenTime'];
        if ($token2FAAge <= 180 ){ //If token is still below to 3mins old, allow code logic to run
            if (isset($_POST['btnSubmit'])){
                if (!empty($_POST['code']) && preg_match('/^[0-9]{6}$/', $_POST['code'])){
                    $ga=new PHPGangsta_GoogleAuthenticator();
                    $userGSecret=$_SESSION['googleSecret'];
                    $keyedCode=$_POST['code'];
                    $isVerified=$ga->verifyCode($userGSecret, $keyedCode);
                    if ($isVerified){
                        unsetVariable('2FAToken');
                        unsetVariable('2FATokenTime');
                        unsetVariable('googleSecret');
                        $authToken=hash('sha256', uniqid(rand(), TRUE));
                        initialiseSessionVar('authToken', $authToken);
                        initialiseSessionVar('authTokenTime', time());
                        echo "<form action='storePage.php' id='submitForm' method='post'>";
                        echo "<input hidden name='authToken' value='$authToken'>";
                        echo "</form>";
                        echo "<script type='text/javascript'>
  document.getElementById('submitForm').submit();
</script>";
                        exit();
                    }
                    else{
                        header('Location:loginProviderValidate.php?error=incorrectcode');
                        exit();
                    }
                }
            }
            else{
                promptMessage('Allowed code is only 6 numeric characters!');
            }
        }
        else{
            destroySession();
            header('Location:providerLogin.php?error=sessionExpired');
            exit();
        }
        
    }
    else{
        destroySession();
        header('Location:providerLogin.php?error=invalidToken');
        exit();
    }
}
else{
    destroySession();
    header('Location:providerLogin.php?error=notloggedin');
    exit();
}
?>