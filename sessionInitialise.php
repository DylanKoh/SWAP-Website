<?php
//This code file is written by Dylan Koh for easy and clean session initialisation and management
session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE); //Sets session only visible in HTTPS
session_start();
function initialiseSessionVar($variable, $value) {
    $_SESSION["$variable"]=$value;
}
function unsetVariable($variable){
    unset($_SESSION["$variable"]);
}
function destroySession() {
    session_unset();
    session_destroy();
}
?>