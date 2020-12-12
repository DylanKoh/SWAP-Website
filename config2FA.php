<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php'; //Initialise Session
require_once 'validateToken.php';
require 'connection.php';
if(!isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){ //If user does not have any ID in their session
    destroySession();
    header('Location:login.php?error=notloggedin');
    exit();
}
else{ //If an ID of sorts is assigned in the session variables
    if (!verifyToken('authToken', 1200)){ //If token is not valid
        destroySession();
        if (isset($_SESSION['providersID'])){
            header('Location:providerLogin.php?error=errToken');
            exit();
        }
        else{
            header('Location:login.php?error=errToken');
            exit();
        }
    }
    else{
        $authToken=$_POST['authToken'];
        $configure2FAToken=hash('sha256', uniqid(rand(),TRUE));
        initialiseSessionVar('configure2FAToken', $configure2FAToken);
        initialiseSessionVar('configure2FATokenTime', time());
    }
}
?>


<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/header.css">
		<link rel="stylesheet" type="text/css" href="css/profilepage.css">

	</head>
<body>
<!-- Header -->
	<div class="webhead">
			<a id="left">Hire a Pentester</a>	
    			<div class='searchfield'>
        			<form class='searchform' method='post' action='storeSearch.php'> 
            			<input type="text" id="nav-search" name='search' placeholder="Search for Services">
            			<input hidden name="authToken" value="<?php echo $_POST['authToken']?>">
            			<button id="nav-sea-but" type="submit">Search</button>
            		</form>
            	</div>	
    		<div class="webhead-right">
    			<form class='navbar-button' action="storePage.php" method="post">
                		<input hidden name='authToken' value="<?php echo $_POST['authToken']?>">
                		<input type="submit" class="nav-but" value="Explore">
            		</form>
        		<form class='navbar-button' action="profilePage.php" method="post">
        		<input hidden name='authToken' value="<?php echo $authToken?>">
        		<input type="submit" class="nav-but" value="Settings">
        		</form>
        		<a href="logout.php">Logout</a>
        		
			</div>
		</div>

<!-- Body of codes -->
	<div class='profilebody'>
	<div class='profilehead'><h1 id='header'>Configure 2FA</h1></div>
		<form class='prof-form' action='config2FADo.php' method="post">
			<div class='userdata'>
				<?php 
				if (isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){
				    $stmt=$conn->prepare('SELECT googleSecret FROM users where usersId=?');
				    $stmt->bind_param('s', $_SESSION['usersID']);
				    $stmt->execute();
				    $stmt->bind_result($googleSecret);
				    if ($stmt->fetch()){
				        if ($googleSecret==NULL){
				            echo "<input type='submit' value='Set up 2FA' name='btnSetUp'>";
				        }
				        elseif ($googleSecret!=NULL){
				            echo "<input type='submit' value='Reset 2FA Configurations' name='btnReset'>";
				            echo "<input type='submit' value='Delete 2FA Configurations' name='btnDelete'>";
				        }
				    }
				    else{
				        echo "There was an issue retrieving 2FA configurations!";
				    }
				}
				elseif (!isset($_SESSION['usersID']) && isset($_SESSION['providersID'])){
				    $stmt=$conn->prepare('SELECT googleSecret FROM providers where providersId=?');
				    $stmt->bind_param('s', $_SESSION['providersID']);
				    $stmt->execute();
				    $stmt->bind_result($googleSecret);
				    if ($stmt->fetch()){
				        if ($googleSecret==NULL){
				            echo "<input type='submit' value='Set up 2FA' name='btnSetUp'>";
				        }
				        elseif ($googleSecret!=NULL){
				            echo "<input type='submit' value='Reset 2FA Configurations' name='btnReset'>";
				            echo "<input type='submit' value='Delete 2FA Configurations' name='btnDelete'>";
				        }
				    }
				    else{
				        echo "There was an issue retrieving 2FA configurations!";
				    }
				}
				?>
				<input hidden name='authToken' value="<?php echo $authToken; ?>">
				<input hidden name='resetPasswordToken' value="<?php echo $configure2FAToken; ?>">
    		</div>
    		</form>
    		</div>
		
<?php 
//Runs relevant prompt message to show user error
require_once 'alertMessageFunc.php';

?>
</body>


</html>