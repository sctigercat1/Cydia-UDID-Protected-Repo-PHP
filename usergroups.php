<?php
// Permission levels; 0-4 (this can't really be changed)
// This, at the moment, has no official use.
$PermissionLevels = array(
0 => "Guest", // Public
1 => "Beta User", // Beta
2 => "Special Beta User", // Special Beta
3 => "Friend", // Friend
4 => "Admin");
// Users; this is where all UDIDs will be located along with their usergroup
$Users = array(
'sampleudid1' => 4,
'sampleudid2' => 1);
// Set to require a minimum usergroup (true/false).
// NOTE: $BetaMode controls all of this, so if it's set to false none of the betamode variables would matter.
$BetaMode = true;
// Example: required level is 1; if user is 0 (not assigned), they can't go in, but if they're 1, they can.
$LowestBetaModeUsergroup = "1"; // Only beta users
// This is what will be displayed in the prompt after adding
$BetaModeNotAllowedHeader = "Sorry, only approved users can access this repo.";
// This marks the current directory. INCLUDE TRAILING SLASH AT END OF DIRECTORY * Also change in .htaccess
$CurrentDirectory = "http://www.example.com/repo/";
// This is just the folder it's in. If it is in a root, do "/"
$CurrentDirectoryFolder = "/repo/";
// Page Title: variable below + Repo
$RepoTitleName = "My Personal";
// Database connection; this is how the download counter works
$Database = "localhost";
$DBUser = "root";
$DBPass = "admin";
$DB_DB = "repo_downlads";
// Change the session variable names for admins
// You can make these different, but for now they're the same.
//$RootSession = "rootpasscorrect";
//$DepictionSession = "passcorrect";
$RootSession = "repopasscheck";
$DepictionSession = "repopasscheck";
// Custom Login Password for Admin interfaces
$RootPassword = "password";
$DepictionPassword = "password";
// This variable is what the page will print if someone tries to access Packages or Release from something other than cydia
$BadUserError = "erp";
/////////////////  RELEASE  /////////////////
// Name of repo
$RepoName = "My Personal Repo";
// Repo's label
$RepoLabel = "MyRepo";
// Repo Version
$RepoVersion = "1.0";
// Repo Description
$RepoDescription = "My Personal UDID Protected Repo!";
// OTHER THINGS: MOST USERS DON'T NEED TO EDIT THESE //
// Repo Suite
$RepoSuite = "stable";
// Repo Codename
$RepoCodename = "stable";
// Repo Architectures
$RepoArchitectures = "darwin-arm";
// Repo Components
$RepoComponents = "main";
/////////////////  END RELEASE  /////////////////
// Done editing!
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Functions
function saveJSON($name,$array) {
  $name = $name . ".json";
  file_put_contents($name,json_encode($array));
}
function getJSON($name) {
  $name = $name . ".json";
  $arr2 = json_decode(file_get_contents($name), true);
  return $arr2;
}
function deleteJSON($name) {
  $name = $name . ".json";
  unlink($name);
}
?>