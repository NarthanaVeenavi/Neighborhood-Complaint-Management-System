<?php
session_start();
require_once '../db/db.php';
require_once '../php/complaints_model.php';

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit("Unauthorized access");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $complaint_id = (int) $_POST['complaint_id'];
    $status       = $_POST['status'];
    $admin_id     = $_SESSION['user_id'];
    $comment = trim($_POST['comment'] ?? '');


    $success = true;

    $success = updateComplaintStatusandComment($complaint_id, $status, $comment);


    if ($success) {
        header("Location: ../pages/admin/list_complaints.php?id=$complaint_id&updated=1");
    } else {
        header("Location: ../pages/admin/list_complaints.php?id=$complaint_id&error=1");
    }
    exit();
}
