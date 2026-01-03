<?php
include 'db.php';

$id = $_POST['id'];
$user_name = $_POST['user_name'];
$email = $_POST['email'];
$house_no = $_POST['house_no'];
$contact_no = $_POST['contact_no'];
$complaint_title = $_POST['complaint_title'];
$category = $_POST['category'];
$complaint = $_POST['complaint'];
$incident_date = $_POST['incident_date'];

// Get old file name
$result = mysqli_query($conn, "SELECT evidence FROM complaints WHERE id=$id");
$old = mysqli_fetch_assoc($result);
$oldFile = $old['evidence'];

$evidence_sql = "";

if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] == 0) {

    $allowed = array('jpg','jpeg','png','pdf','doc','docx');
    $ext = pathinfo($_FILES['evidence']['name'], PATHINFO_EXTENSION);

    if (in_array(strtolower($ext), $allowed)) {

        $newFile = time() . "_" . basename($_FILES['evidence']['name']);
        move_uploaded_file($_FILES['evidence']['tmp_name'], "../uploads/" . $newFile);

        // Delete old file if exists
        if (!empty($oldFile) && file_exists("../uploads/" . $oldFile)) {
            unlink("../uploads/" . $oldFile);
        }

        $evidence_sql = ", evidence='$newFile'";
    }
}


$sql = "UPDATE complaints SET
user_name='$user_name',
email='$email',
house_no='$house_no',
contact_no='$contact_no',
complaint_title='$complaint_title',
category='$category',
complaint='$complaint',
incident_date='$incident_date'
$evidence_sql
WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    header("Location: list_complaints.php");
} else {
    echo "Error updating record";
}
?>
