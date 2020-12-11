<?php 
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
header("X-Frame-Options: DENY");
require_once 'sessionInitialise.php';
destroySession();
?>
<head>
  <title>Successful Logout Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrapcdn452.css">
  <script src="css/ajaxgoogleapisajaxlibs351.js"></script>
  <script src="css/cdnjscloudflareajaxpopper1160.js"></script>
  <script src="bootstrapcdn452.js"></script>
</head>
<body>

<div class="jumbotron text-center">
  <h1>Website Nav bar</h1>
</div>
  
<div class="container">

  <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Success!</strong> Please clear your cache after exiting this page
  </div>
</div>

</body>
