<html>
    <head>
    	<script src="https://kit.fontawesome.com/9d4359df6d.js"></script>
    	<link rel="stylesheet" type="text/css" href="header.css">
    	<link rel="stylesheet" type="text/css" href="storepage.css">
    	<title>Pentesters for Hire</title>
    </head>

	<body>
    	<?php
        //Connecting to Mysql Database:
        include 'connection.php'; 
        
        //Sessions
        
        session_set_cookie_params(0, '/', 'localhost', TRUE, TRUE);
        session_start();
        $_SESSION['provId'] ='1';
        $_SESSION['isProvider'] ='yes';
        $_SESSION['orderId']='1';
        $_SESSION['userId']='3';
        $isProv = $_SESSION['isProvider'];
        
        ?>
        
		<div class="webhead">
			<a id="left">Hire a Pentester</a>	
    			<div class='searchfield'>
        			<form class='searchform' method='post' action='storeSearch.php'> 
            			<input type="text" id="nav-search" name='search' placeholder="Search for Services">
            			<button id="nav-sea-but" type="submit">Search</button>
            		</form>
            	</div>	
    		<div class="webhead-right">
        		<a href="index.php">Home</a>
        		<a href="">Explore</a>
        		<a href="about.php">About</a>
        		<a class="nav-but" href="login.php">Settings</a>
			</div>
		</div>
		
		
		<!-- Store body codes -->
		<div class="store-body">
		
		<div class="store-header1">
		<h1>Pentesting Services</h1>
    		<?php 
        		if ($isProv == 'yes') {
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
    		echo"<a href='storeIndiv.php?id=$servicesId'><div class='container'>";
            echo"<div class='box-view'><div class='sell-info'>";      
    		echo"<p id='title' style='font-size:22px;'><b>". $serviceName . "</b></p>";
    		echo"<p style='font-size:14px;'> Provider: ".$username."</p>";
    		echo "<p id='sell-price'>Price: $". $price. "</p>";
    		echo"<p id='rating'>5 <i class='fas fa-star fa-sm'></i> <a>(No. of Reviews)</a></p>";
    		echo"</div> </div> </div></a>";
    		
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
    <style>
	.searchfield {
	   margin-left: 10%;
	   width: 400px;
	   height: 100%;
	   display: inline-block;
	}
	</style>
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