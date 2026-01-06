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

if ($success) {
    header("Location: ../pages/admin/apartment_list.php?success=1");
} else {
    header("Location: ../pages/admin/apartment_list.php?error=1");
}
exit();
