<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // If not logged in, redirect to login page
    header('Location: student_login.php');
    exit();
}
if ($_SESSION['logged_in'] == 0) { 
    header('Location: logout.php');
    exit();
}
?>
