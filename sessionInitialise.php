<?php
session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE); //Sets session only visible in HTTPS
session_start();
function initialiseSession($variable, $value) {
    $_SESSION["$variable"]=$value;
    session_write_close();
}
?>