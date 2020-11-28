<?php
include 'connection.php';
include_once 'alertMessageFunc.php'; 
//Login to DB and to Users table
if (isset($_POST["btnLogin"])){
    if (isset($_POST['username']) && isset($_POST['password'])){
        if (!empty($_POST['username']) && !empty($_POST['password'])){
            echo "Hello Check";
            //Check if account exist
            //If exist, Log in or Validate 2FA if applicable
            //Else, error message
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