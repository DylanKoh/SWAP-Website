<?php
include 'connection.php'; //Include login connection to database
include_once 'alertMessageFunc.php';
if (isset($_POST["btnLogin"])){
    if (!empty($_POST['username']) && !empty($_POST['password'])){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $stmt=$conn->prepare('SELECT providersID,password,salt_1,salt_2,googleSecret,passwordDate FROM providers where username=?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($providersID, $correctPassword, $salt_1, $salt_2, $googleSecret, $passwordDate);
        if ($stmt->fetch()){
            $password1=$salt_1.$password;
            $hash_1=hash('sha256', $password1);
            $password2=$hash_1.$salt_2;
            $hash_2=hash('sha256', $password2);
            $encodedPassword=base64_encode($hash_2);
            if ($encodedPassword!=$correctPassword){
                header('Location:providerLogin.php?error=invalid');
            }
            else{
                session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE); //Sets session only visible in HTTPS
                session_start(); //Starts session
                $_SESSION['providersID']=$providersID;
                $_SESSION['isProvider']=TRUE; //Use this only if decided combined store page
                if($googleSecret!=NULL){
                    $_SESSION['googleSecret']=$googleSecret;
                    header('Location:loginProviderValidate.php');
                    exit();
                }
                else{
                    header('Location:storePage.php');
                    exit();
                }
            }
        }
        else{
            header('Location:providerLogin.php?error=invalid');
            exit();
        }
    }
    else{
        header('Location:providerLogin.php?error=empty');
        exit();
    }
}
?>