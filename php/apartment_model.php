<?php
require_once __DIR__ . '/../db/db.php';

// Get all apartments
function getAllApartments() {
    global $conn;
    $sql = "SELECT * FROM apartments ORDER BY created_at DESC";
    return mysqli_query($conn, $sql);
}

// Get single apartment
function getApartmentById($id) {
    global $conn;
    $id = (int)$id;
    $sql = "SELECT * FROM apartments WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

// Add apartment
function addApartment($name, $floor, $block) {
    global $conn;
    $name = mysqli_real_escape_string($conn, $name);
    $floor = (int)$floor;
    $block = mysqli_real_escape_string($conn, $block);

    $sql = "INSERT INTO apartments (name, floor, block) VALUES ('$name', $floor, '$block')";
    return mysqli_query($conn, $sql);
}

// Update apartment
function updateApartment($id, $name, $floor, $block) {
    global $conn;
    $id = (int)$id;
    $name = mysqli_real_escape_string($conn, $name);
    $floor = (int)$floor;
    $block = mysqli_real_escape_string($conn, $block);

    $sql = "UPDATE apartments SET name='$name', floor=$floor, block='$block' WHERE id=$id LIMIT 1";
    return mysqli_query($conn, $sql);
}

// Delete apartment
function deleteApartment($id) {
    global $conn;
    $id = (int)$id;
    $sql = "DELETE FROM apartments WHERE id=$id LIMIT 1";
    return mysqli_query($conn, $sql);
}
