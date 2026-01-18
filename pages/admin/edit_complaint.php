<?php
session_start();
require_once '../../db/db.php';
require_once '../../php/complaints_model.php';

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Validate complaint ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: list_complaints.php");
    exit();
}

$complaint_id = (int)$_GET['id'];

// Fetch complaint
$complaint = getComplaintById($complaint_id);

if (!$complaint) {
    header("Location: list_complaints.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Complaint Status & Add Comment</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/register.css">
</head>
<body class="admin_body">

<?php include '../../includes/header.php'; ?>

<div class="container" style="max-width:600px; margin-bottom:40px;">
    <h2 style="text-align:center;">Update Complaint Status & Add Comment</h2>

    <form action="../../controllers/update_complaint.php" method="POST">

        <!-- Hidden complaint ID -->
        <input type="hidden" name="complaint_id" value="<?= (int)$complaint['id'] ?>">

        <label>Resident Name</label>
        <input type="text" value="<?= htmlspecialchars($complaint['first_name'] . ' ' . $complaint['last_name']) ?>" disabled>

        <label>Complaint Title</label>
        <input type="text" value="<?= htmlspecialchars($complaint['title']) ?>" disabled>

        <label>Complaint Description</label>
        <textarea disabled><?= htmlspecialchars($complaint['description']) ?></textarea>

        <label>Status</label>
        <select name="status" required>
            <option value="Pending" <?= $complaint['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="In Progress" <?= $complaint['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="Resolved" <?= $complaint['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
            <option value="Rejected" <?= $complaint['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
            <option value="Closed" <?= $complaint['status'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
        </select>

        <label>Add Comment</label>
        <textarea name="comment"><?= htmlspecialchars($complaint['comment']) ?></textarea>

        <br>
        <div style="text-align:center; display:flex; justify-content:center; gap:10px;">
            <button type="button" onclick="location.href='list_complaints.php'">Cancel</button>
            <button type="submit">Update Complaint</button>
        </div>

    </form>

</div>

<?php include '../../includes/footer.php'; ?>
</body>
</html>
