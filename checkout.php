<html>
   <head>
      <script src="https://kit.fontawesome.com/9d4359df6d.js" crossorigin="anonymous"></script>
      <!--bootstrap-->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js%22%3E"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
   </head>
   <style>
      /* Header start */
      .navbar {
      padding: .8rem;
      }
      .navbar-nav li {
      padding-right: 20px;
      }
      .nav-link {
      font-size: 1.1em !important;
      }
      .form-inline input {
      height: 34px;
      width: 100px;
      }
      /* Header end */
      @import url("https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,600i&display=swap");
      .checkout * {
      box-sizing: border-box;
      line-height: 1.5;
      }
      .checkout_title {
      font-size: 2em;
      font-weight: 600;
      padding: 10px;
      }
      .checkout_item {
      display: flex;
      flex-direction: column;
      padding:10px;
      }
      .checkout_item > * {
      align-self: flex-start;
      }
      .checkout_label {
      font-weight: 600;
      padding: 10px 0;
      }
      .checkout_input {
      -webkit-appearance: none;
      width: 100%;
      max-width: 425px;
      }
      .checkout_input:focus {
      background: #ffffff;
      }
      .checkout_input::placeholder {
      color: #bbbbbb;
      }
      textarea.checkout_input {
      resize: none;
      min-height: 200px;
      }
      .checkout_btn {
      font-family: "Source Sans Pro", sans-serif;
      font-weight: 600;
      font-size: 1.1em;
      padding: 10px 16px;
      margin: 10px 0;
      color: #ffffff;
      background: #14b64a;
      border: 2px solid #0fa942;
      border-radius: 5px;
      cursor: pointer;
      outline: none;
      }
      .checkout_btn:active {
      background: #0fa942;
      }
      /* footer start */
      .col-md-4 a {
      font-size: 2.5em;
      padding: 1em;
      }
      .fa-facebook {
      color: #3b5998
      }
      .fa-twitter {
      color: #00aced;
      }
      .fa-instagram {
      color: #517fa4;
      }
      .fa-facebook:hover,
      .fa-twitter:hover,
      .fa-instagram:hover {
      color: #d5d5d5;
      text-decoration: none;
      }
      footer {
      background-color: #3f3f3f;
      color: #d5d5d5;
      }
      hr.light {
      border-top: 1px solid #d5d5d5;
      width: 75%;
      }
      p {
      padding-left: 10px;
      }
      /* footer end */
   </style>
   <body>
      <?php 
         //connect to db
         include 'connection.php';
         
         //start session
         session_start();
         $_SESSION['userID'] ='1';
         $_SESSION['isUser'] ='yes';
         $isUser = $_SESSION['isUser'];
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
         <br>
            <input type="radio" name="action" id="yes" value="yes">Save Payment Information<br>
            <input type="radio" name="action" id="no" value="no">Don't Save Payment Information<br>
            <input type="radio" name="action" id="update" value="update">Update Payment Information<br>
            <input type="radio" name="action" id="delete" value="delete">Delete Payment Information<br>   
         <?php 
        		if ($isUser == 'yes') {
        		   echo"<button name='checkout_btn' class='checkout_btn' type='submit'>Checkout</button>";
        		} else {
        		    echo "<br> Unable to checkout as you are not logged in!";
        		}
        	?>
      </form>
      <!-- Footer start -->
      <footer>
         <div class="container-fluid padding">
            <div class="row text-center">
               <div class="col-md-4">
                  <img src="images/websitelogo.png" alt="Website Logo" style="width: auto; height: 201px;">
                  <hr class="light">
                  <p>Temasek Polytechnic</p>
                  <p>School of IIT</p>
                  <p>21 Tampines Avenue 1, Singapore 529757</p>
                  <p>Tampines, Singapore</p>
               </div>
               <div class="col-md-4">
                  <br><br><br><br><br><br>
                  <hr class="light">
                  <h5>Contact Us</h5>
                  <hr class="light">
                  <p>+65 8123 4567</p>
                  <p>1907446g@student.tp.edu.sg</p>
                  <a href="#" class="fab fa-facebook"></a>
                  <a href="#" class="fab fa-twitter"></a>
                  <a href="#" class="fab fa-instagram"></a>
               </div>
               <div class="col-md-4">
                  <br><br><br><br><br><br>
                  <hr class="light">
                  <h5>About</h5>
                  <hr class="light">
                  <p>Privacy Policy</p>
                  <p>Terms and Conditions</p>
               </div>
               <div class="col-12">
                  <hr class="light">
                  <h5>&copy; Website Name</h5>
               </div>
            </div>
         </div>
      </footer>
      <!-- Footer end -->
   </body>
</html>