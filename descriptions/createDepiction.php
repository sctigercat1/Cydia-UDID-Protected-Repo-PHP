<?php
session_start();
include("../usergroups.php");
require_once '../Mobile_Detect.php';
$detect = new Mobile_Detect;
if (empty($_SESSION[$DepictionSession])) {
$_SESSION[$DepictionSession] = false;
}
$session = $_SESSION[$DepictionSession];
if ($session === true) {
if (isset($_GET['identifier'])) {
$ident_from_create = $_GET['identifier'];
}
if (isset($_GET['name'])) {
$name_from_create = $_GET['name'];
}
if (isset($_GET['debname'])) {
$debname_from_create = $_GET['debname'];
}
if (isset($_POST['submit'])) {
$ident = $_POST['identifier'];
$name = $_POST['name'];
$desc = $_POST['description'];
$debnames = $_POST['debnames'];
$originalArrayNames = getJSON("names");
$originalArrayDescs = getJSON("description");
$originalArrayDebs = getJSON("../debnames");
if ($originalArrayNames === null) {
$prepName = array();
} else {
$prepName = $originalArrayNames;
}
if ($originalArrayDescs === null) {
$prepDesc = array();
} else {
$prepDesc = $originalArrayDescs;
}
if ($originalArrayDebs === null) {
$prepDebs = array();
} else {
$prepDebs = $originalArrayDebs;
}
//$prepDesc = $originalArrayDescs;
if (!array_key_exists($ident, $prepName)) {
    $newArrayNames = array($ident => $name);
	$newArrayDescs = array($ident => $desc);
	$newArrayDebs = array($ident => $debnames);
	// name merge
	$finalName = array_merge($prepName,$newArrayNames);
//	$finalName = $prepName + $newArrayNames;
	saveJSON("names",$finalName);
	chmod("names",0777);
	// description merge
	$finalDesc = array_merge($prepDesc,$newArrayDescs);
	saveJSON("description",$finalDesc);
	chmod("description",0777);
	// deb merge
	$finalDebs = array_merge($prepDebs,$newArrayDebs);
	saveJSON("../debnames",$finalDebs);
	chmod("../debnames",0777);
	echo "Added!"; 
	echo "<br>";
	$link1 = $CurrentDirectory . "descriptions/pages.php?file=";
	$finallink = $link1 . $ident;
	echo $finallink;
	echo "<br>";
	echo "<br>";
	$descriptroot = $CurrentDirectory . "descriptions/";
	echo "<a href='$descriptroot'>Go back</a>.";
//	print_r($prepDesc);
//	print_r($finalName);
} else {
echo "That already exists!";
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
    <header><h1>Create Depiction</h1></header>
    <h2>Fill out this form...</h2>
		<ul><li><p><form action="" method="post">
			<input type="hidden" name="submit">
			Tweak identifier: <input type="text" name="identifier" autocapitalize="off" <?php if (isset($_GET['identifier'])) { echo "value='$ident_from_create'"; } ?>><br>
			Tweak name: <input type="text" name="name" <?php if (isset($_GET['name'])) { echo "value='$name_from_create'"; } ?>><br>
			Tweak deb name: <input type="text" name="debnames" required <?php if (isset($_GET['debname'])) { echo "value='$debname_from_create'"; } ?>><br>
			Tweak description: <br>
			<textarea name="description" rows="6" cols="45"></textarea><br>
			<input type="submit" value="Submit">
		</form></p></li></ul>
</body>
</html>
<?php }
else { ?>
<form action="" method="post">
<input type="hidden" name="submit">
Tweak identifier: <input type="text" name="identifier" <?php if (isset($_GET['identifier'])) { echo "value='$ident_from_create'"; } ?>><br>
Tweak name: <input type="text" name="name" <?php if (isset($_GET['name'])) { echo "value='$name_from_create'"; } ?>><br>
Tweak deb name: <input type="text" name="debnames" required <?php if (isset($_GET['debname'])) { echo "value='$debname_from_create'"; } ?>><br>
Tweak description: <br>
<textarea name="description" rows="6" cols="50"></textarea><br>
<input type="submit" value="Submit">
</form>
<?php } 
}
} else {
$authurl = $CurrentDirectory . "descriptions/authenticate.php";
header("Location: $authurl");
} ?>
