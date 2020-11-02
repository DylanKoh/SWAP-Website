<html>
<head><title>Login</title></head>
<body>
<h1 align="center">Login to your Account</h1>
<form action="" method="post">
<table>
<tr><td>Username: </td><td><input inputmode="text" placeholder="Username" name="username"></td></tr>
<tr><td>Password: </td><td><input inputmode="text" type="password" placeholder="Password" name="password"></td></tr>
</table>
<input type="submit" value="Login" name="btnLogin">
<input type="submit" value="Create New Account" name="btnCreateAccount">
</form>

<?php
if (isset($_POST['btnCreateAccount'])){
    header("Location: createAccount.php");
}
?>
</body>
</html>

