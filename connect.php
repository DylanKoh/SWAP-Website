<?php
$db_host="localhost";
$db_user="root";
$db_pwd="";
$db_name="swapcasestudy";

$con = mysqli_connect($db_host,$db_user,$db_pwd,$db_name); //connect to database
if (!$con){
    die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}
?>