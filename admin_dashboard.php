<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
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
            background-color: #3498db ;
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

        h1 {
            font-size: 2rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <h1>Admin Dashboard</h1>
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
        <h1>Welcome to the Admin Dashboard, <?php echo $_SESSION['username']; ?>!</h1>
        <!-- Add admin-related content here -->
    </div>
</body>
</html>
