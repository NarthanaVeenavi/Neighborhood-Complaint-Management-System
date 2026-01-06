<?php
session_start();
require_once '../php/user_model.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: residents_list.php");
    exit();
}

$user_id = (int) $_GET['id'];
$user = getResidentById($user_id);
$role = $_POST['role'] ?? $user['role'];

if (!$user) {
    header("Location: residents_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $success = updateResidentByAdmin(
        $user_id,
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['apartment_id'],
        $role,
        $_POST['joining_date']
    );


    if ($success) {
        header("Location: ../pages/admin/user_list.php?updated=1");
        exit();
    } else {
        $error = "Failed to update resident";
    }
}