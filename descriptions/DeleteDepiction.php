<?php
session_start();
include("../usergroups.php");
require_once '../Mobile_Detect.php';
$detect = new Mobile_Detect;
// Only admins.
if (empty($_SESSION[$DepictionSession])) {
$_SESSION[$DepictionSession] = false;
}
$session = $_SESSION[$DepictionSession];
if ($session === true) {
if (isset($_POST['identifier'])) {
// check
if (isset($_POST['confirm']) && $_POST['confirm'] == "yes") {
$ident = $_POST['identifier'];
$descriptions = getJSON("description");
$names = getJSON("names");
$debnames = getJSON("../debnames");
$debnames = array_flip($debnames);
$allDepictionIdentifiers = array_keys($names);
if (in_array($ident,$allDepictionIdentifiers)) {
// Delete from names
unset($names[$ident]);
saveJSON("names",$names);
// Delete from descriptions
unset($descriptions[$ident]);
saveJSON("description",$descriptions);
$packagejson = $ident . ".json";
if (file_exists("../all_packages/$packagejson")) {
// Don't delete from debnames if this is an existing package
echo "Successfully removed $ident's depiction! NOTE: This still exists in ../debnames!";
} else {
// Otherwise, it's probably a test depiction (or something leftover from a deleted package), so we can delete it.
unset($debnames[$ident]);
saveJSON("../debnames",$debnames);
echo "Successfully removed $ident's depiction! NOTE: This does NOT exist in ../debnames! (It's not in the package listing, so we can assume it was a test.)";
}
} else {
echo "That depiction doesn't exist!";
exit();
}
} else {
echo "Mmm, be sure to confirm it.";
exit();
}
} else { 
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>Delete Depiction</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>Delete Depiction</h1></header>
    <h2>Fill out this form...</h2>
		<ul><li><p><form action="" method="post">
Identifier: <input type="text" name="identifier" autocapitalize="off"><br>
Are you sure? Type 'yes' in the box: <input type="text" name="confirm"><br><br>
<input type="submit" value="Submit">
</form></p></li></ul>
</body>
</html>
<?php } else { ?>
<form action="" method="post">
Identifier: <input type="text" name="identifier" autocapitalize="off"><br>
Are you sure? Type 'yes' in the box: <input type="text" name="confirm"><br>
<input type="submit" value="Submit">
</form>
<?php }
}
} else {
$authurl = $CurrentDirectory . "/descriptions/authenticate.php";
header("Location: $authurl");
}
?>