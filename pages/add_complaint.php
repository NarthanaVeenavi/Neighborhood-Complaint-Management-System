<?php
session_start();
require_once '../db/db.php';
require_once '../php/complaints_model.php';
require_once '../php/apartment_model.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'resident') {
    header("Location: login.php");
    exit();
}

// ────────────────────────────────────────────────
// Determine mode: add or edit
// ────────────────────────────────────────────────
$isEdit = isset($_GET['edit']) && is_numeric($_GET['edit']);
$complaint_id = $isEdit ? (int)$_GET['edit'] : 0;

$complaint = null;
if ($isEdit) {
    $complaint = getComplaintById($complaint_id);
    if (!$complaint || $complaint['resident_id'] != $_SESSION['user_id']) {
        header("Location: list_my_complaints.php?error=unauthorized");
        exit();
    }
}

// Get apartments for dropdown
$apartments = getAllApartments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit Complaint' : 'Add Complaint' ?></title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container">
    <div id="toastContainer"></div>
    <h2><?= $isEdit ? 'Edit Complaint' : 'Add Complaint' ?></h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="error-toast">Please check the form and try again.</div>
    <?php endif; ?>

    <form action="../controllers/save_complaint.php" method="POST" enctype="multipart/form-data" 
          onsubmit="return validateForm();" novalidate>

        <?php if ($isEdit): ?>
            <input type="hidden" name="complaint_id" value="<?= $complaint_id ?>">
        <?php endif; ?>

        <label>Complaint Title</label>
        <input type="text" name="complaint_title" 
               value="<?= htmlspecialchars($complaint['title'] ?? '') ?>" required>
        <span id="complaint_titleError" class="error"></span>

        <label>Complaint Category</label>
        <select name="category" required>
            <option value="">-- Select Category --</option>
            <option value="Noise"    <?= ($complaint['category'] ?? '') === 'Noise'    ? 'selected' : '' ?>>Noise</option>
            <option value="Maintenance" <?= ($complaint['category'] ?? '') === 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
            <option value="Security" <?= ($complaint['category'] ?? '') === 'Security' ? 'selected' : '' ?>>Security</option>
            <option value="Other"    <?= ($complaint['category'] ?? '') === 'Other'    ? 'selected' : '' ?>>Other</option>
        </select>
        <span id="categoryError" class="error"></span>

        <label>Complaint Description</label>
        <textarea name="complaint" required><?= htmlspecialchars($complaint['description'] ?? '') ?></textarea>
        <span id="complaintError" class="error"></span>

        <label>Location / Apartment</label>
        <p style="color: #666; font-size: 12px;">
            Select your apartment if known, or add details (lift, parking, corridor...) in description.
        </p>
        <select name="apartment_id">
            <option value="">-- Select Apartment (optional) --</option>
            <?php while ($apt = $apartments->fetch_assoc()): ?>
                <option value="<?= $apt['id'] ?>"
                    <?= ($complaint['apartment_id'] ?? 0) == $apt['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($apt['name']) ?>
                    | Block <?= htmlspecialchars($apt['block'] ?? '-') ?>
                    | Floor <?= htmlspecialchars($apt['floor'] ?? '-') ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Date of Incident</label>
        <input type="date" name="incident_date" id="incident_date"
               value="<?= htmlspecialchars($complaint['incident_date'] ?? '') ?>" required>
        <span id="incident_dateError" class="error"></span>

        <label>Priority</label>
        <div class="priority-group">
            <label class="priority low">
                <input type="radio" name="priority" value="Low" 
                       <?= ($complaint['priority'] ?? 'Medium') === 'Low' ? 'checked' : '' ?>>
                <span>🟢 Low</span>
            </label>
            <label class="priority medium">
                <input type="radio" name="priority" value="Medium" 
                       <?= ($complaint['priority'] ?? 'Medium') === 'Medium' ? 'checked' : '' ?>>
                <span>🟠 Medium</span>
            </label>
            <label class="priority high">
                <input type="radio" name="priority" value="High" 
                       <?= ($complaint['priority'] ?? 'Medium') === 'High' ? 'checked' : '' ?>>
                <span>🔴 High</span>
            </label>
        </div>

        <label>Upload Evidence <?= $isEdit ? '(leave empty to keep current)' : '' ?></label><br>
        <?php if ($isEdit && !empty($complaint['attachment'])): ?>
            <div style="margin: 8px 0;">
                Current file: 
                <a href="../uploads/<?= htmlspecialchars($complaint['attachment']) ?>" target="_blank">
                    View / Download
                </a>
            </div>
        <?php endif; ?>
        <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
        <br/><br/>
        <button type="submit">
            <?= $isEdit ? 'Update Complaint' : 'Submit Complaint' ?>
        </button>
    </form>
</div>

<script src="../js/complaint.js"></script>

<?php include '../includes/footer.php'; ?>

</body>
</html>