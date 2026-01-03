<?php
include 'db.php';

$user_name = $_POST['user_name'];
$email = $_POST['email'];
$house_no = $_POST['house_no'];
$contact_no = $_POST['contact_no'];
$complaint_title = $_POST['complaint_title'];
$category = $_POST['category'];
$complaint = $_POST['complaint'];
$incident_date = $_POST['incident_date'];

$evidence = "";
if (!empty($_FILES['evidence']['name'])) {
    $evidence = time() . "_" . $_FILES['evidence']['name'];
    move_uploaded_file($_FILES['evidence']['tmp_name'], "../uploads/" . $evidence);
}

$sql = "INSERT INTO complaints 
(user_name, email, house_no, contact_no, complaint_title, category, complaint, incident_date, evidence)
VALUES 
('$user_name', '$email', '$house_no', '$contact_no', '$complaint_title', '$category', '$complaint', '$incident_date', '$evidence')";

mysqli_query($conn, $sql);  // Run query without showing error

// Redirect to list page
header("Location: list_complaints.php");
exit;
?>
