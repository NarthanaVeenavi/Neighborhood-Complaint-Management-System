<?php
session_start();
require_once '../php/user_model.php';

// Only admin can delete
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

// Check if 'id' is present in URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../pages/admin/users_list.php");
    exit();
}

$resident_id = (int) $_GET['id'];

// Prevent admin from deleting themselves
if ($resident_id === $_SESSION['user_id']) {
    header("Location: ../pages/admin/user_list.php?error=self_delete");
    exit();
}

// Get resident details to check role
$resident = getResidentById($resident_id);
if (!$resident) {
    header("Location: ../pages/admin/user_list.php?error=not_found");
    exit();
}

// Prevent deleting another admin
if ($resident['role'] === 'admin') {
    header("Location: ../pages/admin/user_list.php?error=admin_delete");
    exit();
}

// Delete resident from database
$success = deleteResident($resident_id);

if ($success) {
    header("Location: ../pages/admin/user_list.php?deleted=1");
    exit();
} else {
    header("Location: ../pages/admin/user_list.php?error=1");
    exit();
}