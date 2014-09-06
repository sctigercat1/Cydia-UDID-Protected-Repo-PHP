<?php
session_start();
require_once 'Mobile_Detect.php';
include './usergroups.php';
$detect = new Mobile_Detect;
if (empty($_SESSION[$RootSession])) {
$_SESSION[$RootSession] = false;
}
$session = $_SESSION[$RootSession];
if ($session === true) {
$availableIdentifiers = array();
if (is_dir("all_packages"))
{
        if ($handle = opendir("all_packages"))
        {
                //Notice the parentheses I added:
                while(($file = readdir($handle)) !== FALSE)
                {
						$temp = explode( '.', $file );
						$ext = array_pop( $temp );
						$name = implode( '.', $temp );
						// Remove .json
                        $availableIdentifiers[] = $name;
                }
                closedir($handle);
        }
}
if (!isset($_POST['submitted'])) {
// Get all identifiers
//////////////////////////////

//////////////////////////////
//$allDeipictions = getJSON("names");
//$availableIdentifiers = array_keys($allDeipictions);
sort($availableIdentifiers);
// remove first useless values
unset($availableIdentifiers[0]);
unset($availableIdentifiers[1]);
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
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>All Packages</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>All Packages</h1></header>
    <h2>What already exists</h2>
		<ul><li><p><?php
			echo "<ul>";
			foreach ($availableIdentifiers as $ident) {
				echo "<li>".$ident."</li>";
			}
			echo "</ul>";
			?></li></p></ul><br><br>
    <h2>Search for one</h2>
		<ul><li><p><form action='' method='post'>
			<input type='hidden' name='submitted'>
			Identifier: <input type='text' name='identifier' autocapitalize="off"><br>
			<input type='submit' value='Search'>
			</form></p></li></ul>
</body>
</html>
<?php } else {
echo "<ul>";
foreach ($availableIdentifiers as $ident) {
echo "<li>".$ident."</li>";
}
echo "</ul>"; ?> 
<br><br>
<form action='' method='post'>
<input type='hidden' name='submitted'>
Identifier: <input type='text' name='identifier'><br>
<input type='submit' value='Search'>
<?php }

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
} else {
$identifier = $_POST['identifier'];
//$names = getJSON("names");
//$descs = getJSON("description");
//$debnames = getJSON("../debnames");
if (in_array($identifier,$availableIdentifiers)) {
$link1 = $CurrentDirectory . "ChangeFile.php?identifier=";
$finallink2 = $link1 . $identifier;
//$identname = $names[$identifier];
//$identdesc = $descs[$identifier];
//$identdeb = $debnames[$identifier];
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>All Packages</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>All Packages</h1></header>
    <h2>Package Search Result</h2>
		<ul><li><p><?php echo "Visit </p><a href='$finallink2'>$finallink2</a><p> to see everything about this package and be able to make changes to it.<br><br>";
			echo "</p><a href='javascript:window.history.back()'>Go back</a>"; ?></ul><br><br>
</body>
</html>
<?php } else {
echo "Visit <a href='$finallink2'>$finallink2</a> to see everything about this package and be able to make changes to it.<br><br>";
echo "<a href='javascript:window.history.back()'>Go back</a>";
}
} else {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>All Packages</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>All Packages</h1></header>
    <h2>Package Search Result</h2>
		<ul><li><p><?php echo "Could not find identifier!<br>";
			echo "</p><a href='javascript:window.history.back()'>Go back</a>"; ?></ul><br><br>
</body>
</html>
<?php } else {
echo "Could not find identifier!<br>";
echo "<a href='javascript:window.history.back()'>Go back</a>";
}
}
}
} else {
$authurl = $CurrentDirectory . "auth.php";
header("Location: $authurl");
} ?>