<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'"); //Allows only script from this page to run, preventing XSS and clickjacking
header("X-Frame-Options: DENY"); //Denys the use of <frame>, <iframe>, <embed> and <object> to protect clients from clickjacking
include 'connection.php'; //Include login connection to database
include_once 'sessionInitialise.php';
if (isset($_POST["btnLogin"])){ //Check if button is login clicked
    if (!empty($_POST['username']) && !empty($_POST['password'])){ //Check if username and password is empty, if not run code
        $username=$_POST['username'];
        $password=$_POST['password'];
        $stmt=$conn->prepare('SELECT providersID,password,salt_1,salt_2,googleSecret,passwordDate FROM providers where username=?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($providersID, $correctPassword, $salt_1, $salt_2, $googleSecret, $passwordDate);
        if ($stmt->fetch()){ //Check if user account exist, if so, run code
            $password1=$salt_1.$password;
            $hash_1=hash('sha256', $password1);
            $password2=$hash_1.$salt_2;
            $hash_2=hash('sha256', $password2);
            $encodedPassword=base64_encode($hash_2);
            if ($encodedPassword!=$correctPassword){ //Check if password keyed in is correct, if not, redirect user back to providerLogin with GET error message
                header('Location:providerLogin.php?error=invalid');
            }
            else{ //If password is correct, run code
                initialiseSessionVar('providersID', $providersID);
                if($googleSecret!=NULL){ //If googleSecret field in dB is not null, run code for 2FA
                    initialiseSessionVar('googleSecret', $googleSecret);
                    $auth2FAToken=hash('sha256', uniqid(rand(), TRUE));
                    initialiseSessionVar('2FAToken', $auth2FAToken);
                    initialiseSessionVar('2FATokenTime', time());
                    echo "<form action='loginProviderValidate.php' id='submitForm' method='post'>";
                    echo "<input hidden name='2FAToken' value='$auth2FAToken'>";
                    echo "</form>";
                    echo "<script type='text/javascript'>
  document.getElementById('submitForm').submit();
</script>";
                    exit();
                }
                else{ //If googleSecret field in dB is null, create token of session and redirect user to main page after login
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
            }
        }
        else{ //If user account does not exist, redirect user back to providerLogin with GET error message
            header('Location:providerLogin.php?error=invalid');
            exit();
        }
    }
    else{ //Check if username and password is empty, if empty redirect user back to providerLogin with GET error message
        header('Location:providerLogin.php?error=empty');
        exit();
    }
    
    
}
?>