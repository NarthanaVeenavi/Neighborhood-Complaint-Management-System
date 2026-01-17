<?php
session_start();
require_once '../db/db.php';
require_once '../php/complaints_model.php';
require_once '../php/apartment_model.php';

// Check user login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'resident') {
    header("Location: ../../login.php");
    exit();
}

// Validate complaint ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: list_my_complaints.php");
    exit();
}

$apartments = getAllApartments();
$complaint_id = (int)$_GET['id'];

// Fetch complaint
$complaint = getComplaintById($complaint_id);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Complaint</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/register.css">

</head>
<body class="reg_body">
<?php include '../includes/header.php'; ?>

<div class="container">
    <div id="toastContainer"></div>
    <h2 style="text-align:center; margin-top:20px;">Edit Complaint</h2>

    <?php if (!empty($error)): ?>
        <div class="error-toast"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="../controllers/update_complaint_resident.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm();">

        <!-- Hidden complaint ID – very important! -->
        <input type="hidden" name="complaint_id" value="<?= (int)$complaint['id'] ?>">

        <label>Complaint Title</label>
        <input type="text" name="complaint_title" value="<?= htmlspecialchars($complaint['title'] ?? '') ?>" required>

        <label>Category</label>
        <select name="category" required>
            <option value="Noise"    <?= ($complaint['category'] ?? '') === 'Noise'    ? 'selected' : '' ?>>Noise</option>
            <option value="Maintenance" <?= ($complaint['category'] ?? '') === 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
            <option value="Security" <?= ($complaint['category'] ?? '') === 'Security' ? 'selected' : '' ?>>Security</option>
            <option value="Other"    <?= ($complaint['category'] ?? '') === 'Other'    ? 'selected' : '' ?>>Other</option>
        </select>

        <label>Description</label>
        <textarea name="complaint" required><?= htmlspecialchars($complaint['description'] ?? '') ?></textarea>

        <label>Location / Apartment</label>
        <select name="apartment_id" required>
            <option value="">-- Select Apartment --</option>
            <?php while ($apt = $apartments->fetch_assoc()): ?>
                <option value="<?= (int)$apt['id'] ?>"
                    <?= ((int)$apt['id'] === (int)($complaint['apartment_id'] ?? 0)) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($apt['name']) ?> | Block <?= htmlspecialchars($apt['block']) ?> | Floor <?= htmlspecialchars($apt['floor']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Date of Incident</label>
        <input type="date" name="incident_date" id="incident_date" value="<?= htmlspecialchars($complaint['incident_date'] ?? '') ?>" required>

        <label>Priority</label>
        <div class="priority-group">
            <label><input type="radio" name="priority" value="Low"    <?= ($complaint['priority'] ?? '') === 'Low'    ? 'checked' : '' ?>> Low</label>
            <label><input type="radio" name="priority" value="Medium" <?= ($complaint['priority'] ?? '') === 'Medium' ? 'checked' : '' ?>> Medium</label>
            <label><input type="radio" name="priority" value="High"   <?= ($complaint['priority'] ?? '') === 'High'   ? 'checked' : '' ?>> High</label>
        </div>

        <label>Evidence / Attachment</label><br>
        <?php if (!empty($complaint['attachment'])): ?>
            <a href="../uploads/<?= htmlspecialchars($complaint['attachment']) ?>" target="_blank">View current file</a><br><br>
        <?php endif; ?>
        <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
        <br/><br/>
        <button type="submit">Update Complaint</button>
    </form>
</div>

<script src="../js/complaint.js"></script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
