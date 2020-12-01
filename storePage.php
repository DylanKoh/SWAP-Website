<html>
    <head>
    	<script src="https://kit.fontawesome.com/9d4359df6d.js"></script>
    	<link rel="stylesheet" type="text/css" href="storepage.css">
    	<title>Pentesters for Hire</title>
    </head>

	<body>
    	<?php
        //Connecting to Mysql Database:
        include 'connect.php'; 
        ?>
        
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
		<!-- Store body codes -->
		<div class="store-body">
		
		<div class="store-header1">
		<h1>Top Reviewed Sellers</h1>
		<button class='post-but' id='mod-button'>Post A Service</button>
		</div>
		
		<?php 
		
		//Retrieving Service data from database:
		$stmt= $con->prepare("SELECT services.servicesId, services.serviceName, services.serviceDesc, services.providersFkid, services.price, providers.username FROM services 
                              INNER JOIN providers ON services.providersFkid = providers.providersId");
		$res = $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($servicesId, $serviceName, $serviceDesc, $providersFkid, $price, $username);
		
		//Creation of tables with data:
		echo "<div class='sell-column'>";
		while($stmt->fetch()){
    		echo"<a href='storePage.php?id=$servicesId'><div class='container'>";
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
                    
                    <form action='StorePost.php' method='post' onSubmit='window.location.reload()'>
                        <div class="modal-body">
                            <a onclick= 'store()'>
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
                           	<button class='post-ser'>Post</button>
                        </div>
                    </form>
                    
                    <div class="modal-footer">
                      <h3></h3>
        		</div>
    		</div>
		</div>
	</div>
    </body>
    
    <style>
    .sell-column a {
    text-decoration: none;
    color:black;
    }
    
    .sell-column {
    height: 60%;
    }
    
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
      height: 600px;
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
    
    .modal-body .post-ser {
    background-color: white;
    border: 1px solid black;
    margin-top:10px;
    padding: 10px 30px;
    text-align: center;
    text-decoration: none;
    font-size:18px;
    border-radius: 5px;
    }
    
    .modal-body label{
    width:100%;
    height: 20px;
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
    
    .modal-body label {
    font-size: 20px;
    }
    
    /*.modal-footer {
      padding: 2px 16px;
      background-color: #5cb85c;
      color: white;
    }
    */
    
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