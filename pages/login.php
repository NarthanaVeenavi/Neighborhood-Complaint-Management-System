<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | NeighborCare</title>

    <!-- Reuse existing styles -->
    <link rel="stylesheet" href="../css/register.css">
    <link rel="stylesheet" href="../css/landing_page.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/main.css">
</head>

<body class="login_body">

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <!-- Login Form -->
    <div class="container" id="login_container">
        <h2>Resident Login</h2>

        <form method="post" action="../php/login.php">

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <div class="link">
            <a href="register.php">Don’t have an account? Register</a>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
