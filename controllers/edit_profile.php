<?php
session_start();
require_once '../php/user_model.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

$success = updateResidentProfile(
    $_SESSION['user_id'],
    $_POST['first_name'],
    $_POST['last_name'],
    $_POST['email'],
    $_POST['phone'],
    $_POST['apartment_id'],
    $_POST['joining_date'],
);

if ($success) {

    // Update session data
    $_SESSION['first_name'] = $_POST['first_name'];
    $_SESSION['last_name']  = $_POST['last_name'];

    // Redirect back to profile page with success flag
    header("Location: ../pages/edit_profile.php?success=1");
    exit();

} else {
    $error = urlencode(mysqli_error($conn));
    header("Location: ../pages/edit_profile.php?error=$error");
    exit();
    }
?>