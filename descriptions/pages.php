<?php
include '../usergroups.php';
// Hide universal notices/warnings
error_reporting(E_ERROR);
// Error to display if a download count failed (somehow)
$download_count = "(error)";
if (!isset($_GET['file'])) {
echo "No identifier listed!";
exit();
} else {
$askedident = $_GET['file'];
// Don't do packages
if ($askedident === "Packages") {
echo "Sorry, this doesn't work for that file.";
exit();
}
}
// Alright, let's find out what our title is.
if (isset($_SERVER["HTTP_X_UNIQUE_ID"])) {
$udid = $_SERVER["HTTP_X_UNIQUE_ID"];
$approved_udids = array_keys($Users);
if (in_array($udid,$approved_udids)) {
$usergroup = $Users[$udid];
} else {
$usergroup = 0;
}
} else {
$usergroup = 0;
}
if ($usergroup === 0) {
$usertitle = $PermissionLevels["0"];
}
if ($usergroup === 1) {
$usertitle = $PermissionLevels["1"];
}
if ($usergroup === 2) {
$usertitle = $PermissionLevels["2"];
}
if ($usergroup === 3) {
$usertitle = $PermissionLevels["3"];
}
if ($usergroup === 4) {
$usertitle = $PermissionLevels["4"];
}
$namesarray = getJSON("names");
$descriptsarray = getJSON("description");
$availableIdentifiers = array_keys($namesarray);
$key = array_search($askedident,$availableIdentifiers);
if ($key !== FALSE) {
   $title = $namesarray[$askedident];
   $description_nobr = $descriptsarray[$askedident];
   $description = nl2br($description_nobr);
} else {
echo "Couldn't find that file!";
exit();
}
$con=mysqli_connect($Database,$DBUser,$DBPass,$DB_DB);
if (mysqli_connect_errno()) { echo "Failed to connect to MySQL: " . mysqli_connect_error(); }
$result = mysqli_query($con,"SELECT * FROM downloads");
$storeDownloadedPackages = Array();
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $storeDownloadedPackages[] =  $row['filename'];  
}
$debnames = getJSON("../debnames");
$allpackages = array_keys($debnames);
$currentdeb_notfound = false;
// Deprecated ^^
if (in_array($askedident,$storeDownloadedPackages)) {
$download_count = mysqli_query($con,"SELECT * FROM `downloads` WHERE filename='$askedident'");
$download_count = mysqli_fetch_array($download_count);
$download_count2 = $download_count['count'];
if ($download_count2 == 1) {
$afterres = "time";
} else {
$afterres = "times";
}
} else {
// if not in database
$download_count2 = "0";
$afterres = "times";
}
mysqli_close($con);

?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title><?php echo $title; ?></title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1><?php echo $title; ?></h1></header>
    <h2>Description</h2>

    <ul>
        <li><p><?php echo $description; ?></p></li></ul>
            <?php if ($currentdeb_notfound !== true) { ?>
            <h2>Download Stats</h2>
    <ul>
        <li><p>This package has been downloaded <?php echo "<b>" . $download_count2 . "</b>" . " " . $afterres; ?>.</p></li></ul> <?php } ?>
</body>
</html>