<?php

require_once 'sessionInitialise.php';
//check connection to MySql database
include 'connection.php';

//Session variables
$userID = $_SESSION['usersID'];
$servID = $_POST['serviceIDS'];


?>

<html>
	<head>
        <!-- linking all relevant css  -->
        <link rel="stylesheet" type="text/css" href="css/header.css">
    	<title></title>
	</head>
	<body>
		<!-- Web header for the website -->
        	<div class="webhead">
    			<a id="left">Hire a Pentester</a>	
    			<div class='searchfield'>
        			<form class='searchform' method='post' action='storeSearch.php'> 
        				<input hidden name='authToken' value="<?php echo $_POST['authToken']?>">
            			<input type="text" id="nav-search" name='search' placeholder="Search for Services">
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

		<div class='providerbody'>
			<div class='providerhead'><h1>Accepting/Completing Offers</h1></div>
    			<div class='cover-contain'>
        			<div class='accept-container'>
        			<h2>Available orders:</h2>
        			</div>
        			
        			
    				<div class='completing-container'>
    				<h2>Pending orders to be completed:</h2>
					</div>
				</div>
		</div>
	</body>
</html>
<style>
.providerbody {
width:60%;
height:80%;
background-color:white;
margin:auto;
}

.providerbody .providerhead h1 {
text-align: center;
padding: 10px 0px;
font-size: 40px;
border-bottom: 1px solid black;
}

.cover-contain{
display:flex;
height:100%;
}

.accept-container {
display: inline-block;
width: 50%;
height: 100%;
margin-top:2%;
border:4px solid black;
}

.accept-container h2{
border-bottom: 4px solid black;
padding: 10px 0px;
padding-left: 120px;
}


.completing-container{
margin-top: 2%;
margin-left: 0.5%;
width:50%;
height:100%;
border:4px solid black;
}

.completing-container h2{
border-bottom: 4px solid black;
padding: 10px 0px;
padding-left: 50px;
}
</style>