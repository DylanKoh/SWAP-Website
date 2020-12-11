<?php

require_once 'sessionInitialise.php';
//check connection to MySql database
include 'connection.php';


$_SESSION['usersID'] ='1';
$userID = $_SESSION['usersID'];


//$servID = $_POST['serviceIDS'];
$servID = '1';


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

		<div class='orderbody'>
			<div class='orderhead'><h1>Post an offer</h1></div>
    			<div class='cover-contain'>
        			<div class='serv-container'>
        			<?php $stmt= $conn->prepare("SELECT providers.username, COUNT(reviews.rating), services.serviceName, services.serviceDesc, services.price FROM services
                                            INNER JOIN providers ON services.providersFkid = providers.providersId
                                            INNER JOIN orders ON services.servicesId = orders.servicesFkid
                                            INNER JOIN reviews ON orders.ordersId = reviews.ordersFkid
                                            WHERE services.servicesId=$servID");
                		$res = $stmt->execute();
                		$stmt->store_result();
                		$stmt->bind_result($username, $number, $serName, $serDesc, $serPrice);
                		while($stmt->fetch()){
        			
            			echo"<div class='serv-info'>";
            			echo"<h1>$serName</h1>";
            			echo"<p>Price: $serPrice</p>";
            			echo"<p>Service Description:</p>";
            			echo"<p>$serDesc</p></div>";
            			echo"<div class='prov-info'>";
            			echo"<h2>Provider: $username</h2>";
            			echo"<p>Rating: ($number)</p></div>";
                		}
            		?>	
        			</div>
        			
    				<div class='orderPost'>
    				<h3>Comments:</h3>
    				<?php 
        				echo"<form class='submitOrder' action='userOfferCrud.php' method='post'>";
        				echo"<textarea class='com-box' name='orderCom' placeholder='Input any information you want to inform the service provider'></textarea>";
        				echo"<input type='hidden' name='servId' value='$servID'><br>";
        				echo"<div class='check-field'>";
            				echo"<label>Are you sure you want to post order?</label>";
            				echo"<input type='checkbox' name='checkbox'>";
        				echo"</div>";
        				echo"<button class='order-but' type='submit'>Submit Order</button>";
        				echo"</form>";
    				?>
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

.orderbody {
width:60%;
height:80%;
background-color:white;
margin: 50px auto;
}

.orderbody .orderhead h1 {
text-align: center;
padding: 10px 0px;
font-size: 40px;
border-bottom: 1px solid black;
}
.cover-contain{
display:flex;
height:100%;
}

.serv-container {
display: inline-block;
width: 40%;
height: 60%;
margin-top:2%;
margin-left:2%;
}

/* service information box */
.serv-info{
border: 1px solid black;
height:50%;
}

.serv-info h1{
margin-left:2%;
margin-top: 2%;
}

.serv-info p{
font-size:18px;
margin-left:2%;
}

/* provider box information */
.prov-info{
margin-top: 5%;
border: 1px solid black;
height:30%;
}

.prov-info h2{
margin-left:2%;
margin-top: 2%;
}

.prov-info p{
font-size:18px;
margin-left:2%;
}


.orderPost{
margin-top: 2%;
margin-left:5%;
width:50%;
height:400px;
border:1px solid black;
}

.orderPost h3{
margin-left:3%;
margin-top: 2%;
}

.orderPost .com-box{
margin-left:3%;
margin-top: 1%;
height:40%;
width:70%;
font-size:16px;
}
.orderPost .check-field{
margin-left:3%;
margin-top: 2%;
}

.orderPost .order-but{
margin-left:3%;
margin-top: 5%;
}




</style>
</html>