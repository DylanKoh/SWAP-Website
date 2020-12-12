<?php 
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'"); //Allows only script from this page to run, preventing XSS and clickjacking
header("X-Frame-Options: DENY"); //Denys the use of <frame>, <iframe>, <embed> and <object> to protect clients from clickjacking
?>
<html>
<body>
<p>Please enter your 2FA code from the Google Authenticator Application</p>

<form action="" method="post">
Code: <input name="code" type="text"><br>
<input hidden name='2FAToken' value="<?php echo $_POST["2FAToken"]?>">
<?php
if (isset($_GET['error']) && $_GET['error'] == 'incorrectcode'){ //If GET error message = 'incorrectcode', print red text error message
    echo "<p style='color: red;'>Incorrect code!</p>";
}
?>
<input type="submit" name="btnSubmit" value="Verify Code">
</form>
</body>
</html>
<?php
require_once 'PHPGangsta/GoogleAuthenticator.php'; //Require Google 2FA code
require_once 'sessionInitialise.php'; //Require session initialisation
require_once 'alertMessageFunc.php'; //Require alert function
require_once 'validateToken.php';
if (isset($_SESSION['providersID'])){ //Check if session variable 'providersID' is set. If so, user is validated and logged in and run code
    if (!verifyToken('2FAToken', 180)){ //Check if token not valid
        destroySession();
        header('Location:providerLogin.php?error=errToken');
        exit();
    }
    else{ //If token is valid
            if (isset($_POST['btnSubmit'])){
                if (!empty($_POST['code']) && preg_match('/^[0-9]{6}$/', $_POST['code'])){
                    $ga=new PHPGangsta_GoogleAuthenticator();
                    $userGSecret=$_SESSION['googleSecret'];
                    $keyedCode=$_POST['code'];
                    $isVerified=$ga->verifyCode($userGSecret, $keyedCode);
                    if ($isVerified){ //If keyed code is verified and correct, run code
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
                    else{ //If keyed 2FA code is not correct, redirect to self with GET error message
                        header('Location:loginProviderValidate.php?error=incorrectcode');
                        exit();
                    }
                }
                else{ //If keyed 2FA code is not 6 numerical characters, alert user
                    promptMessage('Allowed code is only 6 numeric characters!');
                }
            }
            
        }
        
}
else{ //If session variable for providersID is not set, user is not logged in and session is destroyed (if any). A re-login is a must
    destroySession();
    header('Location:providerLogin.php?error=notloggedin');
    exit();
}
?>