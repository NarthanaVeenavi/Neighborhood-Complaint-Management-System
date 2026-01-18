<?php
session_start();
require_once '../../php/apartment_model.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Pagination & search (you can add name/block search later if needed)
$page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit  = 10; // you can adjust this number
$offset = ($page - 1) * $limit;

$apartments     = getAllApartmentsPaginated($limit, $offset);
$totalApartments = countAllApartments();
$totalPages     = ceil($totalApartments / $limit);

$start = $offset + 1;
$end   = min($offset + $limit, $totalApartments);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Apartments | NeighborCare</title>

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
        <h2>Manage Apartments</h2>

        <div style="margin-left: auto;">
            <a href="edit_apartment.php" class="btn" style="background: #2ecc71; color: white;">
                <i class="fas fa-plus"></i> Add New Apartment
            </a>
        </div>
    </div>

    <div class="table-scroll">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th style="text-align:center;">Floor</th>
                    <th style="text-align:center;">Block</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($apartments && mysqli_num_rows($apartments) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($apartments)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name'] ?? '—') ?></td>
                        <td style="text-align:center;"><?= htmlspecialchars($row['floor'] ?? '—') ?></td>
                        <td style="text-align:center;"><?= htmlspecialchars($row['block'] ?? '—') ?></td>
                        <td class="action-cell" style="text-align:center;">
                            <a href="edit_apartment.php?id=<?= $row['id'] ?>" class="btn btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button class="btn btn-delete" style="border: none;"
                                    onclick="event.stopPropagation(); openDeleteModal('../../controllers/delete_apartment.php?id=<?= $row['id'] ?>')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="no-data">No apartments found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <div class="table-info">
            Showing <?= $start ?>–<?= $end ?> of <?= $totalApartments ?> apartments
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page-1 ?>" class="nav-btn">« Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>"
                   class="<?= ($i == $page) ? 'active' : '' ?>">
                   <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page+1 ?>" class="nav-btn">Next »</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Confirm Delete</h3>
        <p>Are you sure you want to delete this Apartment?</p>
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