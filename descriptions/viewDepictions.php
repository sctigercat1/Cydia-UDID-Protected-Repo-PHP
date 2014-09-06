<?php
session_start();
include '../usergroups.php';
require_once '../Mobile_Detect.php';
$detect = new Mobile_Detect;
if (empty($_SESSION[$DepictionSession])) {
$_SESSION[$DepictionSession] = false;
}
$session = $_SESSION[$DepictionSession];
if ($session === true) {
if (!isset($_POST['submitted'])) {
$allDeipictions = getJSON("names");
$availableIdentifiers = array_keys($allDeipictions);
sort($availableIdentifiers);
$form = "<form action='' method='post'>
<input type='hidden' name='submitted'>
Identifier: <input type='text' name='identifier'><br>
<input type='submit' value='Search'>";
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>View Depictions</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>View Depictions</h1></header>
    <h2>All Depictions</h2>
		<ul><li><p><?php
			echo "<ul>";
			foreach ($availableIdentifiers as $ident) {
				echo "<li>".$ident."</li>";
			}
			echo "</ul>";
			?></p></li></ul>
</body>
</html>
<?php } else { 
echo "<ul>";
foreach ($availableIdentifiers as $ident) {
echo "<li>".$ident."</li>";
}
echo "</ul>";
}
/*
echo "<ul>";
foreach ($availableIdentifiers as $ident) {
echo "<li>".$ident."</li>";
}
echo "</ul>";
echo "<br>";
echo "<br>";
echo "<br>";
*/
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>View Depictions</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>View Depictions</h1></header>
    <h2>Search for a Depiction</h2>
		<ul><li><p><form action='' method='post'>
			<input type='hidden' name='submitted'>
			Identifier: <input type='text' name='identifier' autocapitalize="off"><br>
			<input type='submit' value='Search'>
			</form></p></li></ul>
</body>
</html>
<?php } else {  ?>
<form action='' method='post'>
<input type='hidden' name='submitted'>
Identifier: <input type='text' name='identifier'><br>
<input type='submit' value='Search'>
<?php }
} else {
$identifier = $_POST['identifier'];
$names = getJSON("names");
$descs = getJSON("description");
$debnames = getJSON("../debnames");
if (array_key_exists($identifier,$names)) {
$link1 = $CurrentDirectory . "descriptions/pages.php?file=";
$finallink2 = $link1 . $identifier;
$identname = $names[$identifier];
$identdesc = $descs[$identifier];
$identdeb = $debnames[$identifier];
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>View Depictions</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>View Depictions</h1></header>
    <h2>Depiction Search Result</h2>
		<ul><li><p><?php
			echo "Identifier: $identifier<br>";
			echo "Name: $identname<br>";
			echo "Deb Name: $identdeb<br>";
			echo "Description:<br>
			<pre>$identdesc</pre><br>";
			echo "Link to page: <a href='$finallink2'>$finallink2</a><br><br>";
			echo "<a href='javascript:window.history.back()'>Go back</a>";
			?></p></li></ul>
</body>
</html>
<?php } else {
echo "Identifier: $identifier<br>";
echo "Name: $identname<br>";
echo "Deb Name: $identdeb<br>";
echo "Description:<br>
<pre>$identdesc</pre><br>";
echo "Link to page: <a href='$finallink2'>$finallink2</a><br><br>";
echo "<a href='javascript:window.history.back()'>Go back</a>";
}
} else {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>View Depictions</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>View Depictions</h1></header>
    <h2>Depiction Search Result</h2>
		<ul><li><p><?php
			echo "Could not find identifier!<br>";
			echo "<a href='javascript:window.history.back()'>Go back</a>";
			?></p></li></ul>
</body>
</html>
<?php } else {
echo "Could not find identifier!<br>";
echo "<a href='javascript:window.history.back()'>Go back</a>";
}
}
}
} else {
$authurl = $CurrentDirectory . "descriptions/authenticate.php";
header("Location: $authurl");
} ?>