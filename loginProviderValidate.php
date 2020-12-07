<?php
session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE); //Sets session only visible in HTTPS
session_start(); //Starts session
require_once 'PHPGangsta/GoogleAuthenticator.php';
$ga=new PHPGangsta_GoogleAuthenticator();
echo $ga->createSecret();
?>