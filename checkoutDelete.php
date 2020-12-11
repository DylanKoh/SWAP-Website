<?php
header("Content-Security-Policy: default-src 'self'");

include 'connection.php';
require_once 'sessionInitialise.php';
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