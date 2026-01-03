<?php
include 'db.php';

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM complaints WHERE id=$id");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Complaint</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">
    <h2>Edit Complaint</h2>

    <form action="update_complaint.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?= $row['id'] ?>">

<label>Full Name</label>
<input type="text" name="user_name" value="<?= htmlspecialchars($row['user_name']) ?>" required>

<label>Email</label>
<input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>

<label>House / Apartment No</label>
<input type="text" name="house_no" value="<?= htmlspecialchars($row['house_no']) ?>">

<label>Contact Number</label>
<input type="text" name="contact_no" value="<?= htmlspecialchars($row['contact_no']) ?>">

<label>Complaint Title</label>
<input type="text" name="complaint_title" value="<?= htmlspecialchars($row['complaint_title']) ?>" required>

<label>Complaint Category</label>
<select name="category">
    <option value="Noise" <?= $row['category']=="Noise"?"selected":"" ?>>Noise</option>
    <option value="Maintenance" <?= $row['category']=="Maintenance"?"selected":"" ?>>Maintenance</option>
    <option value="Security" <?= $row['category']=="Security"?"selected":"" ?>>Security</option>
    <option value="Other" <?= $row['category']=="Other"?"selected":"" ?>>Other</option>
</select>

<label>Description</label>
<textarea name="complaint" required><?= htmlspecialchars($row['complaint']) ?></textarea>

<label>Date of Incident</label>
<input type="date" name="incident_date" value="<?= $row['incident_date'] ?>"></br></br>

<label>Upload Evidence (optional)</label>
<input type="file" name="evidence" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">

<?php if (!empty($row['evidence'])): ?>
    <p style="margin-top:8px;">
        Current File:
        <a href="../uploads/<?= htmlspecialchars($row['evidence']) ?>" 
           target="_blank">
            View Evidence
        </a>
    </p>
<?php else: ?>
    <p>No evidence uploaded.</p>
<?php endif; ?>


        <button type="submit">Update</button>
    </form>

    <a href="list_complaints.php" class="add-btn">Back to Complaint List</a>
</div>

</body>
</html>
