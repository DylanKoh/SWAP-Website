<?php

require_once 'sessionInitialise.php';
//check connection to MySql database
include 'connection.php';

//Session variables
$provID = $_SESSION['providersID'];
$servID = $_POST['serviceIDS'];
$provID='1';
$servID='1';
$notComp = '0';
$Completed = '1';

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
            			
            			<?php 
            			$stmt= $conn->prepare("SELECT orders.ordersId, users.username, orders.comments FROM services
                                            INNER JOIN orders ON orders.servicesFkid = services.servicesId
                                            INNER JOIN providers ON providers.providersId = services.providersFkid
                                            INNER JOIN users ON users.usersId = orders.customerFkid
                                            WHERE services.servicesId=$servID AND orders.isAccepted=$notComp AND providers.providersId=$provID");
                        $res = $stmt->execute();
                        $stmt->store_result();
                        $stmt->bind_result($orderId, $username, $comments);
                        while($stmt->fetch()){
                            echo"<div class='avail-card'>";
                            echo"<form class='avail-order' method='post' action='providerOffersCrud.php'>";
                    			echo"<h3 class='cus-name'>Customer: $username</h3><br>";
                    			echo"<textarea name='order-comment' Readonly>$comments</textarea>";
                    			echo"<input hidden name='orderId' value=$orderId>";
                    			echo"<button type='submit' name='acc-offer'>Accept Order</button>";
                    			echo"</form></div>";
                        }
                        ?>
        			</div>
        			
        			
    				<div class='completing-container'>
    				<h2>Pending orders to be completed:</h2>
    				
    				<?php 
            			$stmt= $conn->prepare("SELECT orders.ordersId, users.username, orders.comments FROM services
                                            INNER JOIN orders ON orders.servicesFkid = services.servicesId
                                            INNER JOIN providers ON providers.providersId = services.providersFkid
                                            INNER JOIN users ON users.usersId = orders.customerFkid
                                            WHERE services.servicesId=$servID AND orders.isAccepted=$Completed AND orders.isCompleted=$notComp AND providers.providersId=$provID");
                        $res = $stmt->execute();
                        $stmt->store_result();
                        $stmt->bind_result($orderId, $username, $comments);
                        while($stmt->fetch()){
                            echo"<div class='pend-card'>";
    						echo"<form class='pend-order' method='post' action='providerOffersCrud.php'>";
    						    echo"<h3 class='cus-name'>Customer: $username</h3><br>";
    							echo"<textarea name='order-comment' Readonly>$comments</textarea>";
    							echo"<input hidden name='orderId' value=$orderId>";
    							echo"<button type='submit' name='comp-offer'>Complete Order</button>";
    							echo"</form></div>";
                        }
                        ?>
					</div>
				</div>
		</div>
	</body>
</html>
<style>
body {
background:	#F0F8FF;
height: 800px;
}

h1, h2, h3, h4, p{
padding: 0px;
margin: 0px;
}

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
height: 80%;
margin-top:2%;
border:4px solid black;
}

.accept-container h2{
border-bottom: 4px solid black;
padding: 20px 0px;
text-align:center;
}

.accept-container .avail-card{
width: 100%;
height: 120px;
border-bottom: 1px solid black;
}
.accept-container .avail-order{
height: 100%;
}

.accept-container .avail-order .cus-name{
display: inline-block;
margin-left:2%;
margin-top:1%;
}

.accept-container .avail-order textarea {
height: 60%;
width: 50%;
margin-left:2%;
margin-top:1%;
}

.accept-container .avail-order button{
float: right;
margin-right:4%;
background-color: white;
border: 1px solid black;
padding: 5px 15px;
font-size:16px;
border-radius: 5px;
margin-top: 25px;
}



.completing-container{
margin-top: 2%;
margin-left: 0.5%;
width:50%;
height:80%;
border:4px solid black;
}

.completing-container h2{
border-bottom: 4px solid black;
padding: 20px 0px;
text-align:center;
}

.completing-container .pend-card{
width: 100%;
height: 120px;
border-bottom: 1px solid black;
}
.completing-container .pend-order{
height: 100%;
}

.completing-container .pend-order .cus-name{
display: inline-block;
margin-left:2%;
margin-top:1%;
}

.completing-container .pend-order textarea {
height: 60%;
width: 50%;
margin-left:2%;
margin-top:1%;
}

.completing-container .pend-order button{
float: right;
margin-right:4%;
background-color: white;
border: 1px solid black;
padding: 5px 15px;
font-size:16px;
border-radius: 5px;
margin-top: 25px;
}

</style>