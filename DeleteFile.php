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
if (isset($_POST['identifier'])) {
// check
if (isset($_POST['confirm']) && $_POST['confirm'] == "yes") {
$ident = $_POST['identifier'];
$jsonname = $ident . ".json";
if (file_exists("all_packages/$jsonname")) {
// Alrighty, let's delete.
$debnames = getJSON("debnames");
$thisdebname = $debnames[$ident];
$debdebdeb = $thisdebname . ".deb";
// Delete package info
unlink("all_packages/$jsonname");
// Done, move deb to recycle bin
// check if exists
$end = false;
if (file_exists("recycle_bin/$debdebdeb")) {
$debdebdeb2 = $thisdebname . "_1" . ".deb";
copy("debs/$debdebdeb","recycle_bin/$debdebdeb2");
if (file_exists("recycle_bin/$debdebdeb2")) {
unlink("debs/$debdebdeb");
}
$end = true;
}
if ($end != true) {
copy("debs/$debdebdeb","recycle_bin/$debdebdeb");
if (file_exists("recycle_bin/$debdebdeb")) {
unlink("debs/$debdebdeb");
}
}
// Ok, deleted deb, now remove from package_groups
$package_groups = getJSON("package_groups");
unset($package_groups[$thisdebname]);
saveJSON("package_groups",$package_groups);
// Alright, removed from package_groups, now remove from debnames
$debnames2 = getJSON("debnames");
unset($debnames2[$ident]);
saveJSON("debnames",$debnames2);
// This is where we'd delete the depiction, if that's ok
// Ok, removed from debnames. Now we're done, show success message.
echo "Successfully deleted $ident!";
} else {
echo "That doesn't exist!";
exit();
}
if ($_POST['depictiondelete'] === "yes") {
// if they said yes, delete from depiction
$desc_names = getJSON("descriptions/names");
$desc_desc = getJSON("descriptions/description");
$identifier = $_POST['identifier'];
unset($desc_names[$identifier]);
saveJSON("descriptions/names",$desc_names);
unset($desc_desc[$identifier]);
saveJSON("descriptions/description",$desc_desc);
}
} else {
echo "Mmm, be sure to confirm it.";
}
} else { 
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>Delete File</title></head><body>
    <header><h1>Delete File</h1></header>
    <h2>Fill out this form...</h2>
		<ul><li><p><form action="" method="post">
Identifier: <input type="text" name="identifier" autocapitalize="off"><br>
Delete depiction? <input type="text" name="depictiondelete"><br>
Are you sure? Type 'yes' in the box: <input type="text" name="confirm"><br><br>
<input type="submit" value="Submit">
</form></p></li></ul>
</body>
</html>
<?php } else { ?>
<form action="" method="post">
Identifier: <input type="text" name="identifier"><br>
Delete depiction? (yes/no) <input type="text" name="depictiondelete"><br>
Are you sure? Type 'yes' in the box: <input type="text" name="confirm"><br>
<input type="submit" value="Submit">
</form>
<?php }
}
} else {
$authurl = $CurrentDirectory . "auth.php";
header("Location: $authurl");
}
?>