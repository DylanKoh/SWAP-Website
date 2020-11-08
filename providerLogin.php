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
               <a href="index.php" href="index.html"><img src="images/websitelogo.png" alt="Website Logo" style="width: 80px; height: 80px;"></a>
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
<body>
<h1 align="center">Login to your Account</h1>
<form action="" method="post">
<table>
<tr><td>Username: </td><td><input inputmode="text" placeholder="Username" name="username"></td></tr>
<tr><td>Password: </td><td><input inputmode="text" type="password" placeholder="Password" name="password"></td></tr>
</table>
<input type="submit" value="Login" name="btnLogin"><br>
<a href="createAccount.php">Have not created your Provider account? Click here to Create!</a><br>
<a href="login.php">Are you a Customer? Click here to Login!</a>
</form>

<?php
//Login to DB and to Providers table
if (isset($_POST["btnLogin"])){
    if (isset($_POST['username']) && isset($_POST['password'])){
        if (strlen($_POST['username']) > 0 && strlen($_POST['password']) > 0){
            echo "Hello Check";
            //Check if account exist
            //If exist, Log in or Validate 2FA if applicable
            //Else, error message
        }
        else{
            alertMessage("Please ensure fills are not empty!");
        }
    }
    else{
        alertMessage("Please ensure fills are filled!");
    }
}
function alertMessage($message){
    echo "<script type='text/javascript'>alert('$message');</script>";
}
?>
</body>
</html>

