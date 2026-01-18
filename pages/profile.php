<?php
session_start();
require_once '../db/db.php';
require_once '../php/user_model.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user = getResidentById($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/landing_page.css">
    <link rel="stylesheet" href="../css/user_profile.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="profile-container">
    <div class="profile-header">
        <div class="profile-avatar">
            <?= strtoupper(substr($user['first_name'], 0, 1)).strtoupper(substr($user['last_name'], 0, 1)) ?>
        </div>
        <h3><?= htmlspecialchars($user['first_name']).' '.htmlspecialchars($user['last_name']) ?></h3>
        <span><?= ucfirst($user['role']) ?> Account</span>
    </div>

    <div class="profile-info">
        <div class="profile-row">
            <label>Email</label>
            <span><?= htmlspecialchars($user['email']) ?></span>
        </div>

        <div class="profile-row">
            <label>Phone</label>
            <span><?= htmlspecialchars($user['phone']) ?></span>
        </div>

        <?php if ($user['role'] === 'resident'): ?>
            <div class="profile-row">
                <label>Apartment</label>
                <span>
                    <?= htmlspecialchars($user['apartment_name']) ?> |
                    Block <?= htmlspecialchars($user['apartment_block']) ?> |
                    Floor <?= htmlspecialchars($user['apartment_floor']) ?>
                </span>
            </div>
        <?php endif; ?>

        <div class="profile-row">
            <label>Role</label>
            <span><?= ucfirst($user['role']) ?></span>
        </div>

        <div class="profile-row">
            <label>Member Since</label>
            <span><?= date("F d, Y", strtotime($user['joining_date'])) ?></span>
        </div>
    </div>

    <div class="profile-actions">
        <a href="edit_profile.php" class="btn btn-edit">Edit Profile</a>
        <a href="/Neighborhood Complaint Management System/pages/logout.php" class="btn btn-logout">Logout</a>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
