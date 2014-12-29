<?php
session_start();
include("../usergroups.php");
$noExist = false;
require_once '../Mobile_Detect.php';
$detect = new Mobile_Detect;
if (empty($_SESSION[$DepictionSession])) {
$_SESSION[$DepictionSession] = false;
}
$session = $_SESSION[$DepictionSession];
$descriptionroot = $CurrentDirectory . "descriptions/";
if ($session === true) {
if (isset($_POST['identifier_orig'])) {
$identifier_orig = $_POST['identifier_orig'];
$identifier_new = $_POST['identifier_new'];
$name = $_POST['name'];
$desc = $_POST['description'];
$userdebname = $_POST['userdebname'];
$oldNameArray = getJSON("names");
$prepName = $oldNameArray;
$oldDescArray = getJSON("description");
$prepDesc = $oldDescArray;
if (array_key_exists($identifier_orig,$prepName)) {
// if already exists, remove it from prep
unset($prepName[$identifier_orig]);
unset($prepDesc[$identifier_orig]);
// now we create new arrays
$newArrayNames = array($identifier_new => $name);
$newArrayDescs = array($identifier_new => $desc);
// name merge
$finalName = array_merge($prepName,$newArrayNames);
saveJSON("names",$finalName);
// description merge
$finalDesc = array_merge($prepDesc,$newArrayDescs);
saveJSON("description",$finalDesc);
// Ok, they're saved
} else {
$noExist = true;
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>Change Depiction</title></head><body>
    <header><h1>Change Depiction</h1></header>
    <h2>Uh oh...</h2>
		<ul><li><p>Depiction didn't already exist.</p>
		<meta http-equiv='refresh' content='1; url=<?php echo $descriptionroot; ?>' /></li></ul>
</body>
</html>
<?php } else { ?>
<p>Depiction didn't already exist.</p>
<meta http-equiv='refresh' content='1; url=<?php echo $descriptionroot; ?>' />
<?php }
}
if ($noExist === false) {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>Change Depiction</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>Change Depiction</h1></header>
    <h2>Done!</h2>
		<ul><li><p>Updated depiction!</p>
		<meta http-equiv='refresh' content='1; url=<?php echo $descriptionroot; ?>' /></li></ul>
</body>
</html>
<?php } else { ?>
<p>Updated depiction!</p>
<meta http-equiv='refresh' content='1; url=<?php echo $descriptionroot; ?>' />
<?php }
}
} else {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>Change Depiction</title></head><body>
    <header><h1>Change Depiction</h1></header>
    <h2>Fill put this form...</h2>
		<ul><li><p><form action="" method="post">
			<input type="hidden" name="submit">
			Original Identifier: <input type="text" name="identifier_orig" autocapitalize="off" required><br>
			New Identifier: <input type="text" name="identifier_new" autocapitalize="off" required><br>
			Tweak name: <input type="text" name="name" required><br>
			Tweak description: <br>
			<textarea name="description" rows="6" cols="45"></textarea><br>
			<input type="submit" value="Submit">
			</form></p></li></ul>
</body>
</html>
<?php } else { ?>
<form action="" method="post">
<input type="hidden" name="submit">
Original Identifier: <input type="text" name="identifier_orig" required><br>
New Identifier: <input type="text" name="identifier_new" required><br>
Tweak name: <input type="text" name="name"><br>
Tweak description: <br>
<textarea name="description" rows="6" cols="50"></textarea><br>
<input type="submit" value="Submit">
</form>
<?php }
}
} else {
$authurl = $CurrentDirectory . "descriptions/authenticate.php";
header("Location: $authurl");
}
?>
