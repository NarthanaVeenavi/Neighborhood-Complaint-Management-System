    <?php
session_start();
require_once '../php/apartment_model.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

$name = $_POST['name'] ?? '';
$floor = $_POST['floor'] ?? '';
$block = $_POST['block'] ?? '';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $success = updateApartment($id, $name, $floor, $block);
} else {
    $success = addApartment($name, $floor, $block);
}

// if ($success) {
//     header("Location: ../pages/admin/apartment_list.php?success=1");
// } else {
//     header("Location: ../pages/admin/apartment_list.php?error=1");
// }
// exit();

if (isset($_GET['id'])) {
    $redirectUrl = "../pages/admin/edit_apartment.php?id=" . (int)$_GET['id'] . "&";
} else {
    $redirectUrl = "../pages/admin/edit_apartment.php?";
}

if ($success) {
    $message = isset($_GET['id']) ? "Apartment updated successfully!" : "Apartment added successfully!";
    $redirectUrl .= "msg=success&text=" . urlencode($message);
} else {
    $message = isset($_GET['id']) ? "Failed to update apartment." : "Failed to add apartment.";
    $redirectUrl .= "msg=error&text=" . urlencode($message);
}

header("Location: $redirectUrl");
exit();
