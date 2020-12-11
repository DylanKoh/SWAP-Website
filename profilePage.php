<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php'; //Initialise Session
echo isset($_POST['authToken']);
if(!isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){
    destroySession();
    header('Location:login.php?error=notloggedin');
    exit();
}
else{
    if (isset($_POST['authToken']) && $_POST['authToken'] == $_SESSION['authToken']){
        $sessionAge=time()-$_SESSION['authTokenTime'];
        if ($sessionAge > 1200){
            destroySession();
            if (isset($_SESSION['providersID'])){
                header('Location:providerLogin.php?error=sessionExpired');
                exit();
            }
            else{
                header('Location:login.php?error=sessionExpired');
                exit();
            }
        }
    }
    else{
        destroySession();
        if (isset($_SESSION['providersID'])){
            header('Location:providerLogin.php?error=invalidToken');
            exit();
        }
        else{
            header('Location:login.php?error=invalidToken');
            exit();
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
	<div class='profilehead'><h1 id='header'>Profile</h1></div>
		<form class='prof-form'>
			<div class='userdata'>
				<div class='fields'>
				<label>Username:</label>
				<input type='text' value=''></input><br></div>
				<div class='fields'>
				<label>Email:</label>
				<input type='text'></input><br></div>
				<div class='fields'>
				<label>Name:</label>	
				<input type='text'></input><br></div>
				<!-- Button input division -->
				<div class='buttons-div'>
    				<div class='fields'><label>Configure 2FA:</label>
    				<button id='conf-but'>Configure 2FA</button><br></div>
    				<div class='last-buttons'>
    				<button id='edit-but'>Edit data</button>
    				<button id='reset-but'>Reset password</button></div>
				</div>
				
				
				
			
    		</div>
    	</form>
	</div>

</body>


</html>