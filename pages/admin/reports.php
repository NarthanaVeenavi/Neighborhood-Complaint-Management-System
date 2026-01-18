<?php
session_start();
require_once '../../db/db.php';
require_once '../../php/complaints_model.php';

// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../pages/login.php");
    exit();
}

// Fetch data for charts
$totalComplaints = getTotalComplaints();
$byCategory = getComplaintsByCategory();
$byPriority = getComplaintsByPriority();
$byStatus   = getComplaintsByStatus();

// Prepare arrays for Chart.js
$categoryLabels = $categoryData = [];
while ($row = $byCategory->fetch_assoc()) {
    $categoryLabels[] = $row['category'];
    $categoryData[] = (int)$row['count'];
}

$priorityLabels = $priorityData = [];
while ($row = $byPriority->fetch_assoc()) {
    $priorityLabels[] = $row['priority'];
    $priorityData[] = (int)$row['count'];
}

$statusLabels = $statusData = [];
while ($row = $byStatus->fetch_assoc()) {
    $statusLabels[] = $row['status'];
    $statusData[] = (int)$row['count'];
}

// Default values
$pendingCount = 0;
$resolvedCount = 0;
$inProgressCount = 0;
$rejectedCount = 0;
$closedCount = 0;

$highCount = 0;
$mediumCount = 0;
$lowCount = 0;

// Calculate from status data
foreach ($statusLabels as $i => $status) {
    if (strtolower($status) === 'pending') {
        $pendingCount = $statusData[$i];
    }
    if (strtolower($status) === 'resolved') {
        $resolvedCount = $statusData[$i];
    }
    if (strtolower($status) === 'in progress') {
        $inProgressCount = $statusData[$i];
    }
    if (strtolower($status) === 'rejected') {
        $rejectedCount = $statusData[$i];
    }
    if (strtolower($status) === 'closed') {
        $closedCount = $statusData[$i];
    }
}

// Calculate from priority data
foreach ($priorityLabels as $i => $priority) {
    if (strtolower($priority) === 'high') {
        $highCount = $priorityData[$i];
    }
    if (strtolower($priority) === 'medium') {
        $mediumCount = $priorityData[$i];
    }
    if (strtolower($priority) === 'low') {
        $lowCount = $priorityData[$i];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Reports - Complaints Summary</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/admin_reports.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin_body">
<?php include '../../includes/header.php'; ?>

<div class="report-cards" style="margin-top: 100px; max-width: 90%;">

    <div class="report-card">
        <h4>Total Complaints</h4>
        <p><?= $totalComplaints ?></p>
    </div>

    <div class="report-card pending">
        <h4>Pending</h4>
        <p><?= $pendingCount ?></p>
    </div>

    <div class="report-card resolved">
        <h4>Resolved</h4>
        <p><?= $resolvedCount ?></p>
    </div>

    <div class="report-card in-progress">
        <h4>In Progress</h4>
        <p><?= $inProgressCount ?></p>  
    </div>

    <div class="report-card rejected">
        <h4>Rejected</h4>
        <p><?= $rejectedCount ?></p>
    </div>

    <div class="report-card closed">
        <h4>Closed</h4>
        <p><?= $closedCount ?></p>  
    </div>

    <div class="report-card high">
        <h4>High Priority</h4>
        <p><?= $highCount ?></p>
    </div>

    <div class="report-card medium">
        <h4>Medium Priority</h4>
        <p><?= $mediumCount ?></p>
    </div>

    <div class="report-card low">
        <h4>Low Priority</h4>
        <p><?= $lowCount ?></p>
    </div>

</div>

<div class="container" style="margin-top: 30px; margin-bottom: 50px; max-width: 90%;">
    <h2 style="text-align:center; margin-bottom:20px;">Admin Reports - Complaints Summary</h2>

    <div class="charts-container" style="margin-left: 65px;">
        <div class="chart-card">
            <canvas id="categoryChart"></canvas>
        </div>
        <div class="chart-card">
            <canvas id="priorityChart"></canvas>
        </div>
        <div class="chart-card">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<script>
// Chart.js data from PHP
const categoryLabels = <?= json_encode($categoryLabels) ?>;
const categoryData = <?= json_encode($categoryData) ?>;

const priorityLabels = <?= json_encode($priorityLabels) ?>;
const priorityData = <?= json_encode($priorityData) ?>;

const statusLabels = <?= json_encode($statusLabels) ?>;
const statusData = <?= json_encode($statusData) ?>;

// Category Chart (Bar)
new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: categoryLabels,
        datasets: [{
            label: 'Complaints by Category',
            data: categoryData,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

// Priority Chart (Pie)
new Chart(document.getElementById('priorityChart'), {
    type: 'pie',
    data: {
        labels: priorityLabels,
        datasets: [{
            data: priorityData,
            backgroundColor: [
                'rgba(75, 192, 192, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(255, 99, 132, 0.7)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: { responsive: true }
});

// Status Chart (Doughnut)
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusData,
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: { responsive: true }
});
</script>

<?php include '../../includes/footer.php'; ?>
</body>
</html>
