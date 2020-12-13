<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php';
if (!isset($_SESSION['providersID'])){ //Check if token for creating account is not valid
    header('HTTP/1.0 403 Forbidden');
    exit();
}
if(!isset($_SESSION['usersID']) && !isset($_SESSION['providersID'])){
    destroySession();
    header('Location:login.php?error=notloggedin');
    exit();
}
else{
    if (isset($_POST['authToken']) && $_POST['authToken'] == $_SESSION['authToken']){
        $sessionAge=time()-$_SESSION['authTokenTime'];
        if ($sessionAge > 1200){
            if (isset($_SESSION['providersID'])){
                destroySession();
                header('Location:providerLogin.php?error=sessionExpired');
                exit();
            }
            else{
                destroySession();
                header('Location:login.php?error=sessionExpired');
                exit();
            }
        }
    }
    else{
        if (isset($_SESSION['providersID'])){
            destroySession();
            header('Location:providerLogin.php?error=invalidToken');
            exit();
        }
        else{
            destroySession();
            header('Location:login.php?error=invalidToken');
            exit();
        }
        
    }
    $authToken = $_POST['authToken'];
}

//check connection to MySql database
include 'connection.php';

//Session variables
$provID = $_SESSION['providersID'];
$servID = htmlentities($_POST['serviceIDS']);
$notComp = '0';
$Completed = '1';

?>

<html>
	<head>
        <!-- linking all relevant css  -->
        <link rel="stylesheet" type="text/css" href="css/header.css">
        <link rel="stylesheet" type="text/css" href="css/providerOffer.css">
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
                    			echo"<input hidden name='authToken' value='$authToken'>";
                    			echo"<input type='hidden' name='servIDS' value='$servID'>";
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
    							echo"<input hidden name='authToken' value='$authToken'>";
    							echo"<input type='hidden' name='servIDS' value='$servID'>";
    							echo"<button type='submit' name='comp-offer'>Complete Order</button>";
    							echo"</form></div>";
                        }
                        ?>
					</div>
				</div>
		</div>
	</body>
</html>