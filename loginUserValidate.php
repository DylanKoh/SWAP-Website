<html>
<body>
<p>Please enter your 2FA code from the Google Authenticator Application</p>
<form action="" method="post">
Code: <input name="code" type="text"><br>
<input type="submit" name="btnSubmit" value="Verify Code">
</form>
</body>
</html>
<?php
require_once 'PHPGangsta/GoogleAuthenticator.php';
session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE);
session_start();
require_once 'connection.php';
if (isset($_POST['btnSubmit']) && isset($_SESSION['providersID'])){
    if (!empty($_POST['code'])){
        if (!isset($_SESSION['userID'])){
            header('Location:login.php?error=notloggedin');
            exit();
        }
        $ga=new PHPGangsta_GoogleAuthenticator();
        $userGSecret=$_SESSION['googleSecret'];
        $keyedCode=$_POST['code'];
        $isVerified=$ga->verifyCode($userGSecret, $keyedCode);
    }
    
}


?>