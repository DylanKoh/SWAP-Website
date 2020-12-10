<?php
header("Content-Security-Policy: script-src 'https://localhost/SWAPWebsite'; "); //Starts Content Security Policy to protect any remote code execution
//check connection to MySql database
include 'connection.php';

session_set_cookie_params(0,'/','localhost', TRUE, TRUE);
session_start();
$_SESSION['servicesId']='1';
$_SESSION['usersId']='1';

?>
<?php    
    //Retrieving services from database:
    $stmt= $conn->prepare("SELECT services.servicesId, services.serviceName, services.serviceDesc, services.providersFkid, services.price  FROM services
                                  INNER JOIN providers ON services.providersFkid = providers.providersId INNER JOIN orders ON services.servicesId = orders.servicesFkid");
    $res = $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($servicesId, $serviceName, $serviceDesc, $providersFkid, $price);
    
    echo "<div class='sell-column'>";
    while($stmt->fetch()){
        echo"<a id=$servicesId'><div class='container'>";
        echo"<div class='box-view'><div class='sell-info'>";
        echo"<p id='title' style='font-size:22px;'><b>". $serviceName . "</b></p>";
        echo"<p style='font-size:14px;'> Provider: ".$username."</p>";
        echo "<p id='sell-price'>Price: $". $price. "</p>";
        echo"<p id='rating'>5 <i class='fas fa-star fa-sm'></i> <a>(No. of Reviews)</a></p>";
        echo"</div> </div> </div></a>";
        
    }
    echo "</div>";
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

<!--         			comments (can be empty) -->
<!--         			status of isCompleted to be false -->
        			
                      <tr><h2>Post an Offer</h2><td>
                      <label for='sComment'><b>Comments:</b></label> <br> 
                      <!--         			servicesId -->
                      <input id='comment' type='text' placeholder='Enter your comment' name='orderComments' required> <br> <br>
                      <input id='status' type='radio' name='completedStatus' value='False' required> <br><br>;
                      <button class='post-ser' type="submit" >Post</button>
		</form>
	</table>
    </body>
      
</html>