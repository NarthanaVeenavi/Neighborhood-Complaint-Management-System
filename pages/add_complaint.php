<?php
session_start();
require_once '../../db/db.php';
require_once '../../php/user_model.php';

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

<link rel="stylesheet" href="../css/style.css">

</head>
<body>

<div class="container">
<h2>Add Complaint</h2>

<form action="save_complaint.php"
      method="POST"
      enctype="multipart/form-data"
      onsubmit="return validateForm();"
      novalidate>


     <label>Full Name</label>
    <input type="text" name="user_name" >
    <span id="user_nameError" class="error"></span>

    <label>Email</label>
    <input type="email" name="email" id="email" >
    <span id="emailError" class="error"></span>

    <label>Apartment</label>
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

    <label>Contact Number</label>
    <input type="text" name="contact_no" >
    <span id="contact_noError" class="error"></span>

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

    <label>Date of Incident</label>
    <input type="date" name="incident_date" required>
    <span id="incident_dateError" class="error"></span> <br><br>

    <label>Upload Evidence</label><br>
    <input type="file" name="evidence" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"> <br><br>

    <button type="submit">Submit</button>
</form>

<a href="list_complaints.php">View Complaints</a>
</div>
</body>
</html>
