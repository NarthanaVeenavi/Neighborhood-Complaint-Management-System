<?php
session_start();
require_once '../../db/db.php';
require_once '../../php/complaints_model.php';

// Check admin login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Validate complaint ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: list_complaints.php");
    exit();
}

$complaint_id = (int)$_GET['id'];

// Fetch complaint details
$complaint = getComplaintById($complaint_id);
if (!$complaint) {
    header("Location: list_complaints.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Complaint</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/register.css">
    <link rel="stylesheet" href="../../css/view_complaint.css">
</head>
<body class="reg_body">
<?php include '../../includes/header.php'; ?>

<div class="container">
    <h2 style="text-align:center; margin-top:20px;">Complaint Details</h2>

    <div class="complaint-details">
        <p><strong>Resident:</strong> <?= htmlspecialchars($complaint['first_name'] . ' ' . $complaint['last_name']) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($complaint['phone']) ?></p>
        <p><strong>Title:</strong> <?= htmlspecialchars($complaint['title']) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($complaint['category']) ?></p>
        <p><strong>Priority:</strong> <?= htmlspecialchars($complaint['priority']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($complaint['apartment_name']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($complaint['status']) ?></p>
        <p><strong>Date of Incident:</strong> <?= htmlspecialchars($complaint['incident_date']) ?></p>
        <p><strong>Description:</strong> <br><?= nl2br(htmlspecialchars($complaint['description'])) ?></p>

        <?php if (!empty($complaint['evidence'])): ?>
            <p><strong>Evidence:</strong> 
                <a href="../../uploads/<?= htmlspecialchars($complaint['evidence']) ?>" target="_blank">View/Download</a>
            </p>
        <?php endif; ?>
    </div>

    <div style="text-align:center;">
        <?php
        // Determine back URL based on role
        $backUrl = 'list_complaints.php'; // default
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'resident') {
            $backUrl = '../../pages/list_my_complaints.php';
        }
        ?>
        <a href="<?= $backUrl ?>" class="btn-back">Back to Complaint List</a>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
</body>
</html>
