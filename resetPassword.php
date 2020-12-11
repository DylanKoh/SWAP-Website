<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php'; //Initialise Session
require_once 'validateToken.php';
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
        		<input hidden name='authToken' value="<?php echo $_POST['authToken']?>">
        		<input type="submit" class="nav-but" value="Settings">
        		</form>
        		<a href="logout.php">Logout</a>
        		
			</div>
		</div>

<!-- Body of codes -->
	<div class='profilebody'>
	<div class='profilehead'><h1 id='header'>Reset Password</h1></div>
		<form class='prof-form' action='resetPasswordDo.php' method="post">
			<div class='userdata'>
				<div class='fields'>
				<label>Old Password:</label>
				<input type="password" name='oldPassword'><br></div>
				<div class='fields'>
				<label>Email:</label>
				<input type="password" name='newPassword'><br></div>
				<div class='fields'>
				<label>Full Name:</label>	
				<input type="password" name='reNewPassword' ></input><br></div>
				<input hidden name='authToken' value="<?php echo $authToken; ?>">
				<!-- Button input division -->
				<div class='buttons-div'>
    				<div class='last-buttons'>
    				<input type="submit" value="Reset Password" name="btnReset" >
				</div>
    		</div>
    		</div>
    	</form>
	
	</div>
		
<?php 
//Runs relevant prompt message to show user error
require_once 'alertMessageFunc.php';
if (isset($_GET['error']) && $_GET['error'] == 'emptyfields'){
    promptMessage('Please fill in all of the fields!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'notEmail'){
    promptMessage('Please enter a valid email!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'illegalCharacters'){
    promptMessage('Please ensure fullname has only alphabetical characters! Username allows only alphabets, numbers and "_?!" characters!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'emailTaken'){
    promptMessage('Email has already been taken! Please try using another email!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'usernameTaken'){
    promptMessage('Username has already been taken! Please try using another Username!');
}
?>
</body>


</html>