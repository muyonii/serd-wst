<?php
// Database connection file (shown in Segment 4)
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "serds_demo";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
