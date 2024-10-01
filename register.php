<?php
// Database configuration
$host = 'localhost'; // Database host
$dbname = 'school_management'; // Database name
$db_username = 'root'; // Database username
$db_password = ''; // Database password

// Create a MySQL connection
$conn = new mysqli($host, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
        echo "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
    } else {
        // Check if the username or email already exists
        $check_user = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check_user->bind_param("ss", $username, $email);
        $check_user->execute();
        $check_user->store_result();

        if ($check_user->num_rows > 0) {
            echo "Username or email already exists!";
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
                echo "Error: " . $stmt->error;
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
    <title>Registration</title>
    <link rel="stylesheet" href="css/reg.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css">
    <style>
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
    </style>
</head>
<body>
    <div class="image-container">
        <img src="images/csab.png" alt="Image">
    </div>
    <h1>Colegio San Agustin - Bacolod</h1>
    <h3>Registration</h3>
    
    <!-- Display success message if set -->
    <?php if (!empty($success_message)): ?>
        <div class="success-box">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <form class="reg-form" method="POST" action="">
        <div class="flex-row">
            <label class="rf--label" for="fullname">
                <i class="ri-user-line"></i>
            </label>
            <input id="fullname" class="rf--input" name="fullname" placeholder="Full Name" type="text" required>
        </div>
        <div class="flex-row">
            <label class="rf--label" for="email">
                <i class="ri-mail-line"></i>
            </label>
            <input id="email" class="rf--input" name="email" placeholder="Email" type="email" required>
        </div>
        <div class="flex-row">
            <label class="rf--label" for="username">
                <i class="ri-user-line"></i>
            </label>
            <input id="username" class="rf--input" name="username" placeholder="Username" type="text" required>
        </div>
        <div class="flex-row">
            <label class="rf--label" for="password">
                <i class="ri-lock-line"></i>
            </label>
            <input id="password" class="rf--input" name="password" placeholder="Password" type="password" required>
        </div>
        <div class="flex-row">
            <label class="rf--label" for="role">
                <i class="ri-user-settings-line"></i>
            </label>
            <select id="role" class="rf--input" name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <input class="rf--submit" type="submit" value="REGISTER">
    </form>
    <small>Already have an account? <span><a class="lf--note" href="admin_login.php">Login Here</a></small> 
</body>
</html>
