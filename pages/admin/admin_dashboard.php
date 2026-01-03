<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/landing_page.css">
    <link rel="stylesheet" href="../../css/main.css">
</head>
<body>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <h3>Admin Dashboard</h3>

    <ul>
        <li><a href="view_complaints.php">View All Complaints</a></li>
        <li><a href="manage_residents.php">Manage Residents</a></li>
        <li><a href="reports.php">Reports & Statistics</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>
<?php include '../../includes/footer.php'; ?>

</body>
</html>
