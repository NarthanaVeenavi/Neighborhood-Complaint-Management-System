<?php
session_start();
require_once '../../db/db.php';
require_once '../../php/user_model.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$result = getAllResidents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Residents</title>

    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/landing_page.css">
    <link rel="stylesheet" href="../../css/user_list.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="admin_body">

<?php include '../../includes/header.php'; ?>

<div class="table-container">
    <h2>Residents List</h2>
    <br/>

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
                        <?= htmlspecialchars($row['first_name']); ?>
                        <?= htmlspecialchars($row['last_name']); ?>
                    </strong>
                </td>

                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= htmlspecialchars($row['phone']); ?></td>
                 <td>
                    <?php if ($row['apartment_name']): ?>
                        <?= htmlspecialchars($row['apartment_name']) ?>
                        | Block <?= htmlspecialchars($row['block']) ?>
                        | Floor <?= htmlspecialchars($row['floor']) ?>
                    <?php else: ?>
                        <em>Not Assigned</em>
                    <?php endif; ?>
                </td>

                <td><?= htmlspecialchars($row['joining_date']); ?></td>

                <td class="action-cell">
                    <a href="edit_resident.php?id=<?= $row['id']; ?>" 
                       class="btn btn-edit"><i class="fas fa-edit"></i> Edit</a>

                    <a href="../../controllers/delete_resident.php?id=<?= $row['id'] ?>" 
                       class="btn btn-delete"
                       onclick="return confirm('Are you sure you want to delete this resident?');">
                       <i class="fas fa-trash"></i> Delete
                    </a>
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

<?php include '../../includes/footer.php'; ?>

</body>
</html>
