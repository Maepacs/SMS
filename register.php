<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: admin_login.php');
    exit();
}

require 'db_connection.php'; // Connect to your database

// Initialize message variable
$success_message = "";

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Handle the registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = sanitize_input($_POST["fullname"]);
    $email = sanitize_input($_POST["email"]);
    $username = sanitize_input($_POST["username"]);
    $password = sanitize_input($_POST["password"]);
    $role = sanitize_input($_POST["role"]);

    // Split the full name into first and last name
    $name_parts = explode(" ", $fullname);
    $first_name = $name_parts[0];
    $last_name = isset($name_parts[1]) ? $name_parts[1] : '';

    // Validation: check if fields are empty
    if (empty($fullname) || empty($email) || empty($username) || empty($password) || empty($role)) {
        $success_message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $success_message = "Invalid email format!";
    } else {
        // Check if the username or email already exists
        $check_user = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check_user->bind_param("ss", $username, $email);
        $check_user->execute();
        $check_user->store_result();

        if ($check_user->num_rows > 0) {
            $success_message = "Username or email already exists!";
        } else {
            // Hash the password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the 'users' table
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $password_hash, $role);

            if ($stmt->execute()) {
                // Get the newly inserted user's ID
                $user_id = $stmt->insert_id;

                // Insert into the students or teachers table based on the selected role
                if ($role == 'student') {
                    $student_stmt = $conn->prepare("INSERT INTO students (user_id, first_name, last_name) VALUES (?, ?, ?)");
                    $student_stmt->bind_param("iss", $user_id, $first_name, $last_name);
                    $student_stmt->execute();
                } elseif ($role == 'teacher') {
                    $teacher_stmt = $conn->prepare("INSERT INTO teachers (user_id, first_name, last_name) VALUES (?, ?, ?)");
                    $teacher_stmt->bind_param("iss", $user_id, $first_name, $last_name);
                    $teacher_stmt->execute();
                }

                // Set success message
                $success_message = "Registration successful!";
            } else {
                $success_message = "Error: " . $stmt->error;
            }
        }

        $check_user->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
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

        .success-box {
            margin: 20px auto;
            padding: 15px;
            border: 1px solid green;
            background-color: #e7f9e7;
            color: green;
            font-size: 18px;
            text-align: center;
            width: 35%;
            border-radius: 5px;
        }

        .button {
            padding: 10px 20px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Register New User</h1>
</div>

<!-- Success message if set -->
<?php if (!empty($success_message)): ?>
    <div class="success-box">
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>

<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Register New User</h2>
        <form id="registerForm" method="POST" action="">
            <div>
                <label for="fullname">Full Name:</label>
                <input id="fullname" name="fullname" type="text" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input id="email" name="email" type="email" required>
            </div>
            <div>
                <label for="username">Username:</label>
                <input id="username" name="username" type="text" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input id="password" name="password" type="password" required>
            </div>
            <div>
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="button">Register</button>
        </form>
    </div>
</div>

<!-- Button to open modal -->
<button id="registerBtn" class="button">Open Registration Form</button>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("registerBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
</body>
</html>
