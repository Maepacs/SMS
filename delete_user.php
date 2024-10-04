<?php
require 'db_connection.php';

// Get user ID from URL
$user_id = $_GET['id'];

// Check if user ID is valid
if (empty($user_id)) {
    header('Location: manage_users.php?error=invalid_user_id');
    exit();
}

// Delete user from database
$query = "DELETE FROM users WHERE id = '$user_id'";
if (mysqli_query($conn, $query)) {
    header('Location: manage_users.php?success=user_deleted');
    exit();
} else {
    header('Location: manage_users.php?error=deletion_failed');
    exit();
}

close_connection();
?>