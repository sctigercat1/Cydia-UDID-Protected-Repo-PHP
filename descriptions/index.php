<?php
session_start();
include("../usergroups.php");
if (empty($_SESSION[$DepictionSession])) {
$_SESSION[$DepictionSession] = false;
}
require_once '../Mobile_Detect.php';
$detect = new Mobile_Detect;
$session = $_SESSION[$DepictionSession];
$authurl = $CurrentDirectory . "descriptions/authenticate.php";
if ($session === false) {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>MasterJumblespeed's Repo</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>Depiction Interface</h1></header>
    <h2>Oh, hi there.</h2>
		<ul><li><p>Hello! If you're viewing this page, it means you're currently trying to access the depiction admin interface. I advise you only to push on if you're the creator. Thanks!</p></li></ul>
</body>
</html>
<script>function goAwayMobile() { window.location = "<?php echo $authurl; ?>" }
setInterval(goAwayMobile, 4000);
</script>
<?php }
else {
echo "Hello! If you're viewing this page, it means you're currently trying to access the depiction admin interface. I advise you only to push on if you're the creator and know the correct password. Thanks!";
echo "<script>function goAway() { window.location = \"$authurl\" }
setInterval(goAway, 2000);</script>";
}
} else {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title><?php echo $RepoTitleName; ?> Repo</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>Depiction Admin Interface</h1></header>
    <h2>Welcome!</h2>
		<ul><li><p>Hi! Welcome to the Depiction Admin Interface!<br>
			<ul>
			<li><a href="createDepiction.php">Create depiction</a></li>
			<li><a href="viewDepictions.php">View depiction</a></li>
			<li><a href="changeDepiction.php">Change depiction</a></li>
			<li><a href="DeleteDepiction.php">Delete depiction</a></li>
			<li><a href="pages.php">Main depiction page (get=file)</a></li>
			<li><a href="../Admin.php">Root Admin Interface</a></li>
			<li><a href="../..">Main Site Page</a></li>
			<li><a href="unAuth.php">Unauthenticate</a></li>
			</ul></p></li></ul>
</body>
</html>
<?php }
else {
echo "<center><font size='4'>Hi! Welcome to the Depiction Admin Interface!</center><br>
			<ul>
			<li><a href=\"createDepiction.php\">Create depiction</a></li>
			<li><a href=\"viewDepictions.php\">View depiction</a></li>
			<li><a href=\"changeDepiction.php\">Change depiction</a></li>
			<li><a href=\"DeleteDepiction.php\">Delete depiction</a></li>
			<li><a href=\"pages.php\">Main depiction page (get=file)</a></li>
			<li><a href=\"../Admin.php\">Root Admin Interface</a></li>
			<li><a href=\"..\">Main Site Page</a></li>
			<li><a href=\"unAuth.php\">Unauthenticate</a></li>
			</ul></font>";
}
}
?>