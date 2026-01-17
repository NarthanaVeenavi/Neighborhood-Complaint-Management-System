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
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/main.css">
</head>
<body class="admin_body">

<?php include '../../includes/header.php'; ?>

<div class="container" style="max-width:600px; margin:auto; margin-top:50px;">
    <h2 style="text-align:center;">Update Complaint Status & Add Comment</h2>

    <form action="../../controllers/update_complaint.php" method="POST">

        <!-- Hidden complaint ID -->
        <input type="hidden" name="complaint_id" value="<?= (int)$complaint['id'] ?>">

        <label>Resident Name</label>
        <input type="text" value="<?= htmlspecialchars($complaint['first_name'] . ' ' . $complaint['last_name']) ?>" disabled>

        <label>Complaint Title</label>
        <input type="text" value="<?= htmlspecialchars($complaint['title']) ?>" disabled>

        <label>Status</label>
        <select name="status" required>
            <option value="Open" <?= $complaint['status'] === 'Open' ? 'selected' : '' ?>>Open / Pending</option>
            <option value="In Progress" <?= $complaint['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="Closed" <?= $complaint['status'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
        </select>

        <label>Add Comment</label>
        <textarea name="admin_comment" placeholder="Add a comment for this complaint..." rows="4"></textarea>

        <br>
        <button type="submit" class="btn">Update Complaint</button>
        <a href="list_complaints.php" class="btn cancel-btn" style="margin-left:10px;">Cancel</a>
    </form>

    <hr>

    <!-- Show previous comments -->
    <h3>Previous Comments</h3>
    <?php
    $sql = "SELECT cc.comment_text, r.first_name, r.last_name, cc.created_at
            FROM complaint_comments cc
            JOIN residents r ON cc.admin_id = r.id
            WHERE cc.complaint_id = ?
            ORDER BY cc.created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $complaint_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
    ?>
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <strong><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></strong> 
            <em>(<?= $row['created_at'] ?>)</em>
            <p><?= nl2br(htmlspecialchars($row['comment_text'])) ?></p>
        </div>
    <?php
        endwhile;
    else:
        echo "<p>No comments yet.</p>";
    endif;
    ?>

</div>

<?php include '../../includes/footer.php'; ?>
</body>
</html>
