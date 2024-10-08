<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: admin_login.php');
    exit();
}

require 'db_connection.php'; // Connect to your database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Make sure to get the password from the form
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Insert new user into the database
    $query = "INSERT INTO users (username, email, password_hash, role, created_at) VALUES ('$username', '$email', '$hashedPassword', '$role', NOW())";

    if (mysqli_query($conn, $query)) {
        header('Location: manage_users.php?status=success');
        exit();
    } else {
        header('Location: manage_users.php?status=error');
        exit();
    }
}
?>
