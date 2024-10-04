<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: admin_login.php');
    exit();
}

require 'db_connection.php'; // Connect to your database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = mysqli_real_escape_string($conn, $_POST['id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Update user in the database
    $query = "UPDATE users SET username='$username', email='$email', role='$role', updated_at=NOW() WHERE id='$userId'";

    if (mysqli_query($conn, $query)) {
        header('Location: manage_users.php?status=success');
        exit();
    } else {
        header('Location: manage_users.php?status=error');
        exit();
    }
}
?>
