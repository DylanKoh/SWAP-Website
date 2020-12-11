<?php
include 'connection.php';
session_start();
$userID= $_SESSION['usersID'];
$serviceID = $_POST['serviceIDS'];
$comments = $_POST['orderComments'];
$status2 = $_POST['completedStatus'];

$query= $conn->prepare("INSERT INTO `orders` (`customerFkid`,`comments`,'servicesFkid','isCompleted') VALUES (?,?,?,?)");
$query->bind_param('isis', $userID, $comments, $serviceID, $status2); //bind the parameters
if ($query->execute()){ //execute query
    echo "<br>Successfully added!";
}else{
    echo "<br>Adding unsuccessful";
}
?>