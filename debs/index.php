<?php
include '../usergroups.php';
// Don't let others access this directory
header("Location: $CurrentDirectory");
?>