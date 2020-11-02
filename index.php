<?php
?>
<html>
   <head>
      <script src="https://kit.fontawesome.com/9d4359df6d.js" crossorigin="anonymous"></script>
      <!--bootstrap-->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
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
      .users {   
      -ms-flex: 70%;
      flex: 70%;
      background-color: white;
      padding: 30px;
      }
      .userimg {
      background-color: #aaa;
      height: 140px;
      width: 140px;
      padding: 20px;
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
      /* footer end */
   </style>
   <body>
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
                        <a class="nav-link">Explore</a>
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
      <div class="users">
         <h2>Top Sellers</h2>
         <br>
         <h5>Seller 1</h5>
         <div class="userimg">Seller 1 Image</div>
         <p>Seller Rating &#9733 &#9733 &#9733 &#9733 &#9733</p>
         <p>Seller Bio. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
         <br>
         <h5>Seller 2</h5>
         <div class="userimg">Seller 2 Image</div>
         <p>Seller Rating &#9733 &#9733 &#9733 &#9733 &#9733</p>
         <p>Seller Bio. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
      </div>
      <br><br>
      <div class="users">
         <h2>Top Buyers</h2>
         <br>
         <h5>Buyer 1</h5>
         <div class="userimg">Buyer 1 Image</div>
         <p>Buyer Rating &#9733 &#9733 &#9733 &#9733 &#9733</p>
         <p>Buyer Bio. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
         <br>
         <h5>Buyer 2</h5>
         <div class="userimg">Buyer 2 Image</div>
         <p>Buyer Rating &#9733 &#9733 &#9733 &#9733 &#9733</p>
         <p>Buyer Bio. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
      </div>
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