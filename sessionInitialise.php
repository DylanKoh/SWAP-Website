<?php
session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE); //Sets session only visible in HTTPS
session_start();
function initialiseSessionVar($variable, $value) {
    $_SESSION["$variable"]=$value;
    session_write_close();
}
function unsetVariable($variable){
    unset($_SESSION["$variable"]);
}
function destroySession() {
    session_destroy();
}
?>