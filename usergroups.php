<?php
// Permission levels; 0-4 (this can't really be changed)
// This, at the moment, has no official use, but is displayed on the depiction pages.
$PermissionLevels = array(
0 => "Guest", // Public
1 => "Beta User", // Beta
2 => "Special Beta User", // Special Beta
3 => "Friend", // Friend
4 => "Admin");
// Beta Mode is where guests wouldn't be able to see the repo, you would have to have a minimum usergroup as defined with $LowestBetaModeUsergroup
// First, you don't need to edit these next two lines.
$BetaModeFromJSON = json_decode(file_get_contents(realpath(__DIR__ . "/beta_mode.json")), true);
$BetaMode = $BetaModeFromJSON[0];
// Second, define the lowest beta mode group. Here's an example: required level is 1; if user is 0 (not assigned), they can't go in, but if they're 1, they can.
$LowestBetaModeUsergroup = "1";
// This is what will be displayed in the prompt after adding. It doesn't always work, but it may occasionally.
$BetaModeNotAllowedHeader = "Sorry, only approved users can access this repo.";
// $CurrentDirectory is the current directory of your repo. It'll look something like this: http://www.example.com/repo/
// If you don't have PHP5, you'll need to manually add your url below (see README for the correct code to use), but if you do have PHP5, you don't need to edit the next two variables.
$CurrentDirectory = "http://".$_SERVER['HTTP_HOST']."/".basename(__DIR__)."/";
// This is the current folder the repo's in. It would be /repo/ if using the example in the comment above. If root, the code below would most likely return something like /public_html/ or /www/
// Again, if you have PHP5, it's automatically filled out for you.
// Also, UPDATE THIS IN HTACCESS; directories cannot be automatically found there, so you'll need to manually adjust it (default htaccess folder is /repo/)
$CurrentDirectoryFolder = "/".basename(__DIR__)."/";
// Page Title: variable below + Repo (so, with this, it would be "My Personal Repo")
$RepoTitleName = "My Personal";
// Database connection; this is how the download counter works
// For now, it'll display an error if it can't connect. I'll add a true/false switch in later, but for now I'd just recommend you use it. Setup the database with the sql file in the repo.
$Database = "localhost";
$DBUser = "root";
$DBPass = "admin";
$DB_DB = "repo_downlads";
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
// This is where we get the approved UDIDs, but it's really only used if beta mode (see above) is active.
$approved_udids = json_decode(file_get_contents(realpath(__DIR__ . "/approved_udids_2.json")), true);
// Create blank arrays for holding
// But first, let's check if there aren't any approved UDIDs in the list.
$UDID = array();
$LEVEL = array();
if ($approved_udids == $UDID) {
// Apply UDIDs and levels from multidimensional into two separate arrays
foreach ($approved_udids as $key => $value) {
$UDID[] = $approved_udids[$key][0];
$LEVEL[] = $approved_udids[$key][1];
}
// Now combine the arrays to get the final
$Users = array_combine($UDID,$LEVEL);
} else {
// Create a blank users array
$Users = array();
}
// Change the session variable names for admins
$RootSession = "repopasscheck521";
$DepictionSession = "repopasscheck521";
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
