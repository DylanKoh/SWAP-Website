<html>
	<head>
	<!-- linking all relevant css  --> 
		<script src="https://kit.fontawesome.com/9d4359df6d.js"></script>
    	<link rel="stylesheet" type="text/css" href="header.css">
    	<title></title>
		<?php
        //Connecting to Mysql Database:
        include 'connect.php'; 
        
        $servId= $_GET['id'];
        
		?>
    	
    </head>
    <body>
    	<!-- Web header for the website -->
        	<div class="webhead">
    			<a id="left">Hire a Pentester</a>	
        			<input type="text" id="nav-search" placeholder="Search for Pentester">
        			<button id="nav-sea-but" type="submit">Search</button>
        		<div class="webhead-right">
            		<a href="index.php">Home</a>
            		<a href="">Explore</a>
            		<a href="about.php">About</a>
            		<a class="nav-but" href="login.php">Login</a>
    			</div>
    		</div>
    		
		<!-- Body of website -->
    		<div class='store-body'>
    			<div class='container'>
    				<?php 
    				$stmt= $con->prepare("SELECT services.serviceName, services.serviceDesc, services.price FROM services WHERE services.servicesId = $servId;");
    				$res = $stmt->execute();
    				$stmt->store_result();
    				$stmt->bind_result($serviceName, $serviceDesc, $price);
    				while($stmt->fetch()){
    				    
    				echo"<div class='left-contain'>";
    				echo"<h1>$serviceName</h1><br>";
    				echo"<div class=image><img src='SwapImage/stock.png'></div>";
            		echo"<div class='serv-desc'>";
            		echo"<h2>About This Service:</h2>";
                	echo"<p id='price'>Price: $$price</p>";
                	echo"<p id='desc1'><b>Description</b></p>";
                	echo"<p id='desc2'>$serviceDesc</p>";
            		echo"</div></div>";
    				}
    				?>
    				<div class='right-contain'>
    				
    				<?php 
    				$stmt= $con->prepare("SELECT COUNT(reviews.rating), providers.username, AVG(reviews.rating) FROM services
                                        INNER JOIN providers ON services.providersFkid = providers.providersId
                                        INNER JOIN orders ON services.servicesId = orders.servicesFkid
                                        INNER JOIN reviews ON orders.ordersId = reviews.ordersFkid
                                        WHERE services.servicesId= $servId");
    				$res = $stmt->execute();
    				$stmt->store_result();
    				$stmt->bind_result($count, $username, $rating);
        				while($stmt->fetch()){
        					echo"<div class='prov-info'>";
        					echo"<h3>Provider: <a>$username</a></h3>";
            				echo"<p>Rating: <i class='fas fa-star fa-sm'></i>";
            				echo"<i class='fas fa-star fa-sm'></i>";
            				echo"<i class='fas fa-star fa-sm'></i>";
            				echo"<i class='fas fa-star fa-sm'></i>";
            				echo"<i class='fas fa-star fa-sm'></i> ($count)</p>";
            				echo"<div class='buttons'>";
                    		echo"<button class='chat'>Chat</button>";
                    		echo"<button class='offer'>Make an offer</button>";
                			echo"</div></div>";
        				}
        				?>
        				
        				<div class='review-header'>
        					<h2>Reviews</h2>
        				</div>
        				<div class='reviews'>
    						<div class='review-card'>
    							<p class='rev-head'><b>Bob</b></p>
    							<p><i class='fas fa-star fa-sm'></i>5</p>
    							<p class='desc'> The provider was fast and easy to understand, he was really helpful.</p>
    							
    						</div>
    					</div>
    					
    				</div>
    				
    			</div>
    		</div>
    		
    </body>

<style>
body {
background:	#F0F8FF;
height: 800px;
}

h1, h2, h3, h4, p{
padding: 0px;
margin: 0px;
}

.store-body {
width:70%;
height: 100%;
background: white;
margin: auto;
margin-top: 50px;
display:flex;
}

.container {
width: 100%;
height: 100%;
padding: 20px 40px;
display:flex;
}

/* left container */

.left-contain {
width: 50%;
height: 100%;
display: inline-block;
}

.left-contain h1, h2 {
display: inline-block;
}

.left-contain .image {
display: inline-block;
border: 0.1px solid black;
margin: 20px 0px;
height: 300px;
width: 80%;
object-fit: cover;
overflow: hidden;
}

.left-contain .image img {
background-size: contain;
background-repeat: no-repeat;
height: 100%;
width: 100%;
}

.left-contain p {
font-size: 20px;
margin: 5px 0px;
}

.left-contain #price {
width: 80%;
border-bottom: 1px solid black;
padding: 5px 0px;
}

.left-contain #desc1 {
font-size: 20px;
margin-top: 10px;
}

.left-contain #desc2 {
width: 80%;
}

.left-contain h1 {
font-size: 40px;
}

.left-contain .serv-desc h2 {
margin:5px 0px;
}

/* right container*/

.right-contain {
margin-left: auto;
margin-top: 68px;
width: 47%;
height: 80%;
display: inline-block;
border: 0px solid black;
}

.right-contain .prov-info {
padding: 20px 20px;
border: 1px solid black;

}

.right-contain .prov-info p {
margin:5px 0px;
font-size: 16px
}

/* css for buttons */
.buttons {
margin-top: 20px;
}

.chat {
background-color: white;
border: 1.5px solid black;
padding: 10px 20px;
text-align: center;
text-decoration: none;
font-size:18px;
border-radius: 5px;
transition-duration: 0.7s;
}

.offer{
background-color: white;
border: 1.5px solid black;
padding: 10px 20px;
margin-left: 10px;
text-align: center;
text-decoration: none;
font-size:18px;
border-radius: 5px;
transition-duration: 0.7s;
}

.chat:hover {
background-color: #e7e7e7;
box-shadow: 0 2px 4px 0 rgba(0,0,0,0.24), 0 5px 10px 0 rgba(0,0,0,0.19);
}

.offer:hover {
background-color: #e7e7e7;
box-shadow: 0 2px 4px 0 rgba(0,0,0,0.24), 0 5px 10px 0 rgba(0,0,0,0.19);
}

/* css for Reviews section */
.right-contain .review-header {
margin-left: 40%;
padding: 15px 0px;
}


.right-contain .reviews {
width: 100%;
height: 70%;
border: 1px solid black;
}

.right-contain .reviews .review-card {
padding: 15px 20px;
border-bottom: 1px solid black;
}

.right-contain .reviews .review-card a {
padding-top: 10px;
}

.right-contain .reviews .review-card .rev-head {
font-size: 20px;
padding: 5px 0px;
}
.right-contain .reviews .review-card .desc {
padding: 5px 0px;
}



</style>

</html>