<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "complaint_system";
$port = 3307;

$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
