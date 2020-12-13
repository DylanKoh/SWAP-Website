<?php
header("Content-Security-Policy: default-src 'self'");

include 'connection.php'; //connect to db
require_once 'sessionInitialise.php'; //start session

if (isset($_SESSION['usersID'])) {
    $userFkid = $_SESSION['usersID'];
}

// if(!isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){
//     destroySession();
//     header('Location:login.php?error=notloggedin');
//     exit();
// }
// else{
//     if (isset($_POST['authToken']) && $_POST['authToken'] == $_SESSION['authToken']){
//         $sessionAge=time()-$_SESSION['authTokenTime'];
//         if ($sessionAge > 1200){
//             if (isset($_SESSION['providersID'])){
//                 destroySession();
//                 header('Location:providerLogin.php?error=sessionExpired');
//                 exit();
//             }
//             else{
//                 destroySession();
//                 header('Location:login.php?error=sessionExpired');
//                 exit();
//             }
//         }
//     }
//     else{
//         if (isset($_SESSION['providersID'])){
//             destroySession();
//             header('Location:providerLogin.php?error=invalidToken');
//             exit();
//         }
//         else{
//             destroySession();
//             header('Location:login.php?error=invalidToken');
//             exit();
//         }
        
//     }
// }

$stmt=$conn->prepare("DELETE FROM sales WHERE usersFkid=?"); //sql query statement to delete the users payment information
$stmt->bind_param("i", $userFkid);
$res=$stmt->execute(); //executes sql query statement
if($res) {
    echo "Deleted successfully!";
} else {
    echo "Unable to delete!";
}
?>