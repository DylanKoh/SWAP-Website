<?php
session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE);
session_start();
require_once 'PHPGangsta/GoogleAuthenticator.php';
$ga=new PHPGangsta_GoogleAuthenticator();
echo $ga->createSecret();
?>