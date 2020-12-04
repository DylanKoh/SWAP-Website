<html>
	<head>
	<!-- linking all relevant css  --> 
		<script src="https://kit.fontawesome.com/9d4359df6d.js"></script>
    	<link rel="stylesheet" type="text/css" href="header.css">
    	<link rel="stylesheet" type="text/css" href="storeIndiv.css">
    	<title></title>
		<?php
        //Connecting to Mysql Database:
        include 'connection.php'; 
        
        $servId= $_GET['id'];
        
        //Session info
        session_start();
        $provId = $_SESSION['provId'];
        $isProv = $_SESSION['isProvider'];
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
    		<?php 
    		$stmt= $conn->prepare("SELECT providers.providersId FROM services 
                                INNER JOIN providers ON services.providersFkid = providers.providersId 
                                WHERE services.servicesId= $servId;");
    		$res = $stmt->execute();
    		$stmt->store_result();
    		$stmt->bind_result($providerId);
    		while($stmt->fetch()){
        		if (($isProv == 'yes') && ($provId == $providerId)) {
        		    echo "<div class='sec-head'>";
        			echo "<div class='edit-but'>";
        			echo "<button class='serv-edit' id='edit-serv-but'>Edit/Delete</button>";
        			echo "</div></div>";
        		}
    		}
    		
    		?>
    		
    	<!-- Modal of website -->
    	<div id="servModal" class="modal">
			
			
    		<div class="modal-content">
        			<div class="modal-header">
                      <span class="close">&times;</span>
                      <h2>Edit your service</h2></div>
                    
                    <?php 
            		$stmt= $conn->prepare("SELECT `serviceName`, `serviceDesc`, `price` FROM `services` WHERE servicesId =$servId;");
            		$res = $stmt->execute();
            		$stmt->store_result();
            		$stmt->bind_result($serName, $serDesc, $serPrice);
            		while($stmt->fetch()){
            		    echo"<form action='indivCrud.php' method='post' onsubmit='setTimeout(function(){window.location.reload();},10);'>";
                        echo"<div class='modal-body'>";
                        echo"<a><label for='sName'><b>Service Name:</b></label> <br>";
                        echo"<input id='name' type='text' placeholder='Enter service name' name='serName' value='$serName' required> <br></a>";
                        echo"<a><label for='sDesc'><b>Service Description:</b></label> <br>";
                        echo"<textarea class='desc' placeholder='Enter your service description' name='serDesc' required>$serDesc</textarea> <br></a>";
                        echo"<a><label for='sPrice'><b>Price of your service: </b></label> <br>";
                        echo"<input id='price' type='text' class='price' placeholder='Enter price of service' name='serPrice' value='$serPrice' required></a><br>";
                        echo"<input name='servId' value=$servId type='hidden'>";
                        echo"<div class='but-serv'>";
                        echo"<button class='edit-ser' type='submit' name='updatebtn'>Edit</button>";
                        echo"<button class='dele-ser' type='submit' name='deletebtn'>Delete</button>";
                        echo"</div></div></form>";
            		}
                    ?>
                    </div></div>
    			
		<!-- Body of website -->
    		<div class='store-body'>
    			<div class='container'>
    				<div class='left-contain'>
    				<?php 
    				$stmt= $conn->prepare("SELECT services.serviceName, services.serviceDesc, services.price FROM services WHERE services.servicesId = $servId;");
    				$res = $stmt->execute();
    				$stmt->store_result();
    				$stmt->bind_result($serviceName, $serviceDesc, $price);
    				while($stmt->fetch()){
    				echo"<h1>$serviceName</h1><br>";
    				echo"<div class=image><img src='SwapImage/stock.png'></div>";
            		echo"<div class='serv-desc'>";
            		echo"<h2>About This Service:</h2>";
                	echo"<p id='price'>Price: $$price</p>";
                	echo"<p id='desc1'><b>Description</b></p>";
                	echo"<p id='desc2'>$serviceDesc</p>";
            		echo"</div>";
    				}
    				?>
    				
    				<div class='rev-contain'>
    				<h4>Leave a review</h4>
    				<textarea class='rev-text'></textarea>
    				<button>Post</button></div>
    				</div>
    				<div class='right-contain'>
    				
    				<?php 
    				$stmt= $conn->prepare("SELECT COUNT(reviews.rating), providers.username, AVG(reviews.rating) FROM services
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
        				
        				<?php 
        				$stmt= $conn->prepare("SELECT users.username, reviews.rating, reviews.comments, reviews.ratingDate FROM services
                                            INNER JOIN providers ON services.providersFkid = providers.providersId
                                            INNER JOIN orders ON services.servicesId = orders.servicesFkid
                                            INNER JOIN reviews ON orders.ordersId = reviews.ordersFkid
                                            INNER JOIN users ON orders.customerFkid = users.usersId
                                            WHERE services.servicesId=$servId");
        				$res = $stmt->execute();
        				$stmt->store_result();
        				$stmt->bind_result($revName, $revRate, $revComment, $revDate);
        				echo"<div class='review-header'><h2>Reviews</h2></div>";
        				echo"<div class='reviews'>";
        				while($stmt->fetch()){
        				    echo"<div class='review-card'>";
    						echo"<p class='rev-head'><b>$revName</b></p>";
    						echo"<p><i class='fas fa-star fa-sm'></i>$revRate</p>";
    						echo"<p class='desc'>$revComment</p>";
    						echo"</div>";
        				}
        				echo"</div>";
    					?>
    				</div>
    				
    			</div>
    		</div>
    		
    </body>

<style>
/* Posting a review section */
.rev-contain {
width: 70%;
height:160px;
border: 1px solid black;
padding: 5px 5px;
}
.rev-contain .rev-text{
width: 70%;
height: 40px;
}


/* Modal form */

.modal {
  display: none; 
  position: fixed; 
  z-index: 1; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0);
  background-color: rgba(0,0,0,0.4);
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 7% auto;
  border: 0px solid #888;
  height: 550px;
  width: 60%;
}

.modal-header {
  padding: 2px 36px;
  background-color: #003366;
  color: white;
  height: 60px;
}

.modal-header h2 {
margin:18px 0px;
}

.modal-header span {
margin: 8px 2px;
font-size: 40px;
}

.modal-body {
padding: 20px 36px;
height: 400px;
}

.modal-body .but-serv {
margin-top:10px;
width:80%;
text-align: center;
text-decoration: none;
}

.modal-body .edit-ser {
float:left;
background-color: white;
border: 1px solid black;
padding: 10px 30px;
font-size:18px;
border-radius: 5px;
}

.modal-body .dele-ser {
float:right;
background-color: red;
color:white;
border: 1px solid black;
padding: 10px 30px;
font-size:18px;
border-radius: 5px;
}

.modal-body label{
width:100%;
height: 20px;
font-size: 20px;
}


/* Full-width input fields */
.modal-body input[type=text]{
width: 80%;
padding: 12px 10px;
margin: 10px 0;
border: 1px solid #ccc;
}


.modal-body .desc {
padding: 12px 10px;
margin: 10px 0px;
height: 160px;
width: 80%;
}


/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover, .close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

</style>

<script type="text/javascript">
	
		//Obtain the modal
		var modal = document.getElementById("servModal");

        // Get the button that opens the modal
        var btn = document.getElementById("edit-serv-but");
        
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        
        // When the user clicks the button, open the modal 
        btn.onclick = function() {
          modal.style.display = "block";
        }
        
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = "none";
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
    }
    </script>
</html>