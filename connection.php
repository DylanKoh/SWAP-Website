<?php
include_once 'alertMessageFunc.php'; 
$conn=mysqli_connect('localhost', 'root', '', 'swapcasestudy');
if (!$conn){
    die(promptMessage('Unable to connect to database! Please try again later!'));
}
?>