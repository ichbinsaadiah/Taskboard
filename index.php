<?php
session_start();

// If user is logged in, go to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Otherwise, go to login
header("Location: login.html");
exit;