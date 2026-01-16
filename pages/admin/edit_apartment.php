
<?php
session_start();
require_once '../../php/apartment_model.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$apartment = null;
$page_title = "Add Apartment";
$form_action = "../../controllers/edit_apartment.php";
$cancel_url = "apartment_list.php";

if (isset($_GET['id'])) {
    $apartment = getApartmentById((int)$_GET['id']);
    $page_title = "Edit Apartment";
    $form_action .= "?id=" . $apartment['id'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $page_title ?> | NeighborCare</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/register.css">
</head>
<body>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <div id="toastContainer"></div>
    <h2><?= $page_title ?></h2>

    <form method="POST" action="<?= htmlspecialchars($form_action) ?>">
        <label>Apartment Name</label>
        <input type="text" name="name" value="<?= $apartment['name'] ?? '' ?>" placeholder="Enter apartment name: Eg: APT-001" required>

        <label>Floor</label>
        <input type="number" name="floor" value="<?= $apartment['floor'] ?? '' ?>" required>

        <label>Block</label>
        <input type="text" name="block" value="<?= $apartment['block'] ?? '' ?>" required placeholder="Enter block: Eg: A or B">

        <div class="form-actions"> 
            <button><a href="<?= htmlspecialchars($cancel_url) ?>">Cancel</a></button>
            <button type="submit"><?= $apartment ? "Update Apartment" : "Add Apartment" ?></button>
        </div>

    </form>
</div>

<?php include '../../includes/footer.php'; ?>
<script src="../../js/apartments.js"></script>
</body>
</html>
