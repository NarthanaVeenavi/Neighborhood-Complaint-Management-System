<?php
session_start();
require_once '../../db/db.php';
require_once '../../php/complaints_model.php';

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$searchName  = $_GET['search_name']  ?? '';
$searchTitle = $_GET['search_title'] ?? '';

$page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit  = 10;
$offset = ($page - 1) * $limit;

// Assuming your model functions support pagination and return total count
$complaints    = getAllComplaints($searchName, $searchTitle, $limit, $offset);
$totalComplaints = countComplaints($searchName, $searchTitle);   // ← you need to implement this
$totalPages    = ceil($totalComplaints / $limit);

$start = $offset + 1;
$end   = min($offset + $limit, $totalComplaints);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints List - Admin</title>

    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/landing_page.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/user_list.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="admin_body">

<?php include '../../includes/header.php'; ?>

<div class="table-container" style="margin-top: 100px; width: 80%;">
    <div class="table-header">
        <h2>Complaints List</h2>
        <form method="GET" class="search-form-wrapper">
            <div class="search-form">
                <input type="text" name="search_name" placeholder="Search by resident name..." value="<?= htmlspecialchars($searchName) ?>">
            </div>
            <div class="search-form">
                <input type="text" name="search_title" placeholder="Search by complaint title..." value="<?= htmlspecialchars($searchTitle) ?>">
            </div>
            <button type="submit" class="search-btn">Search</button>
            <button type="button" class="clear-btn" onclick="window.location='list_complaints.php'">Clear</button>
        </form>
    </div>

    <div class="table-scroll">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Resident</th>
                    <th>Location</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($complaints && mysqli_num_rows($complaints) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($complaints)): ?>
                    <tr class="viewable" onclick="window.location='view_complaint.php?id=<?= $row['id'] ?>'">
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></strong>
                        </td>
                        <td><?= htmlspecialchars($row['location'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['category'] ?? '—') ?></td>
                        <td>
                            <span class="status-<?= strtolower($row['status'] ?? 'pending') ?>">
                                <?= htmlspecialchars($row['status'] ?? 'Pending') ?>
                            </span>
                        </td>
                        <td class="action-cell actions" style="text-align:center;">
                            <a href="edit_complaint.php?id=<?= $row['id'] ?>" class="btn btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button class="btn btn-delete" style="border: none;"
                                    onclick="event.stopPropagation(); openDeleteModal('../../controllers/delete_complaint.php?id=<?= $row['id'] ?>')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="no-data">No complaints found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <div class="table-info">
            Showing <?= $start ?>–<?= $end ?> of <?= $totalComplaints ?> complaints
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page-1 ?>&search_name=<?= urlencode($searchName) ?>&search_title=<?= urlencode($searchTitle) ?>" class="nav-btn">« Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>&search_name=<?= urlencode($searchName) ?>&search_title=<?= urlencode($searchTitle) ?>"
                   class="<?= ($i == $page) ? 'active' : '' ?>">
                   <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page+1 ?>&search_name=<?= urlencode($searchName) ?>&search_title=<?= urlencode($searchTitle) ?>" class="nav-btn">Next »</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
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
function openDeleteModal(deleteUrl) {
    document.getElementById("confirmDeleteBtn").href = deleteUrl;
    document.getElementById("deleteModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("deleteModal").style.display = "none";
}

window.onclick = function(e) {
    if (e.target === document.getElementById("deleteModal")) {
        closeModal();
    }
}
</script>

<?php include '../../includes/footer.php'; ?>

</body>
</html>