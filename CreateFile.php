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
if (isset($_POST['Package'])) {
$allvars = $_POST;
$identifier = $_POST['Package'];
$debname = $_POST['debname'];
$permission = $_POST['permission'];
unset($allvars["depiction_content"]);
unset($allvars["permission"]);
unset($allvars["debname"]);
unset($allvars["deb"]);
$thisnewpackageinfo = array();
foreach ($allvars as $objid => $objval) {
$thisnewpackageinfo[$objid] = $objval;
}
$debnameplusdeb = $_POST["debname"] . ".deb";
$filename = "./debs/$debnameplusdeb";
$thisnewpackageinfo['Filename'] = $filename;
// First update debs
$usedindep = false;
$olddebnames = getJSON("debnames");
$newdebmame = array($identifier => $debname);
$merged_deb_array = array_merge($olddebnames,$newdebmame);
saveJSON("debnames",$merged_deb_array);
// Ok, done with debs, now permission
$oldpermissions = getJSON("package_groups");
$newpermission = array($debname => $permission);
$mergedpermissions = array_merge($oldpermissions,$newpermission);
saveJSON("package_groups",$mergedpermissions);
// Done with permissions
// Now save package json
saveJSON("all_packages/$identifier",$thisnewpackageinfo);
$finalpath = "all_packages/".$identifier.".json";
chmod($finalpath,0777);
// Done with package json, now save the deb from form
if (isset($_FILES['deb'])) {
 $info = pathinfo($_FILES['deb']['name']);
 $ext = $info['extension']; // get the extension of the file
 $newname = $_FILES['deb']['name']; 

 $target = 'debs/'.$newname;
 move_uploaded_file( $_FILES['deb']['tmp_name'], $target);
 // and chmod it
 chmod($target,0777);
}
// Done with saving deb, now check out depiction
if (!empty($_POST['depiction_content'])) {
//////////////////////////////////////////////
$ident = $_POST['Package'];
$name = $_POST['Name'];
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
if (!array_key_exists($ident, $prepName)) {
    $newArrayNames = array($ident => $name);
	$newArrayDescs = array($ident => $desc);
	$newArrayDebs = array($ident => $debnames);
	// name merge
	$finalName = array_merge($prepName,$newArrayNames);
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
$name = $_POST['Name'];
$debnameplusde = $debname . ".deb";
echo "New file added! Now, you need to <a href='descriptions/createDepiction.php?identifier=$identifier&name=$name&debname=$debname'>add its depiction</a> and add the deb file ('debs/$debnameplusde').";
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
$depictionbase = $CurrentDirectory . "descriptions/pages.php?file="; ?>
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
		<ul><li><p>

<form action='' method='post' enctype='multipart/form-data'>            <div class="box">
                <span class="label">Identifier: </span>
                <span class="ib"> <input type="text" name="Package" id="identifier" onkeyup="copyIt()"></span>
            </div>
            <div class="box">
                <span class="label">Deb Name (no .deb): </span>
                <span class="ib"> <input type="text" name="debname">
                </span>
            </div>
            <div class="box">
                <span class="label">MD5Sum:  </span>
                <span class="ib"><input type="text" name="MD5Sum"></span>
            </div>
            <div class="box">
                <span class="label">Maintainer: </span>
                <span class="ib">  <input type="text" name="Maintainer" value="Me"></span>
            </div>
            <div class="box">
                <span class="label">Section: </span>
                <span class="ib"><input type="text" name="Section"></span>
            </div>
            <div class="box">
                <span class="label">Author: </span>
                <span class="ib">  <input type="text" name="Author" value="Me"></span>
            </div>
            <div class="box">
                <span class="label">Version: </span>
                <span class="ib">  <input type="text" name="Version" value="1.0"></span>
            </div>
            <div class="box">
                <span class="label">Discount: </span>
                <span class="ib">  <input type="text" name="discount_per" id="discount_per"/>
                </span></div>
    <div class="box">
                <span class="label">Architecture: </span>
                <span class="ib">  <input type="text" name="Architecture" value="iphoneos-arm">
                </span></div>
    <div class="box">
                <span class="label">Description: </span>
                <span class="ib">  <input type="text" name="Description">
                </span></div>
    <div class="box">
                <span class="label">Size: </span>
                <span class="ib">  <input type="text" name="Size">
                </span></div>
    <div class="box">
                <span class="label">Installed-Size: </span>
                <span class="ib">  <input type="text" name="Installed-Size">
                </span></div>
    <div class="box">
                <span class="label">Depiction: </span>
                <span class="ib">  <input type="text" name="Depiction" value="<?php echo $depictionbase; ?>">
                </span></div>
    <div class="box">
                <span class="label">Priority: </span>
                <span class="ib">  <input type="text" name="Priority" value="optional">
                </span></div>
    <div class="box">
                <span class="label">Depends: </span>
                <span class="ib">  <input type="text" name="Depends" value="">
                </span></div>
    <div class="box">
                <span class="label">Conflicts: </span>
                <span class="ib">  <input type="text" name="Conflicts" value="">
    <div class="box">
                <span class="label">Replaces: </span>
                <span class="ib">  <input type="text" name="Replaces" value="">
                </span></div>
    <div class="box">
                <span class="label">Tag (can leave blank): </span>
                <span class="ib">  <select name="permission"><option value="0">Level 0</option><option value="1">Level 1</option><option value="2">Level 2</option><option value="3">Level 3</option><option value="4">Level 4</option></select>
                </span></div>
    <div class="box">
                <span class="label">Permission Level: </span>
                <span class="ib">  <input type="text" name="discount_per" id="discount_per"/>
                </span></div>
                    <div class="box">
                <span class="label">(optional) Upload deb file: </span>
                <span class="ib">  <input type="file" name="deb" id="deb">
                </span></div>
                    <div class="box">
                <span class="label">Depiction: </span>
                <span class="ib">  <textarea name="depiction_content" id="depiction_content" rows="8" cols="45"></textarea>
                <input type='submit' value="Submit" class="button">
                    <br><br>

        </form>
                        </p></li></ul>
</body>
</html>
<?php } 
?>
<?php }
 else {
$authurl = $CurrentDirectory . "auth.php";
header("Location: $authurl");
}
?>
