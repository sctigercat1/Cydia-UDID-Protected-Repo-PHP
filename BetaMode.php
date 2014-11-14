<?php
session_start();
include("./usergroups.php");
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
// Only admins.
if (empty($_SESSION[$RootSession])) {
$_SESSION[$RootSession] = false;
}
$session = $_SESSION[$RootSession];
if ($session === true) {
unset($_SESSION['redirlink']);
if (isset($_POST['submitted'])) {
$BetaModeStatus = $_POST['betamode'];
if ($BetaModeStatus == "true") {
$finalarray = array(true);
}
if ($BetaModeStatus == "false") {
$finalarray = array(false);
}
saveJSON("beta_mode",$finalarray);
echo "Updated!";
$finaladdr = $CurrentDirectory."BetaMode.php";
echo "<META http-equiv='refresh' content='1;URL=$finaladdr'>";
} else {
$betamode = getJSON("beta_mode");
if ($betamode[0] == true) {
$betamodeword = "true";
} elseif ($betamode[0] == false) {
$betamodeword = "false";
} else {
$betamodeword = "error :(";
}
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>All Packages</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>Beta Mode</h1></header>
    <h2>Current Status</h2>
		<ul><li><p><?php
			echo $betamodeword;
			?></li></p></ul>
    <h2>Change Status</h2>
		<ul><li><p>True or false?<br>
		<form action='' method='post'>
		<input type="hidden" value="true" name="submitted">
			True: <input type='radio' name='betamode' value=true><br>
			False: <input type='radio' name='betamode' value=false><br>
			<input type='submit' value='Submit'>
			</form></p></li></ul>
			<br>
			<br>
			<ul><li><a href="Admin.php">Go back to admin interface.</a></li></ul>
</body>
</html>
<?php } else { ?>
Current status of beta mode:<br><br>
<?php echo "<b>$betamodeword</b>"; ?>
<br><br><u>Change Status</u><br><br>
<form action='' method='post'>
<input type="hidden" value="true" name="submitted">
True: <input type='radio' name='betamode' value=true><br>
False: <input type='radio' name='betamode' value=false><br>
<input type='submit' value='Submit'>
<br>
<br>
<br>
<a href="Admin.php">Go back to admin interface</a>.
<?php }
}
} else {
$authurl = $CurrentDirectory . "auth.php";
$_SESSION['redirlink'] = $_SERVER['REQUEST_URI'];
header("Location: $authurl");
}
?>