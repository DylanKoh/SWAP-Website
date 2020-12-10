<?php
header("X-Frame-Options: DENY");
?>
<html>
   <head>
      <script src="css/kitfontawesome9d4359df6d.js" crossorigin="anonymous"></script>
      <!--bootstrap-->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="css/bootstrapcdn441.css">
      <script src="css/ajaxgoogleapisajaxlibs351.js"></script>
      <script src="css/cdnjscloudflareajaxpopper1160.js"></script>
      <script src="css/bootstrapcdn452.css"></script>
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
      /*login button start*/
      .loginImage {
      max-width: 100%;
      max-height: 100%;
      }
      .login #btn {
      position: absolute;
      top: 60%;
      left: 50%;
      font-size: 16px;
      padding: 12px 24px;
      text-align: center;
      }
      /*login buutton end*/
      /*about start*/
      .about {
      padding: 20px
      }
      /*about end*/
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
      <div class=login>
         <img src=SwapImage/choosing_your_pentester.png alt=choosing_your_pentester class="loginImage">
         <a href="login.php"><button type="button" class="btn btn-outline-light btn-lg" id="btn">Login</button></a>
      </div>
      <div class=about>
         <h2>Interested in Hiring a Pentester?</h2>
         <br>
         Do you need a penetration tester to help test your system/web application? <br>
         Freelance pentesters will be able to post their services here based on their specific skillsets, and you can choose to hire them based on your requirements.<br><br>
         <h4>Why do you need a pentester?</h4>
         <br>
         - Uncover critical vulnerabilities before cybercriminals can exploit them<br>
         - Gives you an opportunity to remedy these vulnerabilities<br>
         - It helps to improve the current status of your security infrastructure<br>
         - You can assess the potential impact of a successful attack on your infrastructure
      </div>
      <br><br>
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