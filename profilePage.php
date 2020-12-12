<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php'; //Initialise Session
require 'connection.php';
require_once 'validateToken.php';
if(!isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){ //If user does not have any ID in their session
    destroySession();
    header('Location:login.php?error=notloggedin');
    exit();
}
else{ //If an ID of sorts is assigned in the session variables
    if (!verifyToken('authToken', 1200)){ //If token is not valid
        destroySession();
        if (isset($_SESSION['providersID'])){ //If initial user is a Provider
            header('Location:providerLogin.php?error=errToken');
            exit();
        }
        else{ //If initial user is a Customer
            header('Location:login.php?error=errToken');
            exit();
        }
    }
    else{ //If token is valid
        $authToken=$_POST['authToken'];
        $editProfileToken=hash('sha256', uniqid(rand(), TRUE));
        initialiseSessionVar('editProfileToken',$editProfileToken);
        initialiseSessionVar('editProfileTokenTime',time());
        if (isset($_SESSION['providersID'])){ //If user is a Provider;
            $stmt=$conn->prepare('SELECT username,email,name FROM providers where providersID=?');
            $stmt->bind_param('i', $_SESSION['providersID']);
            $stmt->execute();
            $stmt->bind_result($username,$email,$name);
            if ($stmt->fetch()){ //If user data retrieval was successful
                echo "Successfully retrieved user data!";
            }
            else{ //If user data retrieval was not successful
                echo "There was an error retrieving user data!";
            }
        }
        elseif (isset($_SESSION['usersID'])){ //If user is a Customer
            $stmt=$conn->prepare('SELECT username,email,name FROM users where usersID=?');
            $stmt->bind_param('i', $_SESSION['usersID']);
            $stmt->execute();
            $stmt->bind_result($username,$email,$name);
            if ($stmt->fetch()){ //If user data retrieval was successful
                echo "Successfully retrieved user data!";
            }
            else{ //If user data retrieval was not successful
                echo "There was an error retrieving user data!";
            }
        }
        if (isset($_SESSION['deleteAccountToken'])){ //Checks if unnecessary token for deleting account exist
            unsetVariable('deleteAccountToken');
            unsetVariable('deleteAccountTokenTime');
        }
        
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
            			<input hidden name="authToken" value="<?php echo $authToken?>">
            			<button id="nav-sea-but" type="submit">Search</button>
            		</form>
            	</div>	
    		<div class="webhead-right">
    			<form class='navbar-button' action="storePage.php" method="post">
                		<input hidden name='authToken' value="<?php echo $authToken?>">
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
	<div class='profilehead'><h1 id='header'>Profile</h1></div>
		<form class='prof-form' action='editDo.php' method="post">
			<div class='userdata'>
				<div class='fields'>
				<label>Username:</label>
				<input type='text' name='username' value="<?php echo htmlspecialchars(strip_tags($username))?>"></input><br></div>
				<div class='fields'>
				<label>Email:</label>
				<input type='text' name='email' value="<?php echo htmlspecialchars(strip_tags($email))?>"></input><br></div>
				<div class='fields'>
				<label>Full Name:</label>	
				<input type='text' name='fullname' value="<?php echo htmlspecialchars(strip_tags($name))?>"></input><br></div>
				<input hidden name='authToken' value="<?php echo $authToken; ?>">
        		<input hidden name='editProfileToken' value="<?php echo $editProfileToken; ?>">
				<!-- Button input division -->
				<div class='buttons-div'>
    				<div class='last-buttons'>
    				<input type="submit" value="Edit data" name="btnEdit" >
				</div>
    		</div>
    		</div>
    	</form>
	<div class='fields'><label>Configure 2FA:</label>
	<form action="config2FA.php" method="post">
	<input hidden name='authToken' value="<?php echo $authToken; ?>">
	<button id='conf-but'>Configure 2FA</button><br>
	</form>
	<?php 
	if (isset($_SESSION['usersID'])){
	    echo "<form action='deleteUserAccount.php' method='post'>";
	    echo "<input hidden name='authToken' value='$authToken'>";
	    echo "<button id='conf-but'>Delete User Account</button><br>";
	    echo "</form>";
	}
	
	?>
	
	<form action="resetPassword.php" method="post">
	<input hidden name='authToken' value="<?php echo $authToken; ?>">	
	<button id='reset-but'>Reset password</button>
	</form>
	</div>
		
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
elseif (isset($_GET['error']) && $_GET['error'] == 'errToken'){
    promptMessage('Token is unvalid or has timed out! Please retry editing your account!');
}
?>
</body>


</html>