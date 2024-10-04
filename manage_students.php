<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: admin_login.php');
    exit();
}

require 'db_connection.php'; // Connect to your database

// Fetch students from the database
$query = "SELECT id, username, email, role, created_at, updated_at FROM users WHERE role='student'";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <style>
        /* CSS styles same as manage_users.php */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

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

        .side-panel {
            background-color: #2c3e50;
            width: 250px;
            padding-top: 60px;
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
            padding-top: 80px;
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

        .modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
            border-color: #3498db;
            outline: none;
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
            background-color: #2980b9;
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
        <h1>Manage Students</h1>
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
        <h2>Student Management</h2>

        <!-- Register button -->
        <a href="#" id="registerBtn" class="register-btn">Register New Student</a>

        <!-- Student table -->
        <table id="studentsTable" class="display">
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
                                    <a href='#' class='button edit-button' data-id='{$row['id']}' data-username='{$row['username']}' data-email='{$row['email']}' data-role='{$row['role']}'>Edit</a>
                                    <a href='delete_user.php?id={$row['id']}' class='button delete-button'>Delete</a>
                                </td>
                            </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Popup message -->
    <div id="popupMessage" class="popup"></div>

    <!-- Modal for editing student -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Student</h2>
            <form action="update_student.php" method="post">
                <input type="hidden" id="editId" name="id">
                <label for="editUsername">Username:</label>
                <input type="text" id="editUsername" name="username" required>
                <label for="editEmail">Email:</label>
                <input type="email" id="editEmail" name="email" required>
                <label for="editRole">Role:</label>
                <select id="editRole" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="student" selected>Student</option>
                </select>
                <button type="submit">Update Student</button>
            </form>
        </div>
    </div>

    <!-- Include jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable();

            // Modal functionality
            var modal = document.getElementById("editModal");
            var span = document.getElementsByClassName("close")[0];

            // Handling the popup message
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const popupMessage = $('#popupMessage');

            if (status) {
                if (status === 'success') {
                    popupMessage.text('Student credentials have been successfully updated!');
                    popupMessage.addClass('success show');
                } else if (status === 'error') {
                    popupMessage.text('Error updating student credentials. Please try again.');
                    popupMessage.addClass('error show');
                }

                // Hide the popup after 3 seconds
                setTimeout(function() {
                    popupMessage.removeClass('show');
                }, 3000);

                // Optionally remove the status query parameter from the URL after displaying the message
                window.history.replaceState(null, '', window.location.pathname);
            }

            // Modal open/close behavior
            $(".edit-button").on("click", function() {
                modal.style.display = "block";
                var userId = $(this).data("id");
                var username = $(this).data("username");
                var email = $(this).data("email");
                var role = $(this).data("role");

                $("#editId").val(userId);
                $("#editUsername").val(username);
                $("#editEmail").val(email);
                $("#editRole").val(role);
            });

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // Registration button functionality
            $("#registerBtn").on("click", function() {
                window.location.href = 'register_student.php'; // Change to your registration page
            });
        });
    </script>
</body>
</html>
