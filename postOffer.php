<?php
//check connection to MySql database
include 'connection.php';

session_start();
$_SESSION['servicesId']='1';
$_SESSION['userId']='1';

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
                     <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                     </li>
                     <li class="nav-item active">
                        <a class="nav-link">Post an Offer</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="storePage.php">Explore</a>
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
      
    		
        			<form action='postOfferCRUD.php' method='post'>
        			<table>
<!--         			For insert, -->
<!--         			will require  -->
<!--         			userId as customerFkid -->
<!--         			servicesId -->
<!--         			comments (can be empty) -->
<!--         			status of isCompleted to be false -->
        			
                      <tr><h2>Post an Offer</h2><td>
                      <label for='sName'><b>Comments:</b></label> <br>
                      <input id='name' type='text' placeholder='Enter your comment' name='serName' required> <br> <br>
                      <button class='post-ser' type="submit" >Post</button>
		</form>
	</table>
    </body>
      
</html>