<?php
session_start();
include '../usergroups.php';
unset($_SESSION[$DepictionSession]);
echo "Unauthenticated!";
$deproot = $CurrentDirectory . "descriptions/";
echo "<meta http-equiv='refresh' content='1; url=$deproot' />";
?>