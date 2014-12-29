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
if (isset($_POST['udid']) && isset($_POST['level'])) {
// Do if adding user ids
$approved_udids = getJSON("approved_udids_2");
$onlyudid = array();
foreach($approved_udids as $w => $n) {
  $value = $approved_udids[$w][0];
  $onlyudid[] = $value;
}
$userudid = $_POST['udid'];
$newuserlevel = $_POST['level'];
$newusername = $_POST['name'];
if (in_array($userudid,$onlyudid)) {
if ($newuserlevel >= 0 && $newuserlevel <= 4) {
// If UDID already exists
foreach ($approved_udids as $key => $value) { 
$udid = $approved_udids[$key][0];
if ($udid == $userudid) {
unset($approved_udids[$key]);
}
}
// Deleted, now readd...
$updatedudid = array($userudid,$newuserlevel,$newusername);
// And merge
//$finalupdatedarray = array_merge($approved_udids,$updatedudid);
array_push($approved_udids,$updatedudid);
// Save it
saveJSON("approved_udids_2",$approved_udids);
echo "Usergroup updated!<br>";
//print_r($finalupdatedarray);
$finaladdr = $CurrentDirectory."ManageUDID.php";
echo "<META http-equiv='refresh' content='1;URL=$finaladdr'>";
} else {
// We need to delete the user.
$approved_udids = getJSON("approved_udids_2");
$userudid = $_POST['udid'];
foreach ($approved_udids as $key => $value) { 
$udid = $approved_udids[$key][0];
if ($udid == $userudid) {
unset($approved_udids[$key]);
}
}
saveJSON("approved_udids_2",$approved_udids);
echo "User deleted!";
$finaladdr = $CurrentDirectory."ManageUDID.php";
echo "<META http-equiv='refresh' content='1;URL=$finaladdr'>";
}
} else {
// If it doesn't exist
$userudid = $_POST['udid'];
$newuserlevel = $_POST['level'];
$newusername = $_POST['name'];
$oldarray = getJSON("approved_udids_2");
$newarray = array($userudid,$newuserlevel,$newusername);
array_push($oldarray,$newarray);
saveJSON("approved_udids_2",$oldarray);
echo "User added!";
$finaladdr = $CurrentDirectory."ManageUDID.php";
echo "<META http-equiv='refresh' content='1;URL=$finaladdr'>";
}
} else {
// Otherwise, show.
$approved_udids = getJSON("approved_udids_2");
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>All UDIDs</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>All UDIDs</h1></header>
    <h2>Users</h2>
		<ul><li><p><?php
			echo "<font size='1'><ul>";
			foreach ($approved_udids as $w => $n) {
			    $UDID = $approved_udids[$w][0];
				$level = $approved_udids[$w][1];
				$name = $approved_udids[$w][2];
				echo "<li>".$name.": ".$UDID.": ".$level."</li>";
			}
			echo "</ul></font>";
			?></li></p></ul><br><br>
    <h2>Adjust Level, Add New User, or Delete</h2>
		<ul><li><p>Everything's pretty self-explanatory, except to delete, in the access level box type anything lower than 0 or higher than 4.<br>
		<form action='' method='post'>
			UDID: <input type='text' name='udid' autocapitalize="off"><br>
			Access Level: <input type='text' name='level' autocapitalize="off"><br>
			Name: <input type='text' name='name' autocapitalize="off"><br>
			<input type='submit' value='Submit'>
			</form></p></li></ul>
			<br>
			<br>
			<ul><li><a href="Admin.php">Go back to admin interface.</a></li></ul>
</body>
</html>
<?php } else { ?>
This is where you can add, modify, or delete approved UDIDs. To add or modify, simply type in the user's UDID and preferred access level. To delete, put anything greater than 4 or less than 0 in the access level box.<br><br>
<ul>
<?php foreach ($approved_udids as $w => $n) {
    $UDID = $approved_udids[$w][0];
	$level = $approved_udids[$w][1];
	$name = $approved_udids[$w][2];
	echo "<li>".$name.": ".$UDID.": ".$level."</li>";
} ?>
</ul>
<br><br>
<form action='' method='post'>
UDID: <input type='text' name='udid'><br>
Access Level: <input type='text' name='level'><br>
Name: <input type='text' name='name'><br>
<input type='submit' value='Submit'>
<br>
<br>
<br>
<a href="Admin.php">Go back to admin interface</a>.
</form>
<?php }
}
} else {
$authurl = $CurrentDirectory . "auth.php";
$_SESSION['redirlink'] = $_SERVER['REQUEST_URI'];
header("Location: $authurl");
}
?>