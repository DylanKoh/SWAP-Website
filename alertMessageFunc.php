<?php
header("Content-Security-Policy: script-src 'https://localhost/SWAPWebsite'; "); //Starts Content Security Policy to protect any remote code execution
function promptMessage($message){
    echo "<script type='text/javascript'>alert('$message');</script>";
}
?>