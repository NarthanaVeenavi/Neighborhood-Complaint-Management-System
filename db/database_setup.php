<?php
require_once 'db.php';

$sql = file_get_contents('database_setup.sql');

if (mysqli_multi_query($conn, $sql)) {
    echo "Database and tables created successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}