<?php
session_start();
require_once '../php/complaint_model.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit("Unauthorized access");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $complaint_id = (int) $_POST['complaint_id'];
    $status       = $_POST['status'];

    $success = updateComplaintStatus($complaint_id, $status);

    if ($success) {
        header("Location: ../pages/admin/manage_complaints.php?updated=1");
    } else {
        header("Location: ../pages/admin/manage_complaints.php?error=1");
    }
    exit();
}
