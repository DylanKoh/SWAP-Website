<?php
header("Content-Security-Policy: default-src 'self'");

include 'connection.php';
require_once 'sessionInitialise.php';
if(isset($_SESSION['usersID'])){
    $userFkid= $_SESSION['usersID'];
}

$stmt=$conn->prepare("DELETE FROM sales WHERE usersFkid=?");
$stmt->bind_param("i", $userFkid);
$res=$stmt->execute();
if($res) {
    echo "Deleted successfully!";
} else {
    echo "Unable to delete!";
}
?>