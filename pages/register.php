<?php
require_once '../php/apartment_model.php';
$apartments = getAllApartments();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" href="../css/register.css">
        <link rel="stylesheet" href="../css/main.css">
    </head>
    <body class="reg_body"> 
        <!-- Header -->
        <?php include '../includes/header.php'; ?> 

        <div class="container">
            <h2>User Registration</h2>
            <form method="post" action="../php/register.php" onsubmit="return validateForm();">

                <label>First Name</label>
                <input type="text" name="first_name" required>

                <label>Last Name</label>
                <input type="text" name="last_name" required>

                <label>Apartment</label>
                <select name="apartment_id" required>
                    <option value="">-- Select Apartment --</option>

                    <?php while ($apt = $apartments->fetch_assoc()): ?>
                        <option value="<?= $apt['id'] ?>">
                            <?= htmlspecialchars($apt['name']) ?>
                            | Block <?= htmlspecialchars($apt['block']) ?>
                            | Floor <?= htmlspecialchars($apt['floor']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label>Phone</label>
                <input type="text" name="phone">

                <label>Email</label>
                <input type="email" name="email" required>

                <label>Joining Date</label>
                <input type="date" name="joining_date" required>

                <label>Password</label>
                <input type="password" name="password" id="password" required>

                <label>Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>

                <button type="submit">Register</button>
            </form>

            <div class="link">
                <a href="login.php">Already have an account? Login</a>
            </div>
        </div>

        <script src="../js/register.js"></script>
        <?php include '../includes/footer.php'; ?>
    </body>
</html>
