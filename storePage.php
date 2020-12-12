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
?>
<html>
    <head>
    	<link rel="stylesheet" type="text/css" href="css/header.css">
    	<link rel="stylesheet" type="text/css" href="css/storepage.css">
    	<title>Pentesters for Hire</title>
    </head>

	<body>
    	<?php
        //Connecting to Mysql Database:
        include 'connection.php'; 
        
        //Sessions
        $_SESSION['orderId']='4';
        
        //echo $_SESSION['usersID'];
        //echo $_SESSION['providersID'];
        
        ?>
        
		<div class="webhead">
			<a id="left">Hire a Pentester</a>	
    			<div class='searchfield'>
        			<form class='searchform' method='post' action='storeSearch.php'> 
            			<input type="text" id="nav-search" name='search' placeholder="Search for Services">
            			<input hidden name="authToken" value="<?php echo $_POST['authToken']?>">
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
		
		
		<!-- Store body codes -->
		<div class="store-body">
		
    		<!-- Store body Header -->
    		<div class="store-header1">
    		<h1>Pentesting Services</h1>
        		<?php //Checks is provider is logged in, if he is logged in the Post a service button will appear.
            		if (isset($_SESSION['providersID'])) {
            		   echo"<button class='post-but' id='mod-button'>Post A Service</button>";
            		}
            	?>
    		</div>
    		
    	<?php 
		
		//Retrieving Service data from database:
		$stmt= $conn->prepare("SELECT services.servicesId, services.serviceName, services.serviceDesc, services.providersFkid, services.price, providers.username FROM services 
                              INNER JOIN providers ON services.providersFkid = providers.providersId");
		$res = $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($servicesId, $serviceName, $serviceDesc, $providersFkid, $price, $username);
		
		//Creation of tables with data:
		echo "<div class='sell-column'>";
		while($stmt->fetch()){
		    echo"<form id='$servicesId' action='storeIndiv.php?id=$servicesId' method='post'>"; 
    		echo"<div class='container'><button class='invis-but'>";
    		echo"<input hidden name='authToken' value='$authToken'>";
            echo"<div class='box-view'><div class='sell-info'>";      
    		echo"<p id='title'><b>". $serviceName . "</b></p>";
    		echo"<p id='provName'> Provider: ".$username."</p>";
    		echo "<p id='sell-price'>Price: $". $price. "</p>";
    		echo"<p id='rating'>5 <img src='SwapImage/star-icon-16.png'> <a>(No. of Reviews)</a></p>";
    		echo"</div> </div> </button></div></form> ";
    		
    		
		}
		echo "</div>";
	
		?>
		
		<!-- Modal for Posting data -->
		
		<div id="postModal" class="modal">
			
    		<div class="modal-content">
        			<div class="modal-header">
                      <span class="close">&times;</span>
                      <h2>Post A Service</h2>
                    </div>
                    
                    <form action='StorePost.php' method='post'>
                        <div class="modal-body">
                        <a>
                              <label for='sName'><b>Service Name:</b></label> <br>
                              <input id='name' type='text' placeholder='Enter service name' name='serName' required> <br>
                            </a>
                            
                            <a> 
                              <label for='sDesc'><b>Service Description:</b></label> <br>
                              <textarea class='desc' placeholder='Enter your service description' name='serDesc' required></textarea> <br>
                            </a>  
                              
                            <a>
                              <label for='sPrice'><b>Price of your service: </b></label> <br>
                              <input id='price' type='text' class='price' placeholder='Enter price of service' name='serPrice' required>
                           	</a><br>
                           	<button class='post-ser' type="submit" >Post</button>
        </div></form></div></div>
	</div>
	
    </body>
    
	<script type="text/javascript">
	
		//Obtain the modal
		var modal = document.getElementById("postModal");

        // Get the button that opens the modal
        var btn = document.getElementById("mod-button");
        
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