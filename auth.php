<?php
session_start();
include './usergroups.php';
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
if (empty($_SESSION[$RootSession])) {
$_SESSION[$RootSession] = false;
}
$session = $_SESSION[$RootSession];
if ($session === false) {
if (isset($_POST['submitted'])) {
$pass_from_user = $_POST['pass'];
if ($pass_from_user === $RootPassword) {
$_SESSION[$RootSession] = true;
echo "Authenticated!";
$rootadminurl = $CurrentDirectory . "Admin.php";
echo "<meta http-equiv='refresh' content='1; url=$rootadminurl' />";
} else {
echo "Password incorrect!";
$rootauthurl = $CurrentDirectory . "auth.php";
echo "<meta http-equiv='refresh' content='1; url=$rootauthurl' />";
}
} else { 
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>Authenticate</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>Login to Admin Interface</h1></header>
	<h2>Login</h2>
	<ul>
        <li><p><form action="" method="post">
            <input type="hidden" name="submitted">
			Password: <input type="password" name="pass"><br><br>
			<input type="submit" value="Submit">
			</form></p></li></ul>
</body>
</html>
<?php } else { ?>
<form action="" method="post">
<input type="hidden" name="submitted">
Password: <input type="password" name="pass"><br>
<input type="submit" value="Submit">
</form>
<?php }
}
} else {
echo "You've already been authenticated!";
}
?>