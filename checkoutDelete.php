<?php
include 'connection.php';
session_start();
$_SESSION['userID'] ='3';
$_SESSION['isUser'] ='yes';
$isUser = $_SESSION['isUser'];
$userFkid = $_SESSION['userID'];

$stmt=$conn->prepare("DELETE FROM sales WHERE userFkid=?");
$stmt->bind_param("i", $userFkid);
$res=$stmt->execute();
if($res) {
    echo "Deleted successfully!";
} else {
    echo "Unable to delete!";
}
?>