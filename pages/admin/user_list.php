<?php
session_start();
require_once '../../db/db.php';
require_once '../../php/user_model.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$nameSearch  = $_GET['search_name'] ?? '';
$phoneSearch = $_GET['search_phone'] ?? '';

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$result = getAllResidents($nameSearch, $phoneSearch, $limit, $offset);
$totalResidents = countResidents($nameSearch, $phoneSearch);
$totalPages = ceil($totalResidents / $limit);

$start = $offset + 1;
$end = min($offset + $limit, $totalResidents);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Residents</title>

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
        <h2>Residents List</h2>
        <form method="GET" class="search-form-wrapper">
            <div class="search-form">
                <input type="text" name="search_name" placeholder="Search by first or last name..." value="<?= htmlspecialchars($nameSearch) ?>">
            </div>
            <div class="search-form">
                <input type="text" name="search_phone" placeholder="Search by phone..." value="<?= htmlspecialchars($phoneSearch) ?>">
            </div>
            <button type="submit" class="search-btn">Search</button>
            <button type="button" class="clear-btn" onclick="window.location='user_list.php'" style="padding: 12px 20px;">Clear</button>
        </form>
    </div>

    <div class="table-scroll">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Resident</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Apartment</th>
                    <th>Joined</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$row['id']; ?></td>
                        <td class="name-cell">
                            <strong>
                                <?= htmlspecialchars($row['first_name'] ?? ''); ?>
                                <?= htmlspecialchars($row['last_name'] ?? ''); ?>
                            </strong>
                        </td>
                        <td><?= htmlspecialchars($row['email'] ?? '—'); ?></td>
                        <td><?= htmlspecialchars($row['phone'] ?? '—'); ?></td>
                        <td>
                            <?php if (!empty($row['apartment_name'])): ?>
                                <?= htmlspecialchars($row['apartment_name']) ?>
                                <?php if (!empty($row['block'])): ?> | Block <?= htmlspecialchars($row['block']) ?><?php endif; ?>
                                <?php if (!empty($row['floor'])): ?> | Floor <?= htmlspecialchars($row['floor']) ?><?php endif; ?>
                            <?php else: ?>
                                <em>Not Assigned</em>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['joining_date'] ?? '—'); ?></td>
                        <td class="action-cell" style="text-align:center;">
                            <a href="edit_resident.php?id=<?= $row['id']; ?>" 
                               class="btn btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button class="btn btn-delete" style="border: none;"
                                    onclick="event.stopPropagation(); openDeleteModal('../../controllers/delete_resident.php?id=<?= $row['id'] ?>')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="no-data">
                        No residents found
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <div class="table-info">
            Showing <?= $start ?>–<?= $end ?> of <?= $totalResidents ?> residents
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page-1 ?>&search_name=<?= urlencode($nameSearch) ?>&search_phone=<?= urlencode($phoneSearch) ?>" class="nav-btn">« Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>&search_name=<?= urlencode($nameSearch) ?>&search_phone=<?= urlencode($phoneSearch) ?>"
                   class="<?= ($i == $page) ? 'active' : '' ?>">
                   <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page+1 ?>&search_name=<?= urlencode($nameSearch) ?>&search_phone=<?= urlencode($phoneSearch) ?>" class="nav-btn">Next »</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Delete</h3>
        <p>Are you sure you want to delete this Resident?</p>
        <div class="modal-actions">
            <button class="cancel-btn" onclick="closeModal()">Cancel</button>
            <a id="confirmDeleteBtn" class="confirm-btn">Delete</a>
        </div>
    </div>
</div>

<script src="../../js/delete_model.js"></script>

<?php include '../../includes/footer.php'; ?>

</body>
</html>