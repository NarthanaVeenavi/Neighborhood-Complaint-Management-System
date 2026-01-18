<?php
session_start();
require_once '../db/db.php';
require_once '../php/complaints_model.php';

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit("Unauthorized access");
}

// Validate complaint ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../pages/admin/list_complaints.php");
    exit();
}

$complaint_id = (int)$_GET['id'];

// Delete the complaint
$success = deleteComplaint($complaint_id);

if ($success) {
    header("Location: ../pages/admin/list_complaints.php?deleted=1");
} else {
    header("Location: ../pages/admin/list_complaints.php?error=1");
}
exit();
