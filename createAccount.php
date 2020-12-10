<?php 
require_once 'sessionInitialise.php';
header("X-Frame-Options: DENY");
$createAccountToken=hash('sha256', uniqid(rand(), TRUE));
initialiseSessionVar('createAccountToken',$createAccountToken);
initialiseSessionVar('createAccountTokenTime',time());

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
<body>
<h1 align="center">Create a new Account</h1>
<?php 
   echo "<form action='createAccountDo.php' id='resubmitForm' method='post'>";
   echo "<input hidden name='createAccountToken' value='$createAccountToken'>";
   if (isset($_SESSION['googleSecret'])){
       echo "<input type='submit' value='Redo 2FA verification' name='btnReSubmit'>";       
       echo "</form>";
       echo "<script type='text/javascript'>
  document.getElementById('resubmitForm').submit();
</script>";
   }
   
?>
<form action="createAccountDo.php" method="post">
<input hidden name='createAccountToken' value="<?php echo $createAccountToken; ?>">
<table>
<tr><td>Full Name: </td><td><input inputmode="text" placeholder="Full Name" name="fullname" value="<?php 
if (isset($_GET['fullname']))
    echo htmlspecialchars(strip_tags($_GET['fullname']));
?>"></td></tr>
<tr><td>Username: </td><td><input inputmode="text" placeholder="Username" name="username" value="<?php 
if (isset($_GET['username']))
    echo htmlspecialchars(strip_tags($_GET['username']));
?>"></td></tr>
<tr><td>Email: </td><td><input inputmode="text" placeholder="Email" name="email" value="<?php 
if (isset($_GET['email']))
    echo htmlspecialchars(strip_tags($_GET['email']));
?>"></td></tr>
<tr><td>Password: </td><td><input inputmode="text" type="password" placeholder="Password" name="password"></td></tr>
<tr><td>Confirm Password: </td><td><input inputmode="text" type="password" placeholder="Confirm Password" name="rePassword"></td></tr>
<tr><td>Sign up as:</td></tr>
<tr><td><input type="radio" name="rbType" value="provider"></td><td>Provider</td></tr>
<tr><td><input type="radio" name="rbType" value="customer"></td><td>Customer</td></tr>
<tr><td>Setup 2FA now: </td><td><input type="checkbox" name="cb2FA"></td></tr>
</table>
<input type="submit" value="Sign Up" name="btnCreate"><br>
<a href="login.php">Already have a Customer Account? Click here to Login!</a><br>
<a href="providerLogin.php">Already have a Service Provider Account? Click here to Login!</a>
</form>
<?php 
require_once 'alertMessageFunc.php';
if (isset($_GET['error']) && $_GET['error'] == 'emptyfields'){
    promptMessage('Please fill in all of the fields!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'notEmail'){
    promptMessage('Please enter a valid email!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'passwordWeak'){
    promptMessage('Password must contain 1 upper, lower case, numeric and special character! No. of characters must be at least 8!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'passwordNoMatch'){
    promptMessage('Please ensure that password matches!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'emailTaken'){
    promptMessage('Email has already been taken! Please try using another email!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'createErr'){
    promptMessage('There was an error creating an account, please try again later!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'illegalCharacters'){
    promptMessage('Please ensure fullname has only alphabetical characters! Username allows only alphabets, numbers and "_?!" characters!');
}
elseif (isset($_GET['error']) && $_GET['error'] == 'sessionExpired'){
    promptMessage('Your session has expired! Please redo account creation!');
}
?>


</body>
</html>
