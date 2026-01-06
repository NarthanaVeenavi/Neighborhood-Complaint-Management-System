<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'resident') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard | NeighborCare</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="../css/landing_page.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h3>Welcome, <?= $_SESSION['first_name'] ?>!</h3>

    <div class="dashboard-grid">
        <a href="my_complaints.php" class="dashboard-card">
            <i class="fas fa-list-alt"></i>
            <span>My Complaints</span>
        </a>

        <a href="new_complaint.php" class="dashboard-card">
            <i class="fas fa-plus-circle"></i>
            <span>Submit New Complaint</span>
        </a>

        <a href="edit_profile.php" class="dashboard-card">
            <i class="fas fa-user-edit"></i>
            <span>Edit Profile</span>
        </a>

        <a href="complaint_status.php" class="dashboard-card">
            <i class="fas fa-info-circle"></i>
            <span>Complaint Status</span>
        </a>
    </div>

    <div style="text-align:center; margin-top:20px;">
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>
