<?php
session_start();
require_once '../../db/db.php';
require_once '../../php/user_model.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include 'db.php';

// Initialize search variables
$searchName = "";
$searchTitle = "";

if (isset($_GET['search_name'])) {
    $searchName = $_GET['search_name'];
}
if (isset($_GET['search_title'])) {
    $searchTitle = $_GET['search_title'];
}

// Update SQL query to filter by name and title
$sql = "SELECT * FROM complaints 
        WHERE user_name LIKE '%$searchName%' 
        AND complaint_title LIKE '%$searchTitle%'
        ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complaint List</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
<div class="container">
    <h2>Complaint List</h2>

    <!-- Search Form -->
    <form method="GET" class="search-form-wrapper">
        <div class="search-form">
            <label class="search-label">Complaint Name</label>
            <input type="text" name="search_name" placeholder="Search by name..."
                value="<?= htmlspecialchars($searchName) ?>">
        </div>
        <div class="search-form">
            <label class="search-label">Complaint Title</label>
            <input type="text" name="search_title" placeholder="Search by title..."
                value="<?= htmlspecialchars($searchTitle) ?>">
        </div>
    <button type="submit" class="search-btn">Search</button>
    </form>



    <table class="complaint-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Title</th>
                <th>Category</th>
                <th>Actions</th>

            </tr>
        </thead>
        <tbody>
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['contact_no']) ?></td>
                <td><?= htmlspecialchars($row['complaint_title']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>

                <td class="actions">
                    <a href="edit_complaint.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                    <button class="delete-btn"
                    onclick="openDeleteModal('delete_complaint.php?id=<?= $row['id'] ?>')">
                    Delete
                    </button>
                </td>
            </tr>
            <?php } ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="no-data">No complaints found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

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
      
    <a href="add_complaint.php" class="add-btn">Add New Complaint</a>
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

    // Close modal when clicking outside
    window.onclick = function (e) {
        if (e.target === modal) {
            closeModal();
        }
    };
</script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
