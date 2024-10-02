<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: admin_login.php');
    exit();
}

require 'db_connection.php'; // Connect to your database

// Fetch users from the database
$query = "SELECT id, username, email, role, created_at, updated_at FROM users";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <!-- Include DataTables CSS and jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        /* Navbar styles */
        .navbar {
            background-color: #333;
            color: white;
            padding: 1rem;
            text-align: center;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        /* Side panel styles */
        .side-panel {
            background-color: #2c3e50;
            width: 250px;
            padding-top: 60px; /* Offset to account for fixed navbar */
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            color: white;
        }

        .side-panel a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .side-panel a:hover {
            background-color: #34495e;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            padding-top: 80px; /* Offset to account for fixed navbar */
            flex: 1;
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        table, th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        a.button {
            padding: 5px 10px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        a.button:hover {
            background-color: #2980b9;
        }

        .delete-button {
            background-color: #e74c3c;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

        /* Button to register new user */
        .register-btn {
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 20px;
            background-color: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
        }

        .register-btn:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <h1>Manage Users</h1>
    </div>

    <!-- Side panel -->
    <div class="side-panel">
    <a href="admin_dashboard.php"><i class="ri-dashboard-line"></i> Dashboard</a>
    <a href="manage_users.php"><i class="ri-user-3-fill"></i> Manage Users</a>
    <a href="manage_students.php"><i class="ri-user-5-fill"></i> Manage Students</a>
    <a href="manage_teachers.php"><i class="ri-user-2-fill"></i> Manage Teachers</a>
    <a href="view_reports.php"><i class="ri-file-copy-2-line"></i> View Reports</a>
    <a href="settings.php"><i class="ri-equalizer-line"></i> Settings</a>
    <a href="logout.php"><i class="ri-logout-box-line"></i> Logout</a>
    </div>

    <!-- Main content -->
    <div class="content">
        <h2>User Management</h2>

        <!-- Register button -->
        <a href="register.php" class="register-btn">Register New User</a>

        <!-- User table -->
        <table id="usersTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['role']}</td>
                                <td>{$row['created_at']}</td>
                                <td>{$row['updated_at']}</td>
                                <td class='actions'>
                                    <a href='edit_user.php?id={$row['id']}' class='button'>Edit</a>
                                    <a href='delete_user.php?id={$row['id']}' class='button delete-button' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Include jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#usersTable').DataTable();
        });
    </script>
</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
