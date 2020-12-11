<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php';
if(!isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){
    
}
else{
    
}
?>
<html>
    <head>
    	<link rel="stylesheet" type="text/css" href="css/storeSearch.css">
    	<link rel="stylesheet" type="text/css" href="css/header.css">
    	<title>Pentesters for Hire</title>
        <?php
            //Connecting to Mysql Database:
            include 'connection.php'; 
            
            
            //Data variables
            $searchResult = $_POST['search'];
            $procResults = htmlentities($searchResult);
        ?>
    
    </head>
    <body>
        <div class="webhead">
    			<a id="left">Hire a Pentester</a>	
        			<div class='searchfield'>
            			<form class='searchform' method='post' action='storeSearch.php'>
                			<input type="text" id="nav-search" name='search' placeholder="Search for Pentester">
                			<button id="nav-sea-but" type="submit">Search</button>
                		</form>
                	</div>	
        		<div class="webhead-right">
            		<form action="storePage.php" method="post">
                		<input hidden name='authToken' value="<?php echo $_POST['authToken']?>">
                		<input type="submit" class="nav-but" value="Explore">
            		</form>
            		<form action="profilePage.php" method="post">
                		<input hidden name='authToken' value="<?php echo $_POST['authToken']?>">
                		<input type="submit" class="nav-but" value="Settings">
            		</form>
        		<a href="logout.php">Logout</a>
        		
    			</div>
    		</div>
    		
    		<div class="store-header1">
    		
    		</div>
    		
    		<div class='store-card'>
    		<?php 
    		//Retrieving Service data from database:
    		$stmt= $conn->prepare("SELECT services.servicesId, services.serviceName, services.serviceDesc, services.providersFkid, services.price, providers.username FROM services 
                                  INNER JOIN providers ON services.providersFkid = providers.providersId WHERE services.serviceName LIKE '%$procResults%'");
    		$res = $stmt->execute();
    		$stmt->store_result();
    		$stmt->bind_result($servicesId, $serviceName, $serviceDesc, $providersFkid, $price, $username);
    		
    		//Creation of tables with data:
    		echo "<div class='sell-column'>";
    		while($stmt->fetch()){
        		echo"<a href='storeIndiv.php?id=$servicesId'><div class='container'>";
                echo"<div class='box-view'><div class='sell-info'>";      
        		echo"<p id='title'><b>". $serviceName . "</b></p>";
        		echo"<p id='provName'> Provider: ".$username."</p>";
        		echo "<p id='sell-price'>Price: $". $price. "</p>";
        		echo"<p id='rating'>5 <img src='SwapImage/star-icon-16.png'> <a>(No. of Reviews)</a></p>";
        		echo"</div> </div> </div></a>";
        		
    		}
    		echo "</div>";
    	
    		?>
    		</div>
    
    </body>
</html>