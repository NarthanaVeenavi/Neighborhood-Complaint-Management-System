<?php
require_once __DIR__ . '/../db/db.php';

/* Get resident by ID */
function getResidentById($id) {
    global $conn;

    $sql = "SELECT 
            r.first_name, 
            r.last_name, 
            r.email, 
            r.phone, 
            r.apartment_id,
            r.role,
            r.joining_date,
            a.name AS apartment_name,
            a.block AS apartment_block,
            a.floor AS apartment_floor
        FROM residents r
        LEFT JOIN apartments a ON r.apartment_id = a.id
        WHERE r.id = ?
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

function getAllResidents($nameSearch = "", $phoneSearch = "", $limit = 10, $offset = 0) {
    global $conn;

    $sql = "SELECT 
                r.id,
                r.first_name,
                r.last_name,
                r.email,
                r.phone,
                r.role,
                r.joining_date,
                a.name AS apartment_name,
                a.block,
                a.floor
            FROM residents r
            LEFT JOIN apartments a ON r.apartment_id = a.id
            WHERE r.role = 'resident'";

    if (!empty($nameSearch)) {
        $nameSearch = mysqli_real_escape_string($conn, $nameSearch);
        $sql .= " AND (r.first_name LIKE '%$nameSearch%' OR r.last_name LIKE '%$nameSearch%')";
    }

    if (!empty($phoneSearch)) {
        $phoneSearch = mysqli_real_escape_string($conn, $phoneSearch);
        $sql .= " AND r.phone LIKE '%$phoneSearch%'";
    }

    $sql .= " ORDER BY r.id DESC LIMIT $limit OFFSET $offset";

    return mysqli_query($conn, $sql);
}

function countResidents($nameSearch = "", $phoneSearch = "") {
    global $conn;

    $sql = "SELECT COUNT(*) as total FROM residents r WHERE r.role = 'resident'";

    if (!empty($nameSearch)) {
        $nameSearch = mysqli_real_escape_string($conn, $nameSearch);
        $sql .= " AND (r.first_name LIKE '%$nameSearch%' OR r.last_name LIKE '%$nameSearch%')";
    }

    if (!empty($phoneSearch)) {
        $phoneSearch = mysqli_real_escape_string($conn, $phoneSearch);
        $sql .= " AND r.phone LIKE '%$phoneSearch%'";
    }

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    return (int)$row['total'];
}

/* Get resident by ID (Admin) */
function getResidentByIdAdmin($id) {
    global $conn;
    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, first_name, last_name, email, phone, apartment_id, role, joining_date
         FROM residents WHERE id=? LIMIT 1"
    );
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}
function updateResidentProfile($id, $first, $last, $email, $phone, $apt, $joining_date) {
    global $conn;

    $sql = "UPDATE residents 
            SET first_name=?, last_name=?, email=?, phone=?, apartment_id=?, joining_date=? 
            WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssisi",
        $first,
        $last,
        $email,
        $phone,
        $apt,
        $joining_date,
        $id
    );

    return mysqli_stmt_execute($stmt);
}


/* Update resident by Admin */
function updateResidentByAdmin($id, $first, $last, $email, $phone, $apt, $role, $joining_date) {
    global $conn;

    $sql = "UPDATE residents 
            SET first_name=?, last_name=?, email=?, phone=?, apartment_id=?, role=?, joining_date=?
            WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssissi",
        $first,
        $last,
        $email,
        $phone,
        $apt,
        $role,
        $joining_date,
        $id
    );

    return mysqli_stmt_execute($stmt);
}

/* Delete resident */
function deleteResident($id) {
    global $conn;
    $id = (int)$id;
    $sql = "DELETE FROM residents WHERE id = $id LIMIT 1";
    return mysqli_query($conn, $sql);
}
