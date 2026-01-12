<?php
session_start();
require_once '../php/complaints_model.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = trim($_POST['complaint_title']);
    $category    = trim($_POST['category']);
    $description = trim($_POST['complaint']);
    $priority    = trim($_POST['priority']);
    $location    = trim($_POST['apartment_id']); 
    $incident_date = trim($_POST['incident_date']);

    $success = createComplaint(
        $_SESSION['user_id'],
        $title,
        $category,
        $description,
        $priority,
        $location,
        $incident_date
    );

    if ($success) {
        header("Location: ../pages/add_complaint.php?success=1");
    } else {
        header("Location: ../pages/add_complaint.php?error=1");
    }
    exit();
}
