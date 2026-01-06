<?php
$servername = "localhost";
$username = "root";
$password = "root";  // <- put the password you set
$dbname = "neighbor_complaints";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Database connected successfully!";
?>
