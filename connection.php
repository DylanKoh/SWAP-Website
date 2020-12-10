<?php
header("Content-Security-Policy: script-src 'https://localhost/SWAPWebsite'; "); //Starts Content Security Policy to protect any remote code execution
include_once 'alertMessageFunc.php'; 
$conn=mysqli_connect('localhost', 'root', '', 'swapcasestudy');
if (!$conn){
    die(promptMessage('Unable to connect to database! Please try again later!'));
}
?>