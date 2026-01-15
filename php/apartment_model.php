<?php
require_once __DIR__ . '/../db/db.php';

function getAllApartments() {
    global $conn;
    $sql = "SELECT * FROM apartments ORDER BY name ASC";
    return mysqli_query($conn, $sql);
}
/**
 * Get paginated list of apartments
 */
function getAllApartmentsPaginated($limit = 10, $offset = 0) {
    global $conn;
    $limit  = (int)$limit;
    $offset = (int)$offset;

    $sql = "SELECT * FROM apartments 
            ORDER BY name ASC 
            LIMIT $limit OFFSET $offset";

    return mysqli_query($conn, $sql);
}

/**
 * Count total number of apartments
 */
function countAllApartments() {
    global $conn;
    $sql = "SELECT COUNT(*) as total FROM apartments";
    $result = mysqli_query($conn, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (int)$row['total'];
    }
    return 0;
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
