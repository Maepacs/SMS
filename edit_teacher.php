<?php
// edit_user.php

session_start();
require 'db_connection.php';

if (isset($_POST['id']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['role'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Update the user information in the database
    $query = "UPDATE users SET username='$username', email='$email', role='$role', updated_at=NOW() WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        header('Location: manage_teacher.php?status=success'); // Redirect with success status
    } else {
        header('Location: manage_teacher.php?status=error'); // Redirect with error status
    }
} else {
    header('Location: manage_teacher.php?status=error'); // Redirect with error status if POST data is missing
}

mysqli_close($conn);
?>
