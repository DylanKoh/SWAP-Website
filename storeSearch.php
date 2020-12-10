<?php
header("Content-Security-Policy: default-src 'self'");
header("X-Frame-Options: DENY");
?>
<html>
    <head>
    	<script src="css/kitfontawesome9d4359df6d.js"></script>
    	<link rel="stylesheet" type="text/css" href="css/header.css">
    	<title>Pentesters for Hire</title>
        <?php
            //Connecting to Mysql Database:
            include 'connection.php'; 
            
            
            //Sessions
            session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE);
            session_start();
            
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
            		<a href="index.php">Home</a>
            		<a href="storePage.php">Explore</a>
            		<a href="about.php">About</a>
            		<a class="nav-but" href="profilePage.php">Settings</a>
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
        		echo"<p id='title' style='font-size:22px;'><b>". $serviceName . "</b></p>";
        		echo"<p style='font-size:14px;'> Provider: ".$username."</p>";
        		echo "<p id='sell-price'>Price: $". $price. "</p>";
        		echo"<p id='rating'>5 <i class='fas fa-star fa-sm'></i> <a>(No. of Reviews)</a></p>";
        		echo"</div> </div> </div></a>";
        		
    		}
    		echo "</div>";
    	
    		?>
    		</div>
    
    </body>
    <style>
.searchfield {
margin-left: 10%;
display: inline-block;
height: 100%;
width: 400px;
}
    
h1, h2, h3, h4, p{
margin:0;
padding:0;
}
    
/*store-card */
.store-card {
padding-top:3%;
width: 80%;
margin: auto;
height: 1200px;
}

/* Section 1 content */
.sell-column{
padding-top:20px;
display: flex;
max-width: 100%;
justify-content: center;
flex-wrap:wrap;
height: 60%;
float: left;
}

.sell-column a {
text-decoration: none;
color:black;
}

.box-view {
position: relative;
width: 260px;
height: 320px;
background-color:#fdfdf8;
overflow: hidden;
box-shadow: 0 2px 4px 0 rgba(0,0,0,0.4);
border: 1px solid black;
margin-left: 20px;
}
.box-view .sell-info {
position: absolute;
width: 100%;
height: 35%;
background-color:lightgray;
bottom: 0;
}
.box-view .sell-info h3 {
padding: 1% 6%;
}
.box-view .sell-info p {
padding: 0 6%;
}
.box-view #rating{
font-size:16px;
}
.box-view i {
color:#FFD700;
}
.box-view a{
font-size:16px;
}
.box-view .sell-info #title {
font-size:18px;
padding-top: 5px;
padding-bottom: 5px;
}
</style>
</html>