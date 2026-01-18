<?php
session_start();
require_once '../php/complaints_model.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit("Unauthorized access");
}

if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    if (deleteComplaint($id)) {
        header("Location: ../pages/admin/list_complaints.php?deleted=1");
    } else {
        header("Location: ../pages/admin/list_complaints.php?error=1");
    }
    exit();
}
