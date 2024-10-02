<?php
// Start session
session_start();

if (isset($_SESSION['username'])) {
    // Confirm logout
    if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to login page
        header("Location: admin_login.php");
        exit;
    } else {
        // Ask for confirmation using JavaScript
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'admin_dashboard.php';
        ?>
        <script>
        // JavaScript confirmation dialog
        if (confirm("Are you sure you want to log out?")) {
            // If user confirms, proceed to logout.php?confirm=true
            window.location.href = "logout.php?confirm=true";
        } else {
            // If user cancels, redirect to the previous page
            window.location.href = "<?php echo $referrer; ?>";
        }
        </script>
        <?php
    }
} else {
    // If user is not logged in, redirect to login page
    header("Location: admin_login.php");
    exit;
}
?>
