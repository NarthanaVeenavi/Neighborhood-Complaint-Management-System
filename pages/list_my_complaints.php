<?php
session_start();
require_once '../db/db.php';
require_once '../php/complaints_model.php';

// Check resident login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'resident') {
    header("Location: ../login.php");
    exit();
}

// Get search values
$searchTitle = $_GET['search_title'] ?? '';

// Fetch resident complaints
$complaints = getResidentComplaints($_SESSION['user_id'], $searchTitle);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Complaints</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/user_list.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin_body">

<?php include '../includes/header.php'; ?>   

<div class="table-container">

    <h2>Complaints List</h2>
        <!-- Search Form -->
        <form method="GET" class="search-form-wrapper">
            <div class="search-form">
                <input type="text" name="search_title" placeholder="Search by Title..."
                    value="<?= htmlspecialchars($searchTitle) ?>">
            </div>

            <button type="submit" class="search-btn">Search</button>
             <!-- Clear button -->
            <button type="button" class="clear-btn" onclick="window.location='list_my_complaints.php'">Clear</button>
        </form>


    <!-- Complaints Table -->
    <table class="data-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Priority</th>
            <th>Date of Incident</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        <?php if ($complaints && mysqli_num_rows($complaints) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($complaints)): ?>
                <tr onclick="window.location='view_complaint.php?id=<?= $row['id'] ?>'" style="cursor: pointer;">
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['priority']) ?></td>
                    <td><?= htmlspecialchars($row['incident_date']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td class="actions">
                        <a href="edit_complaint.php?id=<?= $row['id'] ?>" class="edit-btn"><i class="fas fa-edit"></i> Edit</a>
                        <button class="delete-btn" 
                                onclick="event.stopPropagation(); openDeleteModal('delete_complaint.php?id=<?= $row['id'] ?>')">
                                <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="no-data">No complaints found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Delete</h3>
        <p>Are you sure you want to delete this complaint?</p>
        <div class="modal-actions">
            <button class="cancel-btn" onclick="closeModal()">Cancel</button>
            <a id="confirmDeleteBtn" class="confirm-btn">Delete</a>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById("deleteModal");
    const confirmBtn = document.getElementById("confirmDeleteBtn");

    function openDeleteModal(deleteUrl) {
        confirmBtn.href = deleteUrl;
        modal.style.display = "flex";
    }

    function closeModal() {
        modal.style.display = "none";
    }

    window.onclick = function (e) {
        if (e.target === modal) closeModal();
    }
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
