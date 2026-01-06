<?php
session_start();
require_once '../../php/apartment_model.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$apartments = getAllApartments();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Apartments | NeighborCare</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/user_list.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="admin_body">

<?php include '../../includes/header.php'; ?>

<div class="table-container" style="max-width: 900px;">

    <a href="edit_apartment.php" class="btn primary" style="background-color: #2ecc71; margin-left: 685px; margin-top: -2.5px; position: absolute; padding: 9px;"><i class="fas fa-plus"></i> Add New Apartment</a>

    <h2>Manage Apartments</h2>
    <br/>
    
    <table class="table-data">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Floor</th>
                <th>Block</th>
                <th style="text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($apartments && mysqli_num_rows($apartments) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($apartments)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['floor'] ?></td>
                        <td><?= htmlspecialchars($row['block']) ?></td>
                        <td style="text-align:center;">
                            <a href="edit_apartment.php?id=<?= $row['id'] ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Edit</a>
                            <a href="../../controllers/delete_apartment.php?id=<?= $row['id'] ?>" 
                               class="btn btn-delete"
                               onclick="return confirm('Are you sure you want to delete this apartment?')"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">No apartments found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
</body>
</html>
