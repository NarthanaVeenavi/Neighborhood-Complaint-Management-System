<?php
session_start();
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, password, role, first_name, last_name FROM residents WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            // Store session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];

            // Role-based redirect
            // Role-based redirect
            if ($user['role'] === 'admin') {
                header("Location: ../pages/admin/admin_dashboard.php?success=Login successful!");
            } else {
                header("Location: ../pages/user_dashboard.php?success=Login successful!");
            }
            exit();

        } else {
            header("Location: ../pages/login.php?error=Invalid email or password!");
            exit();
        }
    } else {
        header("Location: ../pages/login.php?error=Invalid email or password!");
        exit();
    }
}
?>
