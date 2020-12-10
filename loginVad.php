<?php
include 'connection.php'; //Include login connection to database
if (isset($_POST["btnLogin"])){
    if (!empty($_POST['username']) && !empty($_POST['password'])){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $stmt=$conn->prepare('SELECT usersId,password,salt_1,salt_2,googleSecret,passwordDate FROM users where username=?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($usersID, $correctPassword, $salt_1, $salt_2, $googleSecret, $passwordDate);
        if ($stmt->fetch()){
            $password1=$salt_1.$password;
            $hash_1=hash('sha256', $password1);
            $password2=$hash_1.$salt_2;
            $hash_2=hash('sha256', $password2);
            $encodedPassword=base64_encode($hash_2);
            if ($encodedPassword!=$correctPassword){
                header('Location:login.php?error=invalid');
            }
            else{
                session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE); //Sets session only visible in HTTPS
                session_start(); //Starts session
                $_SESSION['usersID']=$usersID;
                if($googleSecret!=NULL){
                    $_SESSION['googleSecret']=$googleSecret;
                    $auth2FAToken=hash('sha256', uniqid(rand(), TRUE));
                    $_SESSION['2FAToken']=$auth2FAToken;
                    $_SESSION['2FATokenTime']=time();
                    echo "<form action='loginUserValidate.php' method='post'>";
                    echo "<input hidden name='2FAToken' value='$auth2FAToken'>";
                    echo "</form>";
                    header('Location:loginUserValidate.php');
                    exit();
                }
                else{
                    $authToken=hash('sha256', uniqid(rand(), TRUE));
                    $_SESSION['authToken']=$authToken;
                    $_SESSION['authTokenTime']=time();
                    echo "<form action='storePage.php' method='post'>";
                    echo "<input hidden name='authToken' value='$authToken'>";
                    echo "</form>";
                    header('Location:storePage.php');
                    exit();
                }
            }
        }
        else{
            header('Location:login.php?error=invalid');
            exit();
        }
    }
    else{
        header('Location:login.php?error=empty');
        exit();
    }
}
?>