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
// Initialize variables for the alert
$alertMessage = '';
$alertType = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $dept = $conn->real_escape_string(trim($_POST['department']));
    $regNum = $conn->real_escape_string(trim($_POST['regnumber']));

    // Insert student into the database
    $sql = "INSERT INTO users (name, email, dept, reg_no) VALUES ('$name', '$email', '$dept', '$regNum')";
    
    if ($conn->query($sql) === TRUE) {
        $alertMessage = 'Student added successfully!';
        $alertType = 'success';
    } else {
        $alertMessage = "Error: " . $conn->error;
        $alertType = 'danger';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    
    <!-- Custom CSS -->
    <link href="../static/css/test.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Add Student</h2>
        
        <?php if ($alertMessage): ?>
            <div class="alert alert-<?= $alertType; ?> alert-dismissible fade show" role="alert">
                <?= $alertMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <a href="admin_dashboard.php" class="btn btn-secondary">Back</a> <!-- Change 'previous_page.php' to your actual previous page -->
        </div>

        <form method="POST" action="add_student.php" name="add_student" id="add_student">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department:</label>
                <input type="text" class="form-control" name="department" id="department" required>
            </div>
            <div class="mb-3">
                <label for="regnumber" class="form-label">Registration Number:</label>
                <input type="text" class="form-control" name="regnumber" id="regnumber" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Student</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
