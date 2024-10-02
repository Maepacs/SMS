<?php
// Start a session
session_start();

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

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Initialize error message
$error_message = "";

// Handle the login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST["username"]);
    $password = sanitize_input($_POST["password"]);

    // Check if fields are empty
    if (empty($username) || empty($password)) {
        $error_message = "Both fields are required!";
    } else {
        // Prepare the query to check the user's credentials
        $stmt = $conn->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $db_username, $db_password_hash, $role);

        // Check if the user exists
        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            
            // Verify the password
            if (password_verify($password, $db_password_hash)) {
                // Set session variables
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $db_username;
                $_SESSION['role'] = $role;

                // Redirect based on user role
                if ($role == 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($role == 'teacher') {
                    header("Location: teacher_dashboard.php");
                } elseif ($role == 'student') {
                    header("Location: student_dashboard.php");
                }
                exit();
            } else {
                $error_message = "Incorrect password!";
            }
        } else {
            $error_message = "User not found!";
        }
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/adLog_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css">
    <style>
        .error-box {
            margin: 20px auto;
            padding: 10px;
            border: 1px solid red;
            background-color: #f8d7da;
            color: red;
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
    <h3>Admin Login</h3>

    <!-- Display error message if set -->
    <?php if (!empty($error_message)): ?>
        <div class="error-box">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form class="login-form" method="POST" action="">
        <div class="flex-row">
            <label class="lf--label" for="username">
                <i class="ri-user-line"></i>
            </label>
            <input id="username" class="lf--input" name="username" placeholder="Username" type="text">
        </div>
        <div class="flex-row">
            <label class="lf--label" for="password">
                <i class="ri-lock-line"></i>
            </label>
            <input id="password" class="lf--input" name="password" placeholder="Password" type="password">
        </div>
        <input class="lf--submit" type="submit" value="LOGIN">   
     </form>
</body>
</html>
