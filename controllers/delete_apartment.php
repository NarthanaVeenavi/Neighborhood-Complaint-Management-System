<?php
session_start();
require_once '../php/apartment_model.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../pages/admin/apartment_list.php");
    exit();
}

$id = (int)$_GET['id'];
deleteApartment($id);
header("Location: ../pages/admin/apartment_list.php?deleted=1");
exit();
