<?php
// register_user.php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data from POST request
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role'];

    // Insert the new user into the database
    $query = "INSERT INTO users (email, username, password_hash, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $email, $username, $password, $role);

    // Execute the query and send a JSON response
   if ($stmt->execute()) {
    echo "<script>alert('User  has been added successfully.'); window.location.href='manage_users.php';</script>";
} else {
    echo "<script>alert('Failed to add user.');</script>";
}
    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
}
?>
