<?php

include '../admin_session_init.php';

$conn = new mysqli('localhost', 'root', '', 'mocks');

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect to login page if not logged in
/*if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../index.php');
    exit();
}
*/
// Check if the delete request is made
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // SQL query to delete all records from the questions table
    $sql = "DELETE FROM questions";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('All questions deleted successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "Error deleting records: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delete Questions</title>

    <!-- SemanticUI CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css" />
    
    <!-- Custom CSS -->
    <link href="../static/css/test.css" rel="stylesheet">
    <style>
        body {
            background-color:  #deedfc;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(15px);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .ui.button {
            width: 100%;
            margin-top: 10px;
        }
        .ui.red.button {
            background-color: #d9534f !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="container glass-effect">
        <h2 class="ui header">Delete All Questions</h2>
        <p style="text-align: center;">Are you sure you want to delete all questions? This action cannot be undone.</p>
        
        <form method="POST" action="delete_questions.php">
            <button type="submit" class="ui red button">Delete All Questions</button>
            <a href="admin_dashboard.php" class="ui button">Cancel</a>
        </form>
        
        <div class="mb-3 mt-3">
            <a href="admin_dashboard.php" class="ui button">Back</a> <!-- Back button to the admin dashboard -->
        </div>
    </div>

    <!-- Footer -->


    <!-- jQuery -->
    <script src='https://code.jquery.com/jquery-3.1.1.min.js'></script>

    <!-- SemanticUI JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.js"></script>
</body>
</html>
