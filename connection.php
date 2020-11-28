<?php
include 'alertMessage.php';
$conn=mysqli_connect('localhost', 'root', '', 'swapcasestudy');
if (!$conn){
    die(alertMessage('Unable to connect to database! Please try again later!'));
}
?>