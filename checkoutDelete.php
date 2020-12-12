<?php
header("Content-Security-Policy: default-src 'self'");

include 'connection.php'; //connect to db
require_once 'sessionInitialise.php'; //start session
if(isset($_SESSION['usersID'])){
    $userFkid= $_SESSION['usersID'];
}

$stmt=$conn->prepare("DELETE FROM sales WHERE usersFkid=?"); //deletes based on userFkid
$stmt->bind_param("i", $userFkid);
$res=$stmt->execute();
if($res) {
    echo "Deleted successfully!";
} else {
    echo "Unable to delete!";
}
?>