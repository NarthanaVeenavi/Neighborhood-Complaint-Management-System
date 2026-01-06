<?php
session_start();

// Optional: you can pass the first name after registration
$first_Name = $_SESSION['first_name'] ?? 'Resident';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | NeighborCare</title>
    <link rel="stylesheet" href="../css/landing_page.css">
    <link rel="stylesheet" href="../css/register.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body class="login_body">

    <!-- Header -->
    <?php include '../includes/header.php'; ?> 

    <!-- Welcome Section -->
    <div class="container" id="welcome_container">
        <div class="welcome-box">
            <h2>Welcome, <?php echo htmlspecialchars($first_Name); ?>!</h2>
            <p>Your account has been successfully created.</p>
            <p>Thank you for joining NeighborCare. You can now log in and start reporting community issues.</p>
            <a href="login.php" class="btn primary">Go to Login</a>
        </div>
    </div>
    <!-- Footer -->
    <?php include '../includes/footer.php'; ?> 
</body>
</html>