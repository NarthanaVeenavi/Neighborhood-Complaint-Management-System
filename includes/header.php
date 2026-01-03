<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate initials if logged in
$initials = '';
if (isset($_SESSION['first_name'], $_SESSION['last_name'])) {
    $initials =
        strtoupper(substr($_SESSION['first_name'], 0, 1)) .
        strtoupper(substr($_SESSION['last_name'], 0, 1));
}
?>

<header class="header">
    <div class="logo">
        <a href="/Neighborhood Complaint Management System/pages/landing_page.php">
            NeighborCare
        </a>
    </div>

    <nav class="nav">

        <?php if (!isset($_SESSION['user_id'])): ?>
            <!-- Guest -->
            <a href="/Neighborhood Complaint Management System/pages/login.php">Login</a>
            <a href="/Neighborhood Complaint Management System/pages/register.php">Register</a>

        <?php else: ?>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <!-- Admin -->
                <a href="/Neighborhood Complaint Management System/pages/admin/admin_dashboard.php">Dashboard</a>
                <a href="/Neighborhood Complaint Management System/pages/admin/view_complaints.php">Complaints</a>
                <a href="/Neighborhood Complaint Management System/pages/admin/manage_residents.php">Residents</a>
            <?php else: ?>
                <!-- Resident -->
                <a href="/Neighborhood Complaint Management System/pages/resident/dashboard.php">Dashboard</a>
                <a href="/Neighborhood Complaint Management System/pages/resident/my_complaints.php">My Complaints</a>
                <a href="/Neighborhood Complaint Management System/pages/resident/new_complaint.php">New Complaint</a>
            <?php endif; ?>
            <!-- Logged user avatar -->
            <div class="avatar">
                <?php echo $initials; ?>
            </div>

            <a href="/Neighborhood Complaint Management System/pages/logout.php">Logout</a>

        <?php endif; ?>

    </nav>
</header>