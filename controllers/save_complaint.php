<?php
session_start();
require_once '../php/complaints_model.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title         = trim($_POST['complaint_title']);
    $category      = trim($_POST['category']);
    $description   = trim($_POST['complaint']);
    $priority      = trim($_POST['priority']);
    $apartment_id  = trim($_POST['apartment_id']); 
    $incident_date = trim($_POST['incident_date']);

    // Handle file attachment
    $attachment = null;
    if (!empty($_FILES['attachment']['name']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../uploads/";
        $fileName  = time() . "_" . preg_replace("/[^A-Za-z0-9\._-]/", "", basename($_FILES['attachment']['name']));
        $targetFile = $uploadDir . $fileName;

        $allowed = ['jpg','jpeg','png','pdf','doc','docx'];
        $fileExt = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowed)) {
            header("Location: ../pages/add_complaint.php?error=invalid_file_type");
            exit();
        }

        if ($_FILES['attachment']['size'] > 5 * 1024 * 1024) { // 5MB
            header("Location: ../pages/add_complaint.php?error=file_too_large");
            exit();
        }

        if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFile)) {
            header("Location: ../pages/add_complaint.php?error=upload_failed");
            exit();
        }

        // Only set attachment if file successfully moved
        $attachment = $fileName;
    }

    // Save to DB
    $success = createComplaint(
        $_SESSION['user_id'],
        $title,
        $category,
        $description,
        $priority,
        $apartment_id,
        $incident_date,
        $attachment // pass the filename here
    );

    if ($success) {
    header("Location: ../pages/add_complaint.php?msg=success&text=" . urlencode("Complaint submitted successfully!"));
    } else {
        header("Location: ../pages/add_complaint.php?msg=error&text=" . urlencode("Failed to submit complaint. Please try again."));
    }

    exit();
}