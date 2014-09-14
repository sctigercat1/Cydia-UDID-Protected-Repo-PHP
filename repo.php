<?php
// Override 128mb limit
ini_set('memory_limit', '-1');
include("./usergroups.php");

require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
if (!isset($_SERVER["HTTP_X_UNIQUE_ID"])) {
//if( $detect->isiOS() ){ 
?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title><?php echo $RepoTitleName; ?> Repo</title></head><body>
    <header><h1><?php echo $RepoTitleName; ?> Repo</h1></header>
    <h2>Intro</h2>
    <ul>
        <li><p>Hello! This is my personal UDID-restricted repo!</p></li></ul><br><br><br>
		<ul><li><a href="cydia://sources/add">Open Cydia</a></li>
		<li><a href="<?php echo $CurrentDirectory."Admin.php"; ?>">Access admin interface (approved users only)</a></li></ul>
</body>
</html>
<?php // }
//else {
//echo "<center><font size='4'>You shouldn't be here.</font></center>";
//}
}
else {
function error($code) {
if ($code === "403") {
header('HTTP/1.0 403 Forbidden');
} elseif ($code === "404") {
header('HTTP/1.0 404 Not Found');
} elseif ($code === "500") {
header("500 Internal Service Error");
} else {
header("$code");
}
}
$udid = $_SERVER["HTTP_X_UNIQUE_ID"];
//$udid = "one";
// $Users
$alludids = array_keys($Users);
if (!in_array($udid,$alludids)) {
//default
$usergroup = 0;
} else {
$usergroup = $Users[$udid];
}
$file_requested_deb = $_GET['file'];
$orig_file = $_GET['file'];
$debnames = getJSON("debnames");
$debnames = array_flip($debnames);
// Log identifier instead of deb name
$thepackageidentifier = $debnames[$orig_file];
$packagelist = getJSON("package_groups");
$seeIfPackageExists = array_keys($packagelist);
if (in_array($file_requested_deb,$seeIfPackageExists)) {
$requiredusergroup = $packagelist[$file_requested_deb];
if ($usergroup >= $requiredusergroup) {
// Log it in mysql
////////////////////////////////////
$link = mysqli_connect($Database, $DBUser, $DBPass); //change parameters to what you use
if (!$link) {
    error("Couldn't connect to the database. Please try your download later.");
}
if (!mysqli_set_charset($link, "UTF8")) { //change UTF8 if your database uses otherwise
    error("Couldn't set the charset for the database. Please try your download later.");
}
if (!mysqli_select_db($link, $DB_DB)) { //change myrepodb to your database name
    error("Couldn't open the database. Please try your download later.");
}
    $query = mysqli_query($link, "SELECT * FROM downloads WHERE filename='$thepackageidentifier'");
    if (!$query or mysqli_num_rows($query) == 0) {
        //If it doesn't exist, create the row
        mysqli_query($link, "INSERT INTO downloads SET filename='$thepackageidentifier', count=1, dldate=NOW()");
    } else {
        //If it does, add one to the counter
        mysqli_query($link, "UPDATE downloads SET count=count+1, dldate=NOW() WHERE filename='$thepackageidentifier'");
    }
////////////////////////////////////
$endfile = $file_requested_deb . ".deb";
if (file_exists("debs/$endfile")) {
$cont = file_get_contents("debs/$endfile");
//If it's more than 16 bytes long, it's ok to serve it
if (strlen($cont) > 16) {
    //Force Cydia to download the file, not display it
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: inline; filename=\"$endfile\"");
    //Tell Cydia how big the file is so it can show the progress bar
    header("Content-Length: " . strlen($cont));
    //And finally, output the file!
    echo $cont;
} else {
echo "404";
    error("404");
}
} else {
echo "Can't find file";
error("404");
}
} else {
error("403");
echo "403";
}
} else {
echo "404";
error("404");
}
}

?>
