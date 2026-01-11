<?php
session_start();
include '../db/db.php';
require_once '../php/user_model.php';
require_once '../php/apartment_model.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user = getResidentById($_SESSION['user_id']);

$apartments = getAllApartments();
?>

<?php $page_title = "Edit Profile"; ?>
<?php $cancel_url = "residents_list.php"; ?>
<?php $form_action = "../controllers/edit_profile.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile | NeighborCare</title>
    <link rel="stylesheet" href="../css/register.css">
    <link rel="stylesheet" href="../css/landing_page.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body class="reg_body">

    <?php include '../includes/header.php'; ?>
    <!-- Toast container -->
    <div id="toastContainer"></div>
    
<?php include '../pages/common_user_profile_form.php'; ?>
<?php include '../includes/footer.php'; ?>

<script src="../js/edit_profile.js"></script>

</body>
</html>
