<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php';
if (!isset($_SESSION['usersID'])){ //Check if token for creating account is not valid
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


$orderId = $_SESSION['orderId'];

?>

<html>
    <head>
    	<!-- linking all relevant css  -->
        <link rel="stylesheet" type="text/css" href="css/header.css">
        <link rel="stylesheet" type="text/css" href="css/userShowOffer.css">
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
        		
        		<div class='order-body'>
        			<div class='header'><h1>Order information:</h1></div>
            			<div class='content'>
            			<?php 
            			$stmt= $conn->prepare("SELECT providers.username, orders.isAccepted, orders.orderDate, orders.comments, orders.isCompleted FROM orders
                                                INNER JOIN services ON services.servicesId = orders.servicesFkid
                                                INNER JOIN providers ON providers.providersId = services.providersFkid
                                                WHERE ordersId =$orderId");
                        $res = $stmt->execute();
                        $stmt->store_result();
                        $stmt->bind_result($provName, $isAcc, $date, $comments, $isComp);
                        
                    while($stmt->fetch()){ //execute query
                        //echo 'Name: '.$provName . ' Accept: '.$isAcc . ' Date: '.$date . ' Comments: '.$comments . ' Complete: '.$isComp;
                            if($isAcc == '0'){
                               $isAccept = 'Pending';
                            }else if($isAcc =='1'){
                                $isAccept = 'Yes';
                            }
                            if($isComp == '0'){
                                $isComplete = 'Pending';
                            }else if($isComp =='1'){
                                $isComplete = 'Yes';
                            }
            			    echo"<div class='cont-border'>";
                			    echo"<div class='cont-fields'>";
                				echo"<label>Order Id:</label>";
                				echo"<input type='number' name='orderId' value=$orderId readonly></div>";
                				echo"<div class='cont-fields'>";
                				echo"<label>Providers Name:</label>";
                				echo"<input type='text' name='provName' value=$provName readonly></div>";
                				echo"<div class='cont-fields' >";
                				echo"<label>Order Accepted?</label>";
                				echo"<input type='text' name='isAcc' value=$isAccept readonly></div>";
                				echo"<div class='cont-com' >";
                				echo"<label>Comments:</label>";
                				echo"<textarea name='comments' readonly>$comments</textarea></div>";
                				echo"<div class='cont-fields'>";
                				echo"<label>Order Date</label>";
                				echo"<input type='text' name='date' value=$date readonly></div>";
                				echo"<div class='cont-fields'>";
                				echo"<label>Is it completed?</label>";
                				echo"<input type='text' name='isComp' value=$isComplete readonly></div>";
                				echo"<div class='cont-fields'>";
                				echo"<form class='check-out' method='post' action='checkout.php'>";
                				echo"<input hidden name='authToken' value='$authToken'>";
                				echo"<button type='submit'>Checkout</button></a></div>";
            				echo"</form></div>";
                        }
                			?>
                	</div>
        	</div>
    </body>
</html>