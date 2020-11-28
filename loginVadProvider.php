<?php
//Login to DB and to Providers table
if (isset($_POST["btnLogin"])){
    if (isset($_POST['username']) && isset($_POST['password'])){
        if (strlen($_POST['username']) > 0 && strlen($_POST['password']) > 0){
            echo "Hello Check";
            //Check if account exist
            //If exist, Log in or Validate 2FA if applicable
            //Else, error message
        }
        else{
            alertMessage("Please ensure fills are not empty!");
        }
    }
    else{
        alertMessage("Please ensure fills are filled!");
    }
}
function alertMessage($message){
    echo "<script type='text/javascript'>alert('$message');</script>";
}
?>