<?php
include '../db/db.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM complaints WHERE id=$id");

header("Location: list_my_complaints.php");
?>
