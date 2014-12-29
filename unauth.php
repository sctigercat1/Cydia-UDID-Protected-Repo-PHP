<?php
session_start();
include './usergroups.php';
unset($_SESSION[$RootSession]);
echo "Unauthenticated!";
$deproot = $CurrentDirectory . "Admin.php";
echo "<meta http-equiv='refresh' content='1; url=$deproot' />";
?>