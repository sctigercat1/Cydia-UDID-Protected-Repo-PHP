<?php
session_start();
include './usergroups.php';
if (empty($_SESSION[$RootSession])) {
$_SESSION[$RootSession] = false;
}
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
$session = $_SESSION[$RootSession];
if ($session === false) {
$rootauthurl = $CurrentDirectory . "auth.php";
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title><?php echo $title; ?></title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1><?php echo $RepoTitleName; ?> Repo</h1></header>
    <h2>Intro</h2>
    <ul>
        <li><p>Hi there, you're about to be redirected to the login page. Only try if you know the password; otherwise, don't even try to humour me.</p></li></ul>
</body>
</html>
<script>function goAwayMobile() { window.location = "<?php echo $rootauthurl; ?>" }
setInterval(goAwayMobile, 3000);
</script>
<?php }
else {
echo "<center><font size='4'>Hi there, you're about to be redirected to the login page. Only try if you know the password; otherwise, don't even try to humour me.</font></center>";
echo "<script>function goAway() { window.location = \"$rootauthurl\" }
setInterval(goAway, 3000);</script>";
}
} else {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title><?php echo $RepoTitleName; ?>s Repo</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>Root Admin Interface</h1></header>
    <h2>Welcome!</h2>
		<ul><li><p>Hi! Welcome to the Admin Interface!<br>
			<ul>
			<li><a href="CreateFile.php">Create file</a></li>
			<li><a href="DeleteFile.php">Delete file</a></li>
			<li><a href="ChangeFile.php">Change file</a></li>
			<li><a href="allFiles.php">All files</a></li>
			<li><a href="descriptions">Depiction Interface</a></li>
			<li><a href="repo.php">Repo Main Page</a></li>
			<li><a href="ManageUDID.php">Manage UDIDs</a></li>
			<li><a href="BetaMode.php">Beta Mode</a></li>
			<li><a href="unauth.php">Unauthenticate</a></li>
			</ul></p></li></ul>
</body>
</html>
<?php }
else {
echo "<center><font size='4'>Hi! Welcome to the Admin Interface!</center><br>
			<ul>
			<li><a href='CreateFile.php'>Create file</a></li>
			<li><a href='DeleteFile.php'>Delete file</a></li>
			<li><a href='ChangeFile.php'>Change file</a></li>
			<li><a href='allFiles.php'>All files</a></li>
			<li><a href='descriptions'>Depiction Interface</a></li>
			<li><a href='repo.php'>Repo Main Page</a></li>
			<li><a href='ManageUDID.php'>Manage UDIDs</a></li>
			<li><a href='BetaMode.php'>Beta Mode</a></li>
			<li><a href='unauth.php'>Unauthenticate</a></li>
			</ul></font>";
}
}
?>