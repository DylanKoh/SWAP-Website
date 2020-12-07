<?php
require 'connection.php';
if (isset($_POST["btnCreate"])){
    $fullName=$_POST['fullname'];
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $rePassword=$_POST['rePassword'];
    if (empty($fullName) || empty($username) || empty($email) || empty($password) || empty($rePassword) || isset($_POST['rbType'])){
        if (@$_POST['cb2FA'] == NULL){
            echo  'hi';
        }
    }
    
}

?>