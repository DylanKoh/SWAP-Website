<?php
//This code file is written by Dylan Koh for easy and clean token validation
require_once 'sessionInitialise.php';
function verifyToken($tokenName, $ttl) {
    if (isset($_POST["$tokenName"]) && $_POST["$tokenName"] == $_SESSION["$tokenName"]){
        $sessionAge=time()-$_SESSION["$tokenName".'Time'];
        if ($sessionAge < $ttl){
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
    else{
        return FALSE;
    }
}
?>
