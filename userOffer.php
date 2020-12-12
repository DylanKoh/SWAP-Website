<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php';
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
$userID = $_SESSION['usersID'];
$servID = htmlentities($_POST['serviceIDS']);


?>

<html>
	<head>
        <!-- linking all relevant css  -->
        <link rel="stylesheet" type="text/css" href="css/header.css">
        <link rel="stylesheet" type="text/css" href="css/userOffer.css">
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
        				echo"<input hidden name='authToken' value='$authToken'>";
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
	

</html>