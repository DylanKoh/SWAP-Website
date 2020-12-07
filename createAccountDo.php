<?php
require 'connection.php';
if (isset($_POST["btnCreate"])){
    $fullName=$_POST['fullname'];
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $rePassword=$_POST['rePassword'];
    if (empty($fullName) || empty($username) || empty($email) || empty($password) || empty($rePassword) || !isset($_POST['rbType'])){
        header("Location:createAccount.php?error=emptyfields&fullname=$fullName&username=$username&email=$email");
        exit();
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location:createAccount.php?error=notEmail&fullname=$fullName&username=$username");
        exit();
    }
    elseif (!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[\#\?\!\@\$\%\^\&\*\-\.\,\/]).{8,}$/', $password)){
        header("Location:createAccount.php?error=passwordWeak&fullname=$fullName&username=$username&email=$email");
        exit();
    }
    elseif ($password!=$rePassword){
        header("Location:createAccount.php?error=passwordNoMatch&fullname=$fullName&username=$username&email=$email");
        exit();
    }
    else{
        
    }
    
}

?>