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

       /* Button styles */
.button {
    padding: 5px 10px;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.9rem;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #2980b9;
}

/* Delete button */
.delete-button {
    background-color: #e74c3c; /* Red color */
    color: black;

}

.delete-button:hover {
    background-color: #c0392b; /* Darker red on hover */
}

/* Edit button */
.edit-button {
    background-color: #2ecc71; /* Green color */
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.3s;
}

.edit-button:hover {
    background-color: #27ae60; /* Darker green on hover */
}

        /* Button to register new user */
        .register-btn {
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 20px;
            background-color: #2ecc71;
            color: black;
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

        .popup {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 5px;
            color: white;
            font-size: 1rem;
            z-index: 1001;
        }

        .popup.success {
            background-color: #2ecc71;
        }

        .popup.error {
            background-color: #e74c3c;
        }

        .popup.show {
            display: block;
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
                                    <button class='edit-button' data-id='{$row['id']}' data-username='{$row['username']}' data-email='{$row['email']}' data-role='{$row['role']}'>Edit</button>
                                    <a href='#' class='button delete-button' onclick='confirmDelete({$row['id']}); return false;'>Delete</a>
                                </td>
                            </tr>";
                    }
                }
                ?>
            </tbody>
        </table>

        <!-- Popup message for status -->
        <div id="popupMessage" class="popup"></div>

        <!-- Edit User Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Edit User</h2>
                <form id="editUserForm" method="POST" action="update_user.php">
                    <input type="hidden" id="editId" name="id" value="">
                    <label for="editUsername">Username:</label>
                    <input type="text" id="editUsername" name="username" required>
                    <label for="editEmail">Email:</label>
                    <input type="email" id="editEmail" name="email" required>
                    <label for="editRole">Role:</label>
                    <select id="editRole" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                    <button type="submit">Update User</button>
                </form>
            </div>
        </div>

        <!-- Register User Modal -->
        <div id="registerModal" class="modal">
            <div class="modal-content">
                <span id="registerClose" class="close">&times;</span>
                <h2>Register User</h2>
                <form id="registerUserForm" method="POST" action="register_user.php">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                    <button type="submit">Register User</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Include jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable();

            // Confirm delete function
            window.confirmDelete = function(userId) {
                if (confirm("Are you sure you want to delete this user?")) {
                    window.location.href = 'delete_user.php?id=' + userId;
                }
            };

            // Modal functionality
            var editModal = document.getElementById("editModal");
            var registerModal = document.getElementById("registerModal");
            var span = document.getElementsByClassName("close")[0];
            var registerClose = document.getElementById("registerClose");

            // Handling the popup message
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const popupMessage = $('#popupMessage');

            if (status) {
                if (status === 'success') {
                    popupMessage.text('User credentials have been successfully updated!');
                    popupMessage.addClass('success show');
                } else if (status === 'error') {
                    popupMessage.text('Error updating user credentials. Please try again.');
                    popupMessage.addClass('error show');
                }

                setTimeout(function() {
                    popupMessage.removeClass('show');
                }, 3000);

                window.history.replaceState(null, '', window.location.pathname);
            }

            // Modal open/close behavior for editing
            $(".edit-button").on("click", function() {
                editModal.style.display = "block";
                var userId = $(this).data("id");
                var username = $(this).data("username");
                var email = $(this).data("email");
                var role = $(this).data("role");

                $("#editId").val(userId);
                $("#editUsername").val(username);
                $("#editEmail").val(email);
                $("#editRole").val(role);
            });

            // Modal open/close behavior for registering
            $("#registerBtn").on("click", function() {
                registerModal.style.display = "block";
            });

            span.onclick = function() {
                editModal.style.display = "none";
            };

            registerClose.onclick = function() {
                registerModal.style.display = "none";
            };

            window.onclick = function(event) {
                if (event.target == editModal) {
                    editModal.style.display = "none";
                } else if (event.target == registerModal) {
                    registerModal.style.display = "none";
                }
            }
        });
    </script>
</body>
</html>
