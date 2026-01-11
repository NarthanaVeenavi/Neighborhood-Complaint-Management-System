<?php
session_start();
require_once '../../php/user_model.php';
require_once '../../php/apartment_model.php';

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get resident ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: residents_list.php");
    exit();
}

$resident_id = (int) $_GET['id'];

// Fetch resident data
$user = getResidentById($resident_id);
$apartments = getAllApartments();

if (!$user) {
    header("Location: residents_list.php");
    exit();
}

$page_title = "Edit Resident";
$cancel_url = "user_list.php";
$form_action = "../../controllers/edit_resident_admin.php?id=" . $resident_id;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Resident</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/landing_page.css">
    <link rel="stylesheet" href="../../css/register.css">
</head>
<body class="reg_body">

<?php include '../../includes/header.php'; ?>
    <!-- Hidden div to store the session flag -->
    <div id="profileUpdatedFlag" style="display: none;">
        <?php echo isset($_SESSION['profile_updated']) && $_SESSION['profile_updated'] === true ? 'true' : 'false'; ?>
    </div>
    <!-- Toast container -->
    <div id="toastContainer"></div>

<?php include '../common_user_profile_form.php'; ?>

<?php include '../../includes/footer.php'; ?>
<script src="../../js/edit_resident.js"></script>

</body>
</html>