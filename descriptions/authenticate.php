<?php
session_start();
include("../usergroups.php");
require_once '../Mobile_Detect.php';
$detect = new Mobile_Detect;
if (empty($_SESSION[$DepictionSession])) {
$_SESSION[$DepictionSession] = false;
}
$session = $_SESSION[$DepictionSession];
if ($session === false) {
if (isset($_POST['submitted'])) {
$pass_from_user = $_POST['pass'];
if ($pass_from_user === $DepictionPassword) {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>Authenticate</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>Authenticate</h1></header>
    <h2>Login</h2>
		<ul><li><p>
		<?php
		$_SESSION[$DepictionSession] = true;
		echo "Authenticated!";
		$rootadminurl = $CurrentDirectory . "descriptions/";
		echo "<meta http-equiv='refresh' content='1; url=$rootadminurl' />";
		?>
		</p></li></ul>
</body>
</html>
<?php } else {
$_SESSION[$DepictionSession] = true;
echo "Authenticated!";
$rootadminurl = $CurrentDirectory . "descriptions/";
echo "<meta http-equiv='refresh' content='1; url=$rootadminurl' />";
}
} else {
echo "Password incorrect!";
$rootauthurl = $CurrentDirectory . "descriptions/authenticate.php";
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
    <header><h1>Authenticate</h1></header>
    <h2>Login</h2>
		<ul><li><p><form action="" method="post">
            <input type="hidden" name="submitted">
			Password: <input type="password" name="pass"><br>
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
$rootauthurl = $CurrentDirectory . "descriptions/authenticate.php";
echo "<meta http-equiv='refresh' content='1; url=$rootauthurl' />";
}
?>