<?php
require_once __DIR__ . '/../db/db.php';

// Get all complaints
function getAllComplaints($searchName = "", $searchTitle = "", $limit = 10, $offset = 0) {
    global $conn;

    $searchName  = mysqli_real_escape_string($conn, trim($searchName));
    $searchTitle = mysqli_real_escape_string($conn, trim($searchTitle));

    $sql = "SELECT 
                c.id,
                c.title,
                c.category,
                r.first_name,
                r.last_name,
                CONCAT(a.name, ' | Block ', a.block, ' | Floor ', a.floor) AS location,
                c.status
            FROM complaints c
            JOIN residents r ON c.resident_id = r.id
            LEFT JOIN apartments a ON c.location = a.id
            WHERE 1=1";

    if ($searchName !== '') {
        $sql .= " AND (r.first_name LIKE '%$searchName%' OR r.last_name LIKE '%$searchName%')";
    }

    if ($searchTitle !== '') {
        $sql .= " AND c.title LIKE '%$searchTitle%'";
    }

    $sql .= " ORDER BY c.id DESC";
    $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

    $result = mysqli_query($conn, $sql);

    if ($result === false) {
        // Optional: log error in production
        // error_log("getAllComplaints query failed: " . mysqli_error($conn));
    }

    return $result;
}

// Count total complaints for pagination
function countComplaints($searchName = "", $searchTitle = "") {
    global $conn;

    $searchName  = mysqli_real_escape_string($conn, trim($searchName));
    $searchTitle = mysqli_real_escape_string($conn, trim($searchTitle));

    $sql = "SELECT COUNT(*) as total
            FROM complaints c
            JOIN residents r ON c.resident_id = r.id
            WHERE 1=1";

    if ($searchName !== '') {
        $sql .= " AND (r.first_name LIKE '%$searchName%' OR r.last_name LIKE '%$searchName%')";
    }

    if ($searchTitle !== '') {
        $sql .= " AND c.title LIKE '%$searchTitle%'";
    }

    $result = mysqli_query($conn, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        return (int)$row['total'];
    }

    return 0;
}

// Add complaint
function createComplaint($resident_id, $title, $category, $description, $priority, $apartment_id, $incident_date, $attachment = null) {
    global $conn;

    $sql = "INSERT INTO complaints
            (resident_id, title, category, description, priority, location, incident_date, attachment)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "isssssss", // s = string, i = integer
        $resident_id,
        $title,
        $category,
        $description,
        $priority,
        $apartment_id, // save apartment ID in location
        $incident_date,
        $attachment // filename can be null
    );

    return mysqli_stmt_execute($stmt);
}

// Get single complaint
function getComplaintById($id) {
    global $conn;

    $sql = "SELECT 
                c.id,
                c.title,
                c.category,
                c.description, 
                c.priority,
                c.incident_date,
                c.status,
                c.comment,
                c.location AS apartment_id,  -- location column stores apartment ID
                CONCAT(a.name, ' | Block ', a.block, ' | Floor ', a.floor) AS apartment_name, -- display

                r.first_name, 
                r.last_name, 
                r.phone
            FROM complaints c
            JOIN residents r ON c.resident_id = r.id
            LEFT JOIN apartments a ON c.location = a.id
            WHERE c.id = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

/* UPDATE status */
function updateComplaintStatusandComment($id, $status, $comment) {
    global $conn;

    $stmt = mysqli_prepare($conn,
        "UPDATE complaints SET status=?, comment=? WHERE id=?"
    );
    mysqli_stmt_bind_param($stmt, "ssi", $status, $comment, $id);

    return mysqli_stmt_execute($stmt);
}

/* DELETE */
function deleteComplaint($id) {
    global $conn;

    $stmt = mysqli_prepare($conn, "DELETE FROM complaints WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    return mysqli_stmt_execute($stmt);
}

/**
 * Get paginated complaints for a specific resident
 */
function getResidentComplaintsPaginated($resident_id, $searchTitle = '', $limit = 10, $offset = 0) {
    global $conn;

    $sql = "SELECT * FROM complaints 
            WHERE resident_id = ?";

    $params = [$resident_id];
    $types  = "i";

    if ($searchTitle !== '') {
        $sql .= " AND title LIKE ?";
        $params[] = "%$searchTitle%";
        $types   .= "s";
    }

    $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = (int)$limit;
    $params[] = (int)$offset;
    $types   .= "ii";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
}

/**
 * Count total complaints for a resident (with optional title filter)
 */
function countResidentComplaints($resident_id, $searchTitle = '') {
    global $conn;

    $sql = "SELECT COUNT(*) as total FROM complaints WHERE resident_id = ?";
    $params = [$resident_id];
    $types  = "i";

    if ($searchTitle !== '') {
        $sql .= " AND title LIKE ?";
        $params[] = "%$searchTitle%";
        $types   .= "s";
    }

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row    = mysqli_fetch_assoc($result);

    return (int)($row['total'] ?? 0);
}

// Update complaint details
function updateComplaint($id, $title, $category, $description, $location, $priority, $incident_date, $attachment) {
    global $conn;

    $apartment_id = isset($_POST['apartment_id']) ? (int)$_POST['apartment_id'] : null;

$sql = "UPDATE complaints
        SET title=?, category=?, description=?, apartment_id=?, incident_date=?, priority=?, attachment=?
        WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssssi",
    $title,
    $category,
    $description,
    $apartment_id,
    $incident_date,
    $priority,
    $attachment,
    $complaint_id
);
$stmt->execute();
    return $stmt->execute();
}




/*Summaries*/
// Total complaints
function getTotalComplaints() {
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM complaints";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result)['total'] ?? 0;
}

// Complaints by category
function getComplaintsByCategory() {
    global $conn;
    $sql = "SELECT category, COUNT(*) AS count 
            FROM complaints 
            GROUP BY category";
    return mysqli_query($conn, $sql);
}

// Complaints by priority
function getComplaintsByPriority() {
    global $conn;
    $sql = "SELECT priority, COUNT(*) AS count 
            FROM complaints 
            WHERE status != 'Resolved'
            GROUP BY priority";
            
    return mysqli_query($conn, $sql);
}

// Complaints by status
function getComplaintsByStatus() {
    global $conn;
    $sql = "SELECT status, COUNT(*) AS count 
            FROM complaints 
            GROUP BY status";
    return mysqli_query($conn, $sql);
}

// Complaints per apartment
function getComplaintsByApartment() {
    global $conn;
    $sql = "SELECT a.name AS apartment_name, a.block, a.floor, COUNT(c.id) AS count
            FROM complaints c
            LEFT JOIN apartments a ON c.location = a.id
            GROUP BY c.location";
    return mysqli_query($conn, $sql);
}

// Recent complaints (last 10)
function getRecentComplaints($limit = 10) {
    global $conn;
    $sql = "SELECT c.id, c.title, c.category, c.priority, c.status, 
                   CONCAT(r.first_name,' ',r.last_name) AS resident_name,
                   a.name AS apartment_name, a.block, a.floor, c.incident_date
            FROM complaints c
            JOIN residents r ON c.resident_id = r.id
            LEFT JOIN apartments a ON c.location = a.id
            WHERE c.status != 'Resolved'
            ORDER BY c.id DESC
            LIMIT ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $limit);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}


