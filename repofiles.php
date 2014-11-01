<?php
include './usergroups.php';
if (!isset($_GET['request'])) {
echo "Error: no GET request";
exit();
}
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
$request = $_GET['request'];
if (isset($request)) {
if (isset($_SERVER["HTTP_X_UNIQUE_ID"])) {
// If Cydia
// First, assign the variable $usergroup with the current user's group
$udid = $_SERVER["HTTP_X_UNIQUE_ID"];
$alludids = array_keys($Users);
if (in_array($udid,$alludids)) {
$usergroup = $Users[$udid];
} else {
$usergroup = 0;
}
// Check if user can access it if betamode is on
if ($BetaMode === true && $LowestBetaModeUsergroup > $usergroup) {
header("HTTP/1.0 403 Forbidden: $BetaModeNotAllowedHeader");
exit();
}
///////////////////////////////////////////////////////////////////////////////////
// Next, onward to Packages(.bz2)
$debpermission = getJSON("package_groups");
$ZeroPackages = array();
$OnePackages = array();
$TwoPackages = array();
$ThreePackages = array();
$FourPackages = array();
foreach($debpermission as $deb => $group) {
if ($group == 0) {
$ZeroPackages[] = $deb;
//echo "added 0 successfully";
}
if ($group == 1) {
$OnePackages[] = $deb;
//echo "added 1 successfully";
}
if ($group == 2) {
$TwoPackages[] = $deb;
//echo "added 2 successfully";
}
if ($group == 3) {
$ThreePackages[] = $deb;
//echo "added 3 successfully";
}
if ($group == 4) {
$FourPackages[] = $deb;
//echo "added 4 successfully";
}
}
$debnames = getJSON("debnames");
$finalfile = "";
$debnames = array_flip($debnames);
if ($usergroup >= 0) {
foreach ($ZeroPackages as $package) {
$thisident = $debnames[$package];
$package_stuff = getJSON("all_packages/$thisident");
$MD5Sum = $package_stuff["MD5Sum"];
$Maintainer = $package_stuff["Maintainer"];
$Description = $package_stuff["Description"];
$Package = $package_stuff["Package"];
$Section = $package_stuff["Section"];
$Author = $package_stuff["Author"];
$Filename = $package_stuff["Filename"];
$Version = $package_stuff["Version"];
$Architecture = $package_stuff["Architecture"];
$Size = $package_stuff["Size"];
$Name = $package_stuff["Name"];
$Depiction = $package_stuff["Depiction"];
$Priority = $package_stuff["Priority"];
$Tag = $package_stuff["Tag"];
$Depends = $package_stuff["Depends"];
$Replaces = $package_stuff["Replaces"];
$Conflicts = $package_stuff["Conflicts"];
$finalfile .= "MD5Sum: $MD5Sum
Maintainer: $Maintainer
Description: $Description
Package: $Package
Section: $Section
Author: $Author
Filename: $Filename
Version: $Version
Architecture: $Architecture
Size: $Size
Name: $Name
Depiction: $Depiction
";
if (!empty($Tag)) {
$finalfile .= "Tag: $Tag
";
}
if (!empty($Depends)) {
$finalfile .= "Depends: $Depends
";
}
if (!empty($Replaces)) {
$finalfile .= "Replaces: $Replaces
";
}
if (!empty($Conflicts)) {
$finalfile .= "Conflicts: $Conflicts
";
}
$finalfile .= "Priority: $Priority";
$finalfile .= "

";
}
}
//////////////
if ($usergroup >= 1) {
foreach ($OnePackages as $package) {
$thisident = $debnames[$package];
$package_stuff = getJSON("all_packages/$thisident");
$MD5Sum = $package_stuff["MD5Sum"];
$Maintainer = $package_stuff["Maintainer"];
$Description = $package_stuff["Description"];
$Package = $package_stuff["Package"];
$Section = $package_stuff["Section"];
$Author = $package_stuff["Author"];
$Filename = $package_stuff["Filename"];
$Version = $package_stuff["Version"];
$Architecture = $package_stuff["Architecture"];
$Size = $package_stuff["Size"];
$Name = $package_stuff["Name"];
$Depiction = $package_stuff["Depiction"];
$Priority = $package_stuff["Priority"];
$Tag = $package_stuff["Tag"];
$Depends = $package_stuff["Depends"];
$Replaces = $package_stuff["Replaces"];
$Conflicts = $package_stuff["Conflicts"];
$finalfile .= "MD5Sum: $MD5Sum
Maintainer: $Maintainer
Description: $Description
Package: $Package
Section: $Section
Author: $Author
Filename: $Filename
Version: $Version
Architecture: $Architecture
Size: $Size
Name: $Name
Depiction: $Depiction
";
if (!empty($Tag)) {
$finalfile .= "Tag: $Tag
";
}
if (!empty($Depends)) {
$finalfile .= "Depends: $Depends
";
}
if (!empty($Replaces)) {
$finalfile .= "Replaces: $Replaces
";
}
if (!empty($Conflicts)) {
$finalfile .= "Conflicts: $Conflicts
";
}
$finalfile .= "Priority: $Priority";
$finalfile .= "

";
}
}
/////////
if ($usergroup >= 2) {
foreach ($TwoPackages as $package) {
$thisident = $debnames[$package];
$package_stuff = getJSON("all_packages/$thisident");
$MD5Sum = $package_stuff["MD5Sum"];
$Maintainer = $package_stuff["Maintainer"];
$Description = $package_stuff["Description"];
$Package = $package_stuff["Package"];
$Section = $package_stuff["Section"];
$Author = $package_stuff["Author"];
$Filename = $package_stuff["Filename"];
$Version = $package_stuff["Version"];
$Architecture = $package_stuff["Architecture"];
$Size = $package_stuff["Size"];
$Name = $package_stuff["Name"];
$Depiction = $package_stuff["Depiction"];
$Priority = $package_stuff["Priority"];
$Tag = $package_stuff["Tag"];
$Depends = $package_stuff["Depends"];
$Replaces = $package_stuff["Replaces"];
$Conflicts = $package_stuff["Conflicts"];
$finalfile .= "MD5Sum: $MD5Sum
Maintainer: $Maintainer
Description: $Description
Package: $Package
Section: $Section
Author: $Author
Filename: $Filename
Version: $Version
Architecture: $Architecture
Size: $Size
Name: $Name
Depiction: $Depiction
";
if (!empty($Tag)) {
$finalfile .= "Tag: $Tag
";
}
if (!empty($Depends)) {
$finalfile .= "Depends: $Depends
";
}
if (!empty($Replaces)) {
$finalfile .= "Replaces: $Replaces
";
}
if (!empty($Conflicts)) {
$finalfile .= "Conflicts: $Conflicts
";
}
$finalfile .= "Priority: $Priority";
$finalfile .= "

";
}
}
///////////
if ($usergroup >= 3) {
foreach ($ThreePackages as $package) {
$thisident = $debnames[$package];
$package_stuff = getJSON("all_packages/$thisident");
$MD5Sum = $package_stuff["MD5Sum"];
$Maintainer = $package_stuff["Maintainer"];
$Description = $package_stuff["Description"];
$Package = $package_stuff["Package"];
$Section = $package_stuff["Section"];
$Author = $package_stuff["Author"];
$Filename = $package_stuff["Filename"];
$Version = $package_stuff["Version"];
$Architecture = $package_stuff["Architecture"];
$Size = $package_stuff["Size"];
$Name = $package_stuff["Name"];
$Depiction = $package_stuff["Depiction"];
$Priority = $package_stuff["Priority"];
$Tag = $package_stuff["Tag"];
$Depends = $package_stuff["Depends"];
$Replaces = $package_stuff["Replaces"];
$Conflicts = $package_stuff["Conflicts"];
$finalfile .= "MD5Sum: $MD5Sum
Maintainer: $Maintainer
Description: $Description
Package: $Package
Section: $Section
Author: $Author
Filename: $Filename
Version: $Version
Architecture: $Architecture
Size: $Size
Name: $Name
Depiction: $Depiction
";
if (!empty($Tag)) {
$finalfile .= "Tag: $Tag
";
}
if (!empty($Depends)) {
$finalfile .= "Depends: $Depends
";
}
if (!empty($Replaces)) {
$finalfile .= "Replaces: $Replaces
";
}
if (!empty($Conflicts)) {
$finalfile .= "Conflicts: $Conflicts
";
}
$finalfile .= "Priority: $Priority";
$finalfile .= "

";
}
}
//////////
if ($usergroup >= 4) {
foreach ($FourPackages as $package) {
$thisident = $debnames[$package];
$package_stuff = getJSON("all_packages/$thisident");
$MD5Sum = $package_stuff["MD5Sum"];
$Maintainer = $package_stuff["Maintainer"];
$Description = $package_stuff["Description"];
$Package = $package_stuff["Package"];
$Section = $package_stuff["Section"];
$Author = $package_stuff["Author"];
$Filename = $package_stuff["Filename"];
$Version = $package_stuff["Version"];
$Architecture = $package_stuff["Architecture"];
$Size = $package_stuff["Size"];
$Name = $package_stuff["Name"];
$Depiction = $package_stuff["Depiction"];
$Priority = $package_stuff["Priority"];
$Tag = $package_stuff["Tag"];
$Depends = $package_stuff["Depends"];
$Replaces = $package_stuff["Replaces"];
$Conflicts = $package_stuff["Conflicts"];
$finalfile .= "MD5Sum: $MD5Sum
Maintainer: $Maintainer
Description: $Description
Package: $Package
Section: $Section
Author: $Author
Filename: $Filename
Version: $Version
Architecture: $Architecture
Size: $Size
Name: $Name
Depiction: $Depiction
";
if (!empty($Tag)) {
$finalfile .= "Tag: $Tag
";
}
if (!empty($Depends)) {
$finalfile .= "Depends: $Depends
";
}
if (!empty($Replaces)) {
$finalfile .= "Replaces: $Replaces
";
}
if (!empty($Conflicts)) {
$finalfile .= "Conflicts: $Conflicts
";
}
$finalfile .= "Priority: $Priority";
$finalfile .= "

";
}
}
file_put_contents("Packages",$finalfile);
chmod("Packages",0777);
$fileconent = file_get_contents("Packages");
$bz = bzopen("Packages.bz2", "w");
bzwrite($bz, $fileconent);
bzclose($bz);
chmod("Packages.bz2",0777);
//////
$request = $_GET["request"];
	$extension = pathinfo($request, PATHINFO_EXTENSION);
	if ($extension) {
		$mimemap = array(
			"bz2" => "application/bzip2",
			"gz" => "application/x-gzip",
			"xz" => "application/x-xz",
			"lzma" => "application/x-lzma",
			"lz" => "application/x-lzip");
		
		header(sprintf("Content-Type: %s", $mimemap[$extension]));
		header(sprintf("Content-Disposition: attachment; filename=\"%s\"", $request));
	}
	$docroot = $_SERVER['DOCUMENT_ROOT'] . $CurrentDirectoryFolder;
	if ($request == $docroot . "Packages" || $request == $docroot . "Release" || $request == $docroot . "Packages.bz2" || $request == $docroot . "Packages.gz" || $request == $docroot . "en_US.bz2" || $request == $docroot . "en_US.gz") {
	echo readfile($request);
	} else {
	header("HTTP/1.0 403 Forbidden: Someone's sneaky.");
	}
} else {
// If Computer
header('HTTP/1.0 403 Forbidden');
echo $BadUserError;
}
// Finally, Release.
//////////////////////////////////////////////////////////////////////////////////
// Get md5/sizes of packages and packages.bz2
// This makes it a little more secure ;)
$packagesize = filesize("Packages");
$packagebz2size = filesize("Packages.bz2");
$packagemd5 = md5_file("Packages");
$packagebz2md5 = md5_file("Packages.bz2");
$release = "Origin: $RepoName
Label: $RepoLabel
Suite: $RepoSuite 
Version: $RepoVersion
Codename: $RepoCodename
Architectures: $RepoArchitectures 
Components: $RepoComponents 
Description: $RepoDescription
MD5sum:
 $packagemd5 $packagesize Packages
 $packagebz2md5 $packagebz2size Packages.bz2
";
if (isset($_SERVER["HTTP_X_UNIQUE_ID"])) { 
$myfile = fopen("Release", "w") or die("Unable to open file!");
fwrite($myfile, $release);
fclose($myfile);
chmod("Release",0777);
}
///////////////////////////////////////////////////////////////////////////////////
}
?>
