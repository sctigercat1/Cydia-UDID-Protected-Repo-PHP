<?php
session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE); // no warnings
include("./usergroups.php");
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
// Only admins.
if (empty($_SESSION[$RootSession])) {
$_SESSION[$RootSession] = false;
}
$session = $_SESSION[$RootSession];
if ($session === true) {
// Integrity checks
if (!isset($_GET['identifier'])) {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>Change File</title></head><body>
    <header><h1>Change File</h1></header>
    <h2>Uh oh!</h2>
		<ul><li><p>You need an identifier to do this. Add ?identifier=[identifier] to the end of this url. You can visit <a href='allFiles.php'>this link</a> to see all identifiers.</p></li></ul>
</body>
</html>
<?php } else { 
echo "You need an identifier to do this. Add ?identifier=[identifier] to the end of this url. You can visit <a href='allFiles.php'>this link</a> to see all identifiers.";
}
exit();
} else {
$ident = $_GET['identifier'];
$ident_json = $ident . ".json";
//$ident_json = $ident;
}
if (isset($_GET['identifier']) && !file_exists("all_packages/$ident_json")) {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>Change File</title></head><body>
    <header><h1>Change File</h1></header>
    <h2>Uh oh!</h2>
		<ul><li><p>That identifier doesn't exist!</p></li></ul>
</body>
</html>
<?php } else { 
echo "That identifier doesn't exist!";
}
exit();
}
// Find out what already exists - this really needs to be rewritten (and it will be soon enough!)
////////////////////////////////////
$ident_json = $ident . ".json";
$already_exists_base = getJSON("all_packages/$ident");
$debnamebase = getJSON("debnames");
$old_identifier = $already_exists_base['Package'];
$old_debname = $debnamebase[$old_identifier];
$permissionbase = getJSON("package_groups");
$old_permission = $permissionbase[$old_debname];
$old_package = $already_exists_base['Package'];
$old_md5sum = $already_exists_base['MD5Sum'];
$old_maintainer = $already_exists_base['Maintainer'];
$old_section = $already_exists_base['Section'];
$old_author = $already_exists_base['Author'];
$old_version = $already_exists_base['Version'];
$old_arch = $already_exists_base['Architecture'];
$old_size = $already_exists_base['Size'];
$old_installedsize = $already_exists_base['Installed-Size'];
$old_name = $already_exists_base['Name'];
$old_depiction = $already_exists_base['Depiction'];
$old_priority = $already_exists_base['Priority'];
$old_tag = $already_exists_base['Tag'];
$old_filename = $already_exists_base['Filename'];
$old_description = $already_exists_base['Description'];
$old_depends = $already_exists_base['Depends'];
$old_conflicts = $already_exists_base['Conflicts'];
$old_replaces = $already_exists_base['Replaces'];
////////////////////////////////////
if (isset($_POST['identifier'])) {
$identifier = $_POST['identifier'];
$package = $_POST['identifier'];
$debname = $_POST['debname'];
$md5sum = $_POST['md5sum'];
$maintainer = $_POST['maintainer'];
$section = $_POST['section'];
$author = $_POST['author'];
$version = $_POST['version'];
$arch = $_POST['arch'];
$size = $_POST['size'];
$installedsize = $_POST['installedsize'];
$name = $_POST['name'];
$depiction = $_POST['depiction'];
$priority = $_POST['priority'];
$tag = $_POST['tag'];
$debnameplusdeb = $debname . ".deb";
$filename = "./debs/$debnameplusdeb";
$description = $_POST['description'];
$permission = $_POST['permission'];
$depends = $_POST['depends'];
$conflicts = $_POST['conflicts'];
$replaces = $_POST['replaces'];
$olddebnames = getJSON("debnames");
$newdebmame = array($identifier => $debname);
$merged_deb_array = array_merge($olddebnames,$newdebmame);
saveJSON("debnames",$merged_deb_array);
// Ok, done with debs, now permission
$oldpermissions = getJSON("package_groups");
$newpermission = array($debname => $permission);
$mergedpermissions = array_merge($oldpermissions,$newpermission);
saveJSON("package_groups",$mergedpermissions);
// Ok, done with permissions. Now add package json info
$thisnewpackageinfo = array("MD5Sum" => $md5sum,
"Maintainer" => $maintainer,
"Description" => $description,
"Package" => $package,
"Section" => $section,
"Author" => $author,
"Filename" => $filename,
"Version" => $version,
"Architecture" => $arch,
"Size" => $size,
"Installed-Size" => $installedsize,
"Name" => $name,
"Depiction" => $depiction,
"Priority" => $priority,
"Tag" => $tag,
"Depends" => $depends,
"Replaces" => $replaces,
"Conflicts" => $conflicts);
saveJSON("all_packages/$identifier",$thisnewpackageinfo);
// Done with package json, now check if identifier changed
if ($_GET['identifier'] != $_POST['identifier']) {
$oldident = $_GET['identifier'];
$newident = $_POST['identifier'];
// Delete all rements of old identifier
$debnames = getJSON("debnames");
$desc_names = getJSON("descriptions/names");
$desc_desc = getJSON("descriptions/description");
// Start with debnames
unset($debnames[$oldident]);
saveJSON("debnames",$debnames);
// desc_names
$theoldname = $desc_names[$oldident];
unset($desc_names[$oldident]);
$new_desc_names = array($newident => $theoldname);
$merged_desc_names = array_merge($new_desc_names,$desc_names);
saveJSON("descriptions/names",$merged_desc_names);
// desc_desc
$theolddesc = $desc_desc[$oldident];
unset($desc_desc[$oldident]);
$new_desc_desc = array($newident => $theolddesc);
$merged_desc_desc = array_merge($new_desc_desc,$desc_desc);
saveJSON("descriptions/description",$merged_desc_desc);
// all_packages
$oldjsonidentname = $oldident . ".json";
unlink("all_packages/$oldjsonidentname");
// Ok, now we removed all old identifiers.
}
if ($old_debname != $debname) {
// Remove all rements of old debname
$package_groups = getJSON("package_groups");
unset($package_groups[$old_debname]);
saveJSON("package_groups",$package_groups);
}
echo "File edited!";
} else {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>Change File</title></head><body>
    <header><h1>Change File</h1></header>
<script type = "text/javascript">
function copyIt() {
var x = document.getElementByName("identifier").value;
document.getElementByName("depiction").value = "<?php echo $old_depiction; ?>"+x;
}
</script>
    <h2>Fill out this form...</h2>
		<ul><li><form action='' method='post'>
Identifier: <input type="text" name="identifier" value="<?php echo $old_identifier; ?>" onkeyup="copyIt()"><br>
Deb Name: <input type="text" name="debname" value="<?php echo $old_debname; ?>"><br>
MD5Sum: <input type="text" name="md5sum" value="<?php echo $old_md5sum; ?>"><br>
Maintainer: <input type="text" name="maintainer" value="Me">
Section: <input type="text" name="section" value="<?php echo $old_section; ?>"><br>
Author: <input type="text" name="author" value="<?php echo $old_author; ?>"><br>
Version: <input type="text" name="version" value="<?php echo $old_version; ?>"><br>
Architecture: <input type="text" name="arch" value="<?php echo $old_arch; ?>"><br>
Description: <input type="text" name="description" value="<?php echo $old_description; ?>"><br>
Size: <input type="text" name="size" value="<?php echo $old_size; ?>"><br>
Installed-Size: <input type="text" name="installedsize" value="<?php echo $old_installedsize; ?>"><br>
Name: <input type="text" name="name" value="<?php echo $old_name; ?>"><br>
Depiction: <input type="text" name="depiction" value="<?php echo $old_depiction; ?>"><br>
Priority: <input type="text" name="priority" value="<?php echo $old_priority; ?>"><br>
Depends: <input type="text" name="depends" value="<?php echo $old_depends; ?>"><br>
Conflicts: <input type="text" name="conflicts" value="<?php echo $old_conflicts; ?>"><br>
Replaces: <input type="text" name="replaces" value="<?php echo $old_replaces; ?>"><br>
Tag (can leave blank): <input type="text" name="tag" value="<?php echo $old_tag; ?>"><br>
Permission Level: <select name="permission"><option <?php if ($old_permission === 0) { echo "selected"; } ?> value="0">Level 0</option><option <?php if ($old_permission === 1) { echo "selected"; } ?> value="1">Level 1</option><option <?php if ($old_permission === 2) { echo "selected"; } ?> value="2">Level 2</option><option <?php if ($old_permission === 3) { echo "selected"; } ?> value="3">Level 3</option><option <?php if ($old_permission === 4) { echo "selected"; } ?> value="4">Level 4</option></select><br>
<input type='submit' value="Submit">
</form></li></ul>
</body>
</html>
<?php } else { ?>
<script type = "text/javascript">
function copyItTwo() {
var x = document.getElementByName("identifier").value;
document.getElementByName("depiction").value = "<?php echo $old_depiction; ?>"+x;
}
</script>
<form action='' method='post'>
Identifier: <input type="text" name="identifier" value="<?php echo $old_identifier; ?>" onkeyup="copyItTwo()"><br>
Deb Name: <input type="text" name="debname" value="<?php echo $old_debname; ?>"><br>
MD5Sum: <input type="text" name="md5sum" value="<?php echo $old_md5sum; ?>"><br>
Maintainer: <input type="text" name="maintainer" value="Me">
Section: <input type="text" name="section" value="<?php echo $old_section; ?>"><br>
Author: <input type="text" name="author" value="<?php echo $old_author; ?>"><br>
Version: <input type="text" name="version" value="<?php echo $old_version; ?>"><br>
Architecture: <input type="text" name="arch" value="<?php echo $old_arch; ?>"><br>
Description: <input type="text" name="description" value="<?php echo $old_description; ?>"><br>
Size: <input type="text" name="size" value="<?php echo $old_size; ?>"><br>
Installed-Size: <input type="text" name="installedsize" value="<?php echo $old_installedsize; ?>"><br>
Name: <input type="text" name="name" value="<?php echo $old_name; ?>"><br>
Depiction: <input type="text" name="depiction" value="<?php echo $old_depiction; ?>"><br>
Priority: <input type="text" name="priority" value="<?php echo $old_priority; ?>"><br>
Depends: <input type="text" name="depends" value="<?php echo $old_depends; ?>"><br>
Conflicts: <input type="text" name="conflicts" value="<?php echo $old_conflicts; ?>"><br>
Replaces: <input type="text" name="replaces" value="<?php echo $old_replaces; ?>"><br>
Tag (can leave blank): <input type="text" name="tag" value="<?php echo $old_tag; ?>"><br>
Permission Level: <select name="permission"><option <?php if ($old_permission == "0") { echo "selected"; } ?> value="0">Level 0</option><option <?php if ($old_permission == "1") { echo "selected"; } ?> value="1">Level 1</option><option <?php if ($old_permission == "2") { echo "selected"; } ?> value="2">Level 2</option><option <?php if ($old_permission == "3") { echo "selected"; } ?> value="3">Level 3</option><option <?php if ($old_permission == "4") { echo "selected"; } ?> value="4">Level 4</option></select><br>
<input type='submit' value="Submit">
</form>
<?php }
 }
} else {
$authurl = $CurrentDirectory . "auth.php";
header("Location: $authurl");
}
?>
