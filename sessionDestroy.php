<?php

function unsetVariable($variable){
    session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE); //Sets session only visible in HTTPS
    session_start();
    unset($_SESSION["$variable"]);
}
function destryoSession() {
    session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE); //Sets session only visible in HTTPS
    session_start();
    session_destroy();
}
?>