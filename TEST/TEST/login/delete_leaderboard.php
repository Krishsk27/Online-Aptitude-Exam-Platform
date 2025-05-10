<?php

include '../admin_session_init.php';


// Check if admin is logged in
/*if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin.php"); // Redirect to admin login if not logged in
    exit();
}
*/
// Database connection
$conn = new mysqli('localhost', 'root', '', 'mocks');

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to delete all records from the leaderboard
$query = "DELETE FROM scores"; // Ensure to replace `scores` with your actual leaderboard table name

if ($conn->query($query) === TRUE) {
    $_SESSION['message'] = "Leaderboard cleared successfully.";
    $_SESSION['msg_type'] = "success"; // Use this for alert styling
} else {
    $_SESSION['message'] = "Error clearing leaderboard: " . $conn->error;
    $_SESSION['msg_type'] = "danger"; // Use this for alert styling
}

// Close the database connection
$conn->close();

// Redirect back to admin dashboard
header("Location: admin_dashboard.php");
exit();
?>
