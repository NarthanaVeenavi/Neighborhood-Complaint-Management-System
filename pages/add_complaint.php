<?php
session_start();
require_once '../db/db.php';
require_once '../php/user_model.php';
require_once '../php/apartment_model.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
$apartments = getAllApartments();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Complaint</title>
    <script src="../js/complaint.js" defer></script>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/register.css">

</head>
<body class="reg_body">
    <!-- Header -->
    <?php include '../includes/header.php'; ?>
    <div class="container">
    <h2>Add Complaint</h2>

    <form action="../controllers/save_complaint.php"
        method="POST"
        enctype="multipart/form-data"
        onsubmit="return validateForm();"
        novalidate>

        <label>Complaint Title</label>
        <input type="text" name="complaint_title" >
        <span id="complaint_titleError" class="error"></span>

        <label>Complaint Category</label>
        <select name="category" >
            <option value="">-- Select Category --</option>
            <option value="Noise">Noise</option>
            <option value="Maintenance">Maintenance</option>
            <option value="Security">Security</option>
            <option value="Other">Other</option>
        </select>
        <span id="categoryError" class="error"></span> <br><br> 

        <label>Complaint Description</label>
        <textarea name="complaint" ></textarea>
        <span id="complaintError" class="error"></span>

        <label>Location</label>
        <p style="color: #666; font-size: 12px;">You can select apartment, if it already known OR add extra detail like: Near lift, Parking Area, Corridor in description area</p>
        <select name="apartment_id" required>
            <option value="">-- Select Apartment --</option>
            <?php while ($apt = $apartments->fetch_assoc()): ?>
                <option value="<?= $apt['id'] ?>">
                    <?= htmlspecialchars($apt['name']) ?>
                        | Block <?= htmlspecialchars($apt['block']) ?>
                        | Floor <?= htmlspecialchars($apt['floor']) ?>
                    </option>
            <?php endwhile; ?>
        </select>

        <label>Date of Incident</label>
        <input type="date" name="incident_date" required>
        <span id="incident_dateError" class="error"></span> <br><br>

        <label>Priority</label>
        <div class="priority-group">
                <label class="priority low">
                    <input type="radio" name="priority" value="Low">
                    <span>🟢 Low</span>
                </label>

                <label class="priority medium">
                    <input type="radio" name="priority" value="Medium" checked>
                    <span>🟠 Medium</span>
                </label>

                <label class="priority high">
                    <input type="radio" name="priority" value="High">
                    <span>🔴 High</span>
                </label>
            </div>

        <label>Upload Evidence</label><br>
        <input type="file" name="evidence" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"> <br><br>

        <button type="submit">Submit</button>
    </form>
    </div>
    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>
