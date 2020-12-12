<?php 
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
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
   </head>
   <body>
      <?php 
         //connect to db
         include 'connection.php';
         require_once 'sessionInitialise.php';
         ?>
      <!-- Header start -->
      <header>
         <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
            <div class="container-fluid">
               <a href="index.html" href="index.html"><img src="images/websitelogo.png" alt="Website Logo" style="width: 80px; height: 80px;"></a>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarResponsive">
                  <ul class="navbar-nav ml-auto">
                     <li class="nav-item active">
                        <a class="nav-link">Home</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link">Post an Offer</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="storePage.php">Explore</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="createAccount.php">Sign Up</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                     </li>
                  </ul>
               </div>
               <div class="dropdown" id="dropdown" style="visibility: hidden">
                  <a class="dropbtn" id="loginAccess" style="color:#ddd;">Welcome User</a>
               </div>
            </div>
         </nav>
      </header>
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
//         		if ($isUser == 'yes') {
//         		   echo"<button name='checkout_btn' class='checkout_btn' type='submit'>Checkout</button>";
//         		} else {
//         		    echo "<br> Unable to checkout as you are not logged in!";
//         		}
//         	?>
      </form>
      
   </body>
</html>