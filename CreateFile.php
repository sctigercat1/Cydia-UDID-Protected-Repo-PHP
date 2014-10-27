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
$usedindep = false;
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
"Name" => $name,
"Depiction" => $depiction,
"Priority" => $priority,
"Tag" => $tag,
"Depends" => $depends,
"Replaces" => $replaces,
"Conflicts" => $conflicts);
saveJSON("all_packages/$identifier",$thisnewpackageinfo);
$finalpath = "all_packages/".$identifier.".json";
chmod ($finalpath,0777);
// Done with package json, now save the deb from form
if (!empty($_FILES["deb"])) {
    // get extension
    $temp = explode(".", $_FILES["deb"]["name"]);
    $extension = end($temp);
	if ($extension === "deb") {
	// only debs
      if (file_exists("debs/" . $_FILES["deb"]["name"])) {
        echo "That deb already exists! Sigh...<br>";
      } else {
        move_uploaded_file($_FILES["deb"]["tmp_name"],
        "debs/" . $_FILES["deb"]["name"]);
      }
    }
}
// Done with saving deb, now check out depiction
if (!empty($_POST['depiction_content'])) {
//////////////////////////////////////////////
$ident = $_POST['identifier'];
$name = $_POST['name'];
$desc = $_POST['depiction_content'];
$debnames = $_POST['debname'];
$originalArrayNames = getJSON("descriptions/names");
$originalArrayDescs = getJSON("descriptions/description");
$originalArrayDebs = getJSON("debnames");
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
	saveJSON("descriptions/names",$finalName);
	// description merge
	$finalDesc = array_merge($prepDesc,$newArrayDescs);
	saveJSON("descriptions/description",$finalDesc);
	// deb merge
	$finalDebs = array_merge($prepDebs,$newArrayDebs);
	saveJSON("debnames",$finalDebs);
	$usedindep = true;
} else {
echo "Depiction already exists.<br><br>";
}
//////////////////////////////////////////////
}
if ($usedindep === false) {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title><?php echo $RepoTitleName; ?> Repo</title></head><body>
    <header><h1>Create File</h1></header>
    <h2>Done!</h2>
		<ul><li><p>New file added! Now, you need to <a href='descriptions/createDepiction.php?identifier=<?php echo $identifier; ?>&name=<?php echo $name; ?>&debname=<?php echo $debname; ?>'>add its depiction</a> and add the deb file ("debs/<?php echo $debname; ?>").</p></li></ul>
</body>
</html>
<?php } else {
echo "New file added! Now, you need to <a href='descriptions/createDepiction.php?identifier=$identifier&name=$name&debname=$debname'>add its depiction</a> and add the deb file ('deb/$debname').";
}
} else {
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title><?php echo $RepoTitleName; ?> Repo</title></head><body>
    <header><h1>Create File</h1></header>
    <h2>Done!</h2>
		<ul><li><p>New file added! If you haven't already, go ahead and add the deb to /debs.</p></li></ul>
</body>
</html>
<?php } else {
echo "New file added! If you haven't already, go ahead and add the deb to /debs. <a href='CreateFile.php'>Add another one</a>.";
}
}
} else {
$depictionbase = $CurrentDirectory . "descriptions/pages.php?file=";
if( $detect->isiOS() ){ ?>
<!DOCTYPE html>
<html>
<head>
    <link href="ios7css.css" rel="stylesheet">
    <meta content="width=device-width, user-scalable=no" name="viewport">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"><title>Create File</title></head><body>
<!--	<ul><li><p>Hello, <?php // echo $_SERVER["HTTP_X_MACHINE"]; ?>!</p></li></ul> -->
    <header><h1>Create File</h1></header>
    <h2>Fill out this form...</h2>
<script type = "text/javascript">
function copyIt() {
var x = document.getElementByName("identifier").value;
document.getElementByName("depiction").value = "<?php echo $depictionbase; ?>"+x;
}
</script>
		<ul><li><p><form action='' method='post'>
Identifier: <input type="text" name="identifier" onkeyup="copyIt()"><br>
Deb Name (no .deb): <input type="text" name="debname"><br>
MD5Sum: <input type="text" name="md5sum"><br>
Maintainer: <input type="text" name="maintainer" value="Me">
Section: <input type="text" name="section"><br>
Author: <input type="text" name="author" value="Me"><br>
Version: <input type="text" name="version" value="1.0"><br>
Architecture: <input type="text" name="arch" value="iphoneos-arm"><br>
Description: <input type="text" name="description"><br>
Size: <input type="text" name="size"><br>
Name: <input type="text" name="name"><br>
Depiction: <input type="text" name="depiction" value="<?php echo $depictionbase; ?>"><br>
Priority: <input type="text" name="priority" value="optional"><br>
Depends: <input type="text" name="depends" value=""><br>
Conflicts: <input type="text" name="conflicts" value=""><br>
Replaces: <input type="text" name="replaces" value=""><br>
Tag (can leave blank): <input type="text" name="tag"><br>
Permission Level: <select name="permission"><option value="0">Level 0</option><option value="1">Level 1</option><option value="2">Level 2</option><option value="3">Level 3</option><option value="4">Level 4</option></select><br>
(optional) Upload deb file: <input type="file" name="deb" id="deb"><br>
Finally, if you want to add a dipiction here, go ahead and type it below:<br>
<textarea name="depiction_content" id="depiction_content" rows="8" cols="45"></textarea><br>
<input type='submit' value="Submit">
</form><br></p></li></ul>
</body>
</html>
<?php } else { ?>
<script type = "text/javascript">
function copyItTwo() {
var x = document.getElementById("identifier").value;
document.getElementById("depiction").value = "<?php echo $depictionbase; ?>"+x;
}
</script>
<form action='' method='post'>
Identifier: <input type="text" name="identifier" id="identifier" onkeyup="copyItTwo()"><br>
Deb Name (no .deb) <input type="text" name="debname"><br>
MD5Sum: <input type="text" name="md5sum"><br>
Maintainer: <input type="text" name="maintainer" value="Me">
Section: <input type="text" name="section"><br>
Author: <input type="text" name="author" value="Me"><br>
Version: <input type="text" name="version" value="1.0"><br>
Architecture: <input type="text" name="arch" value="iphoneos-arm"><br>
Description: <input type="text" name="description"><br>
Size: <input type="text" name="size"><br>
Name: <input type="text" name="name"><br>
Depiction: <input type="text" name="depiction" id="depiction" value="<?php echo $depictionbase; ?>"><br>
Priority: <input type="text" name="priority" value="optional"><br>
Depends: <input type="text" name="depends" value=""><br>
Conflicts: <input type="text" name="conflicts" value=""><br>
Replaces: <input type="text" name="replaces" value=""><br>
Tag (can leave blank): <input type="text" name="tag"><br>
Permission Level: <select name="permission"><option value="0">Level 0</option><option value="1">Level 1</option><option value="2">Level 2</option><option value="3">Level 3</option><option value="4">Level 4</option></select><br>
(optional) Upload deb file: <input type="file" name="deb" id="deb"><br>
Finally, if you want to add a dipiction here, go ahead and type it below:<br>
<textarea name="depiction_content" id="depiction_content" rows="8" cols="50"></textarea><br>
<input type='submit' value="Submit">
</form><br>
<?php }
?>
<?php }
} else {
$authurl = $CurrentDirectory . "auth.php";
header("Location: $authurl");
}
?>
