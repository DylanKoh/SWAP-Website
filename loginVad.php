<?php
include 'connection.php'; //Include login connection to database
include_once 'alertMessageFunc.php'; 
if (isset($_POST["btnLogin"])){
    if (isset($_POST['username']) && isset($_POST['password'])){
        if (!empty($_POST['username']) && !empty($_POST['password'])){
            $username=$_POST['username'];
            $password=$_POST['password'];
            $stmt=$conn->prepare('SELECT usersId,password,salt_1,salt_2,googleSecret,passwordDate * FROM users where username=?');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($userID, $correctPassword, $salt_1, $salt_2, $googleSecret, $passwordDate);
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
                    
                }
            }
        }
        else{
            promptMessage("Please ensure fills are not empty!");
        }
    }
    else{
        promptMessage("Please ensure fills are filled!");
    }
}
?>