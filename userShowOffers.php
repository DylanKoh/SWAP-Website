<?php

require_once 'sessionInitialise.php';
//check connection to MySql database
include 'connection.php';


$orderId = $_SESSION['orderId'];
echo $orderId

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
                				echo"<button>Return to store page</button></div>";
            				echo"</div>";
                        }
                			?>
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

.order-body{
width:60%;
height:90%;
background-color:white;
margin: 50px auto;
}

.order-body .header{
text-align:center;
padding: 30px 0;
}

.order-body .content{
margin:30px auto;
width: 70%;
height: 70%;
border: 1px solid black;
}

.cont-border {
margin: 40px 20px;
}
.order-body .content .cont-fields{
width: 90%;
height:40px;
margin: 2% auto;
font-size: 22px;
}

.cont-com{
width: 90%;
height:80px;
margin: 2% auto;
font-size: 22px;
}


.cont-fields input, textarea{
font-size: 18px;
float:right;
}

.cont-com textarea{
height: 100%;
width: 225px;
}

</style>