<?php
include '../db/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $apartment_id = mysqli_real_escape_string($conn, $_POST['apartment_id']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $joining_date = mysqli_real_escape_string($conn, $_POST['joining_date']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO residents (first_name, last_name, apartment_id, phone, email, joining_date, password)
            VALUES ('$first_name', '$last_name', '$apartment_id', '$phone', '$email', '$joining_date', '$password')";
    if(mysqli_query($conn, $sql)){
        header("Location: ../pages/welcome_page.php");
    } else {
        echo "Error: ".mysqli_error($conn);
    }
}
?>
