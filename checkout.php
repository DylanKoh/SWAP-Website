<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
include 'connection.php'; //connect to db
require_once 'sessionInitialise.php'; //start session
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
      <script src="css/kitfontawesome9d4359df6d.js" crossorigin="anonymous"></script>
      <!--bootstrap-->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="css/bootstrapcdn452.css">
      <script src="css/ajaxgoogleapisajaxlibs351.js"></script>
      <script src="css/cdnjscloudflareajaxpopper1160.js"></script>
      <script src="css/bootstrapcdn452.js"></script>
      <link rel="stylesheet" type="text/css" href="css/checkout.css">
      <link rel="stylesheet" type="text/css" href="css/header.css">
   </head>
   <body>
      <!-- Header start -->
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
<!-- Header end -->
      <form class="checkout" Action="checkoutPost.php" method="post">
         <div class="checkout_title">Checkout</div>
         <div class="checkout_item">
            <label for="cardNumber" class="checkout_label">Credit Card Number</label>
            <input type="text" class="checkout_input" name="creditCard" id="creditCard" placeholder="Credit Card Number">
         </div>
         <div class="checkout_item">
            <label for="cardExpiry" class="checkout_label">Expiry Date</label>
            <input type="text" class="checkout_input" name="expiryDate" id="expiryDate" placeholder="Expiry Date (MM/YY)">
         </div>
         <div class="checkout_item">
            <label for="fourDigits" class="checkout_label">Last 4 Digits of Credit Card</label>
            <input type="text" class="checkout_input" name="fourDigits" id="fourDigits" placeholder="Last 4 Digits">
         </div>
         <div class="checkout_item">
            <label for="fourDigits" class="checkout_label">Pin</label>
            <input type="text" class="checkout_input" name="paymentPin" id="paymentPin" placeholder="6-Digit Numeric Pin">
         </div>
         <br>
            <input type="radio" name="action" id="yes" value="yes">Save Payment Information<br>
            <input type="radio" name="action" id="no" value="no">Don't Save Payment Information<br>
            <input type="radio" name="action" id="update" value="update">Update Payment Information<br>
            <input type="radio" name="action" id="delete" value="delete">Delete Payment Information<br>
            <input type="radio" name="action" id="existing" value="existing">Already have an existing card?<br>   
            <br>
            <button name='checkout_btn' class='checkout_btn' type='submit'>Checkout</button> 
         <?php 
  
        	?>
      </form>
      
   </body>
</html>