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
if (isset($_GET['error']) && $_GET['error'] == 'incorrectcode'){
    echo "<p style='color: red;'>Incorrect code!</p>";
}
?>
<input type="submit" name="btnSubmit" value="Verify Code">
</form>
</body>
</html>
<?php
require_once 'PHPGangsta/GoogleAuthenticator.php'; //Require 2FA code
require_once 'sessionInitialise.php'; //Initialise Session
include_once 'alertMessageFunc.php'; //Include the need for prompt messages
require_once 'validateToken.php';
if (isset($_SESSION['usersID'])){ //Checks if session value 'usersID' is set before running page
    if (!verifyToken('2FAToken', 180))  //Check if token is not valid
    {
        destroySession();
        header('Location:providerLogin.php?error=errToken');
        exit();
    }
    else{
        if (isset($_POST['btnSubmit'])){
            if (!empty($_POST['code']) && preg_match('/^[0-9]{6}$/', $_POST['code'])){ //Check if code only have  6 numeric characters, if so, run
                $ga=new PHPGangsta_GoogleAuthenticator(); //Initialise the Google Authenticator class
                $userGSecret=$_SESSION['googleSecret'];
                $keyedCode=$_POST['code'];
                $isVerified=$ga->verifyCode($userGSecret, $keyedCode); //Create if user's googleSecret and inputted code corresponds with Google's generated code
                if ($isVerified){ //If user's code is verified, run code
                    unsetVariable('2FAToken');
                    unsetVariable('2FATokenTime');
                    unsetVariable('googleSecret');
                    $authToken=hash('sha256', uniqid(rand(), TRUE));
                    initialiseSessionVar('authToken', $authToken);
                    initialiseSessionVar('authTokenTime', time());
                    echo "<form action='storePage.php' id='submitForm' method='post'>";
                    echo "<input hidden name='authToken' value='$authToken'>";
                    echo "</form>";
                    echo "<script type='text/javascript'>document.getElementById('submitForm').submit();</script>";
                    exit();
                }
                else{ //If user's code is incorrect, redirect user to same page with GET error message
                    header('Location:loginUserValidate.php?error=incorrectcode');
                    exit();
                }
                
            }
            else{ //If code contains illegal characters, prompt user with message and do not run verification
                promptMessage('Allowed code is only 6 numeric characters!');
            }
        }
    }
}
else{ //If session variable for usersID is not set, user is not logged in and session is destroyed (if any). A re-login is a must
    destroySession();
    header('Location:;ogin.php?error=notloggedin');
    exit();
}

?>