<?php 
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'"); //Allows only script from this page to run, preventing XSS and clickjacking
header("X-Frame-Options: DENY"); //Denys the use of <frame>, <iframe>, <embed> and <object> to protect clients from clickjacking
?>
<html>
<head>
      <script src="css/kitfontawesome9d4359df6d.js"></script>
      <!--bootstrap-->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="css/bootstrapcdn452.css">
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
               <a href="index.php"><img src="images/websitelogo.png" alt="Website Logo" style="width: 80px; height: 80px;"></a>
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
<h1 align="center">Login to your Account (Customer)</h1>
<form action="loginVad.php" method="post">
<table>
<tr><td>Username: </td><td><input inputmode="text" placeholder="Username" name="username"></td></tr>
<tr><td>Password: </td><td><input inputmode="text" type="password" placeholder="Password" name="password"></td></tr>
</table>
<input type="submit" value="Login" name="btnLogin"><br>
<a href="createAccount.php">Have not created your Customer account? Click here to Create!</a><br>
<a href="providerLogin.php">Are you a Service Provider? Click here to Login!</a>
</form>
<?php 
include_once 'alertMessageFunc.php';
if (isset($_GET['error']) && $_GET['error'] == 'invalid'){ //If return URL has an error that = 'invalid', prompt user with message
    promptMessage('Username or password is incorrect or does not exist!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'empty'){ //If return URL has an error that = 'empty', prompt user with message
    promptMessage('Please fill in fields of Username and Password!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'notloggedin'){ //If return URL has an error that = 'notloggedin', prompt user with message
    promptMessage('You have been redirected back as you were not logged in!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'errToken'){ //If return URL has an error that = 'errToken', prompt user with message
    promptMessage('Your session has expired or is not valid! Please re-login!');
}
?>

</body>
</html>

