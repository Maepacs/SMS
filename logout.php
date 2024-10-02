<?php
session_start();
session_destroy();
header('Location: admin_login.php'); // redirect to login page
exit();
?>