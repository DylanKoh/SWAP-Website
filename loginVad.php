<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'"); //Allows only script from this page to run, preventing XSS and clickjacking
header("X-Frame-Options: DENY"); //Denys the use of <frame>, <iframe>, <embed> and <object> to protect clients from clickjacking
include 'connection.php'; //Include login connection to database
include_once 'sessionInitialise.php'; //Run session initialisation and also allows for usage of cleaner functions within the included functions
if (isset($_POST["btnLogin"])){ //Checks if the post request was send by a button click
    if (!empty($_POST['username']) && !empty($_POST['password'])){ //Checks if username and password fields are empty, if not, run
        $username=$_POST['username'];
        $password=$_POST['password'];
        $stmt=$conn->prepare('SELECT usersId,password,salt_1,salt_2,googleSecret,passwordDate FROM users where username=?'); //Preparing the SQL statememt to retrieve relevant User account based on username
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($usersID, $correctPassword, $salt_1, $salt_2, $googleSecret, $passwordDate);
        if ($stmt->fetch()){ //If results exist, run password check
            $password1=$salt_1.$password; //Prepend stored salt_1 to user input password
            $hash_1=hash('sha256', $password1); //First hash
            $password2=$hash_1.$salt_2; //Append stored salt_1 to first hash
            $hash_2=hash('sha256', $password2); //Second hash
            $encodedPassword=base64_encode($hash_2); //Encode second hash with base64
            if ($encodedPassword!=$correctPassword){ //If stored password in base64 is not equal to result of user's input password, redirect user back with a GET error message
                header('Location:login.php?error=invalid');
            }
            else{ //If not wrong password, run code
                initialiseSessionVar('usersID', $usersID); //Sets session variable 'userID' with value in $userID
                if($googleSecret!=NULL){ //Checks if googleSecret for the account is not null. If so, run 2FA verification
                    initialiseSessionVar('googleSecret', $googleSecret); //Sets session variable 'googleSecret' with value in $googleSecret
                    $auth2FAToken=hash('sha256', uniqid(rand(), TRUE)); //Generation of token
                    initialiseSessionVar('2FAToken', $auth2FAToken); //Sets session variable '2FAToken' with value in $auth2FAToken
                    initialiseSessionVar('2FATokenTime', time()); //Sets session variable '2FATokenTime' with value in time()
                    echo "<form action='loginUserValidate.php' id='submitForm' method='post'>";
                    echo "<input hidden name='2FAToken' value='$auth2FAToken'>";
                    echo "</form>";
                    echo "<script type='text/javascript'>document.getElementById('submitForm').submit();</script>"; //This code will auto submit the form with the 2FAToken to the validation of 2FA session
                    exit();
                }
                else{ //If googleSecret for the account is null, prep authToken for session timeout and validitily and redirect user to the storePage.php
                    $authToken=hash('sha256', uniqid(rand(), TRUE));
                    initialiseSessionVar('authToken', $authToken);
                    initialiseSessionVar('authTokenTime', time());
                    echo "<form action='storePage.php' id='submitForm' method='post'>";
                    echo "<input hidden name='authToken' value='$authToken'>";
                    echo "</form>";
                    echo "<script type='text/javascript'>document.getElementById('submitForm').submit();</script>"; //This code will auto submit the form with the authToken to the storePage, which will be used to validate session
                    exit();
                }
            }
        }
        else{ //If not records of user's username is found, redirect user back with a GET error message
            header('Location:login.php?error=invalid');
            exit();
        }
    }
    else{ //If username or password fields are empty, redirect user back with a GET error message
        header('Location:login.php?error=empty');
        exit();
    }
}
?>