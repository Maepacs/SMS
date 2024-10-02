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
            transition: background-color 0.3s;
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
            border-collapse: collapse;
        }

        table, th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
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
            transition: background-color 0.3s;
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
            transition: background-color 0.3s;
        }

        .register-btn:hover {
            background-color: #27ae60;
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1001; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.5); /* Black w/ opacity */
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 400px; /* Set a fixed width */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0,0,0,0.2); /* Subtle shadow */
        }

        .modal h2 {
            margin-top: 0;
            font-size: 1.5rem;
            color: #333;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Form styles */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus,
        select:focus {
            border-color: #3498db; /* Focus border color */
            outline: none; /* Remove outline */
        }

        button[type="submit"] {
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #2980b9; /* Darker blue on hover */
        }

        /* Loading message styles */
        .loading-message {
            display: none; /* Hidden by default */
            font-size: 1.2rem;
            color: #3498db;
            text-align: center;
            margin-top: 20px;
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
        <a href="#" id="registerBtn" class="register-btn">Register New User</a>

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

        <!-- The Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Register New User</h2>
                <form id="registerForm" method="POST" action="register_user.php">
                    <label for="email">Email:</label>
                    <input id="email" name="email" type="email" required>

                    <label for="username">Username:</label>
                    <input id="username" name="username" type="text" required>

                    <label for="password">Password:</label>
                    <input id="password" name="password" type="password" required>

                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                    </select>

                    <button type="submit">Register User</button>
                </form>
                <div id="loadingMessage" class="loading-message">User is being added...</div>
            </div>
        </div>
    </div>

    <!-- Include jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#usersTable').DataTable();

            // Modal functionality
            var modal = document.getElementById("myModal");
            var btn = document.getElementById("registerBtn");
            var span = document.getElementsByClassName("close")[0];
            var loadingMessage = $("#loadingMessage");

            btn.onclick = function() {
                modal.style.display = "block";
            }

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // Show loading message on form submission
            $("#registerForm").on("submit", function() {
                loadingMessage.show(); // Show the loading message
            });
        });
    </script>
</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
