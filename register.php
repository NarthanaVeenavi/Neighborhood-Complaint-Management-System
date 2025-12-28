<?php
include './db/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $apartment_no = mysqli_real_escape_string($conn, $_POST['apartment_no']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO residents (first_name, last_name, apartment_no, phone, email, password)
            VALUES ('$name', '$last_name', '$apartment_no', '$phone', '$email', '$password')";

    if(mysqli_query($conn, $sql)){
        echo "Registration successful! <a href='login.html'>Login here</a>";
    } else {
        echo "Error: ".mysqli_error($conn);
    }
}
?>
