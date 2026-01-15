<?php
session_start();
require_once '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/list_my_complaints.php");
    exit();
}

$complaint_id = $_POST['complaint_id'];
$title = $_POST['complaint_title'];
$category = $_POST['category'];
$description = $_POST['complaint'];
$location = $_POST['apartment_id'];
$incident_date = $_POST['incident_date'];
$priority = $_POST['priority'];

// Handle file upload (optional)
$attachment = null;
if (!empty($_FILES['attachment']['name'])) {
    $filename = time() . '_' . $_FILES['attachment']['name'];
    $target = "../uploads/" . $filename;
    move_uploaded_file($_FILES['attachment']['tmp_name'], $target);
    $attachment = $filename;
}

// Build SQL
if ($attachment) {
    $sql = "UPDATE complaints 
            SET title=?, category=?, description=?, location=?, incident_date=?, priority=?, attachment=? 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $title, $category, $description, $location, $incident_date, $priority, $attachment, $complaint_id);
} else {
    $sql = "UPDATE complaints 
            SET title=?, category=?, description=?, location=?, incident_date=?, priority=? 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $title, $category, $description, $location, $incident_date, $priority, $complaint_id);
}

if ($stmt->execute()) {
    header("Location: ../pages/list_my_complaints.php?updated=1");
} else {
    echo "Update failed: " . $stmt->error;
}
