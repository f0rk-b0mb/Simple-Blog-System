<?php

ob_start();
session_start();

$db_host = "localhost";
$db_user = "blog";
$db_pass = "Z3@20N]S]sWHpU1g";
$db_name = "linux_engineer";

$dbcon = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

?>