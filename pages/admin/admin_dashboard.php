<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="../../css/landing_page.css">
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <h3>Admin Dashboard</h3>

    <div class="dashboard-grid">
        <a href="view_complaints.php" class="dashboard-card">
            <i class="fas fa-list-alt"></i>
            <span>View Complaints</span>
        </a>

        <a href="./user_list.php" class="dashboard-card">
            <i class="fas fa-users-cog"></i>
            <span>Manage Users</span>
        </a>

        <a href="reports.php" class="dashboard-card">
            <i class="fas fa-chart-line"></i>
            <span>Reports & Statistics</span>
        </a>
        <a href="../admin/apartment_list.php" class="dashboard-card">
            <i class="fas fa-house"></i>
            <span>Manage Apartments</span>
        </a>
    </div>

    <div style="text-align:center;">
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

</body>
</html>
