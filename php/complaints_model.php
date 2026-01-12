<?php
require_once __DIR__ . '/../db/db.php';

// Get all complaints
function getAllComplaints($searchName = "", $searchTitle = "") {
    global $conn;

    $sql = "SELECT 
                c.id,
                c.title,
                c.category,
                r.first_name,
                r.last_name,
                r.phone
            FROM complaints c
            JOIN residents r ON c.resident_id = r.id
            WHERE 1=1";

    if (!empty($searchName)) {
        $searchName = mysqli_real_escape_string($conn, $searchName);
        $sql .= " AND (r.first_name LIKE '%$searchName%' OR r.last_name LIKE '%$searchName%')";
    }

    if (!empty($searchTitle)) {
        $searchTitle = mysqli_real_escape_string($conn, $searchTitle);
        $sql .= " AND c.title LIKE '%$searchTitle%'";
    }

    $sql .= " ORDER BY c.id DESC";

    return mysqli_query($conn, $sql);
}

// Add complaint
function createComplaint($resident_id, $title, $category, $description, $priority, $location, $incident_date) {
    global $conn;

    $sql = "INSERT INTO complaints 
            (resident_id, title, category, description, priority, location, incident_date)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "issssis",
        $resident_id,
        $title,
        $category,
        $description,
        $priority,
        $location,      // apartment_id saved into location column
        $incident_date
    );

    return mysqli_stmt_execute($stmt);
}


/* READ - All complaints by user */
function getComplaintById($id) {
    global $conn;

    $sql = "SELECT 
                c.*, 
                r.first_name, 
                r.last_name, 
                r.phone
            FROM complaints c
            JOIN residents r ON c.resident_id = r.id
            WHERE c.id = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

/* UPDATE status */
function updateComplaintStatus($id, $status) {
    global $conn;

    $stmt = mysqli_prepare($conn,
        "UPDATE complaints SET status=? WHERE id=?"
    );
    mysqli_stmt_bind_param($stmt, "si", $status, $id);

    return mysqli_stmt_execute($stmt);
}

/* DELETE */
function deleteComplaint($id) {
    global $conn;

    $stmt = mysqli_prepare($conn, "DELETE FROM complaints WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    return mysqli_stmt_execute($stmt);
}

/* Get all complaints by resident */
function getResidentComplaints($resident_id, $searchTitle = '') {
    global $conn;
    $sql = "SELECT * FROM complaints 
            WHERE resident_id = ?";

    if ($searchTitle !== '') {
        $sql .= " AND title LIKE ?";
    }

    $sql .= " ORDER BY created_at DESC";

    $stmt = mysqli_prepare($conn, $sql);

    if ($searchTitle !== '') {
        $likeTitle = "%$searchTitle%";
        mysqli_stmt_bind_param($stmt, "is", $resident_id, $likeTitle);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $resident_id);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
}
