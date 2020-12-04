<?php
session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE);
session_start();
require_once 'PHPGangsta/GoogleAuthenticator.php';
require_once 'connection.php';
if (!isset($_SESSION['userID'])){
    header('Location:login.php?error=notloggedin');
    exit();
}
$ga=new PHPGangsta_GoogleAuthenticator();
$userGSecret=$_SESSION['googleSecret'];

?>