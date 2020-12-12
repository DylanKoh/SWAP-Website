<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'");
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
}
?>
<html>
	<head>
	<!-- linking all relevant css  --> 
    	<link rel="stylesheet" type="text/css" href="css/header.css">
    	<link rel="stylesheet" type="text/css" href="css/storeIndiv.css">
    	<link rel="stylesheet" type="text/css" href="css/storeIndiv1.css">
    	<!-- <script type="text/javascript" src="storeIndiv.js"></script> -->
    	<title>Search</title>
		<?php
        //Connecting to Mysql Database:
        include 'connection.php'; 
        
        $servId= $_GET['id'];
        
        //Session info
        if(isset($_SESSION['providersID'])){
            $provId = $_SESSION['providersID'];
        }
        else if(isset($_SESSION['usersID'])){
            $userId= $_SESSION['usersID']; 
        }
        
		?>
    	
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
    		<?php 
    		$stmt= $conn->prepare("SELECT providers.providersId FROM services 
                                INNER JOIN providers ON services.providersFkid = providers.providersId 
                                WHERE services.servicesId= $servId;");
    		$res = $stmt->execute();
    		$stmt->store_result();
    		$stmt->bind_result($providerId);
    		while($stmt->fetch()){
    		    if ((isset($_SESSION['providersID'])) && ($provId == $providerId)) {
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
            		    echo"<form action='indivCrud.php' method='post'>";
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
    		
    		<!-- Service information sql -->
    		
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
    				
    				<!-- Post a review section! -->
    				<div class='rev-contain'>
    				<h4>Leave a review</h4>
    				<?php 
    				echo"<form action='reviewCrud.php' method='post'>";
    				$reviewToken=hash('sha256', uniqid(rand(), TRUE));     
    				initialiseSessionVar('reviewToken', $reviewToken);     
    				initialiseSessionVar('reviewTokenTime', time());    
    				echo "<input hidden name='reviewToken' value='$reviewToken'>";
    				echo"<textarea class='rev-text' name='revComments'></textarea>";
    				echo"<input name='revRating' type='number' placeholder='Rate' min='1' max='5'><br>";
    				echo"<button class='post-review' name='reviewbtn' type='submit'>Post</button>";
    				echo"</form>";
    				?>
    				</div>
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
            				echo"<p>Rating: <img src='SwapImage/star-icon-16.png'>
                            <img src='SwapImage/star-icon-16.png'>
                            <img src='SwapImage/star-icon-16.png'>
                            <img src='SwapImage/star-icon-16.png'>
                            <img src='SwapImage/star-icon-16.png'> ($count)</p>";
            				echo"<div class='buttons'>";
                    		echo"<button class='chat'>Chat</button>";
                    		echo"<form class='makeoffer' method='post' action='userOffer.php'>";
                    		$offerToken=hash('sha256', uniqid(rand(), TRUE));     
                    		initialiseSessionVar('offerToken', $offerToken);     
                    		initialiseSessionVar('offerTokenTime', time());     
                    		echo "<input hidden name='offerToken' value='$offerToken'>";
                    		echo"<input type='hidden' name='serviceIDS' value='$servId'></input>";
                    		echo"<button class='offer'>Make an offer</button>";
                    		echo"</form>";
                			echo"</div></div>";
        				}
        				?>
        				
        				<!-- Review cards -->
        				
        				<?php 
        				$stmt= $conn->prepare("SELECT users.username, reviews.rating, reviews.comments, reviews.ratingDate, reviews.ordersFkid, reviews.usersFkid, reviews.reviewsId FROM services
                                            INNER JOIN providers ON services.providersFkid = providers.providersId
                                            INNER JOIN orders ON services.servicesId = orders.servicesFkid
                                            INNER JOIN reviews ON orders.ordersId = reviews.ordersFkid
                                            INNER JOIN users ON reviews.usersFkid = users.usersId
                                            WHERE services.servicesId=$servId");
        				$res = $stmt->execute();
        				$stmt->store_result();
        				$stmt->bind_result($revName, $revRate, $revComment, $revDate, $ordId, $usId, $revId);
        				echo"<div class='review-header'><h2>Reviews</h2></div>";
        				echo"<div class='reviews'>";
        				while($stmt->fetch()){
        				    echo"<div id='revcard$revId' class='review-card'>";
        				    if(isset($_SESSION['usersID']) && ($userId==$usId)){
        				        echo"<button class='myRevBtn' id='myRevBtn' onclick=saveRevIds($revId)>Edit</button>";
        				    }
    						echo"<p class='rev-head'><b>$revName</b></p>";
    						echo"<p>$revRate <img src='SwapImage/star-icon-16.png'></p>";
    						echo"<p class='desc'>$revComment</p>";
    						echo"<p class='daterev'>Date posted: $revDate</p>";
    						echo"<p hidden class='revHideId' value=$revId>$revId</p>";
    						echo"</div>";
        				}
        				echo"</div>";
    					?>
    				</div>
    				
    			</div>
    			
    			<!-- Review modal contents-->
    			<div id="reviewModal" class="revModal">
			
            		<div class="revmodal-content">
                		<?php 
                			echo"<div class='revmodal-header'>";
                			echo"<span class='closerev'>&times;</span>";
                            echo"<h2>Edit your review</h2></div>";
                            echo"<form action='reviewCrud1.php' method='post'>";
                            echo"<div class='revmodal-body'><a> ";
                            echo"<label for='rCom'><b>Review comments:</b></label> <br>";
                            echo"<textarea id='comments' class='comments' placeholder='Enter your review' name='commentUpdate' required></textarea> <br>";
                            echo"</a><a>";
                            echo"<label for='rRate'><b>Rating: </b></label>";
                            echo"<input id='revRates' class='rate-box' type='number' placeholder='Rate' min='1' max='5' name='ratingUpdate' required>";
                            echo"</a><br>";
                            echo"<div class='but-rev'>";
                            echo"<button class='edit-rev' type='submit' name='revUpdateBtn'>Edit</button>";
                            echo"<button class='dele-rev' type='submit' name='revDeleteBtn'>Delete</button>";
                        	echo"<input id='revIds' name='reviewId' type='hidden'>";
                        	echo"<a></a>";
                            echo"</div></div></form>";  
                		?>
                </div></div>
    		</div>
    </body>

<?php 
$stmt= $conn->prepare("SELECT providers.providersId FROM services 
                    INNER JOIN providers ON services.providersFkid = providers.providersId
                    WHERE services.servicesId= $servId;");
    		$res = $stmt->execute();
    		$stmt->store_result();
    		$stmt->bind_result($providerId);
    		while($stmt->fetch()){
    if(isset($_SESSION['providersID']) && $provId==$providerId){
            echo"<script type='text/javascript'>";
            echo"var modal = document.getElementById('servModal')
            ";
            echo"var btn = document.getElementById('edit-serv-but')
            ";
            echo"var span = document.getElementsByClassName('close')[0]
            ";
            echo"btn.onclick = function() {
            ";
            echo"modal.style.display = 'block';}
            ";
            echo"span.onclick = function() {
            ";
            echo"modal.style.display = 'none';}
            ";
            echo"window.onclick = function(event) {
            ";
            echo"if (event.target == modal) {
            ";
            echo"modal.style.display = 'none';} }</script>";
    }
}
?>     
 
 <script type="text/javascript">
 
 		//Obtain the modal
		var revmodal = document.getElementById("reviewModal");

        // Get the button that opens the modal
        var btnrev = document.getElementById("myRevBtn");
        
        
        // Get the <span> element that closes the modal
        var spanrev = document.getElementsByClassName("closerev")[0];
       
        
        // When the user clicks on <span> (x), close the modal
        spanrev.onclick = function() {
          revmodal.style.display = "none";
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == revmodal) {
            revmodal.style.display = "none";
              }
        }
        
        function saveRevIds(revId) {
        alert(revId);
        var carddiv = document.getElementById("revcard"+revId);
        alert(carddiv.innerHTML);
        var modalComments = document.getElementById("comments");
      	modalComments.innerHTML = carddiv.childNodes[3].innerHTML;
      	
      	var reviewIds = document.getElementById("revIds");
      	reviewIds.value = carddiv.childNodes[5].innerHTML;
      	
      	var reviewRate = document.getElementById("revRates");
      	ratingNum = carddiv.childNodes[2].innerHTML;
      	reviewRate.value = parseInt(ratingNum, 10);
      	
        revmodal.style.display = "block";
        
        }
</script>

</html>