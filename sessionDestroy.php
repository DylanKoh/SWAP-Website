<?php
function unsetVariable($variable){
    unset($_SESSION["$variable"]);
}
function destryoSession() {
    session_destroy();
}
?>