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
if (isset($_POST['btnSubmit']) && isset($_SESSION['userID'])){
    if (!empty($_POST['code'])){
        $ga=new PHPGangsta_GoogleAuthenticator();
        $userGSecret=$_SESSION['googleSecret'];
        $keyedCode=$_POST['code'];
        $isVerified=$ga->verifyCode($userGSecret, $keyedCode);
        if ($isVerified){
            header('Location:storePage.php');
            exit();
        }
        else{
            header('Location:loginUserValidate.php?error=incorrectcode');
            exit();
        }
    }
    
}
else{
    header('Location:login.php?error=notloggedin');
    exit();
}


?>