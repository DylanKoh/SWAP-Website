<?php
header("Content-Security-Policy: default-src 'self';"); //Starts Content Security Policy to protect any remote code execution
include 'connection.php';
session_start();
$_SESSION['userID'] =3;
$_SESSION['isUser'] ='yes';
$isUser = $_SESSION['isUser'];
$userFkid = $_SESSION['userID'];

$stmt=$conn->prepare("DELETE FROM sales WHERE usersFkid=?");
$stmt->bind_param("i", $_SESSION['userID']);
$res=$stmt->execute();
if($res) {
    echo "Deleted successfully!";
} else {
    echo "Unable to delete!";
}
?>