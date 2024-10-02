<?php
$servername = "localhost";  // Database server (usually localhost)
$username = "root";         // Database username (default for XAMPP/MAMP is root)
$password = "";             // Database password (default is empty for XAMPP/MAMP)
$dbname = "school_management";  // Name of your database

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
