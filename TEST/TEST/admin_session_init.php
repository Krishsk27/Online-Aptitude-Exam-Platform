<?php
session_start(); // Start the session



// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // If not logged in, redirect to login page
    header('Location: admin.php');
    exit();
}

?>
