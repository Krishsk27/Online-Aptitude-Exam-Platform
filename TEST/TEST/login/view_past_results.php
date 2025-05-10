<?php
include '../session_init.php';

$conn = new mysqli('localhost', 'root', '', 'mocks');

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect to login page if not logged in
/*if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: student_login.php');
    exit();
}*/

// Get the logged-in student's reg_no
$reg_no = $_SESSION['reg_no'];

// Fetch past exam results
$query = "SELECT total, submission_date FROM scores WHERE reg_no = ? ORDER BY submission_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $reg_no);
$stmt->execute();
$result = $stmt->get_result();

// Check for results
if ($result->num_rows === 0) {
    $message = "No past exam results found.";
} else {
    $exam_results = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Exam Results</title>

    <!-- Semantic UI and Bootstrap 5 CDN Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #deedfc;
            margin: 20px;
        }
        
        .custom-table {
            margin-top: 30px;
            background-color: #fff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            margin-top: 20px;
        }
        .ui.header {
            font-size: 36px;
            margin-top: 30px;
        }
        .ui.button {
            margin-bottom: 20px;
        }
        .no-results-message {
            margin-top: 20px;
            background-color: #ffedcc;
            color: #ff944d;
        }
    </style>
</head>
<body>
<div class="ui container">
    <h1 class="ui header center aligned">Past Exam Results</h1>
    
    <!-- Back to Dashboard Button -->
    <a href="student_dashboard.php" class="ui button primary">Back to Dashboard</a>

    <!-- Results or No Results Message -->
    <div class="table-container">
        <?php if (isset($message)): ?>
            <div class="alert alert-warning no-results-message"><?php echo $message; ?></div>
        <?php else: ?>
            <table class="table table-hover custom-table">
                <thead class="table-dark">
                    <tr>
                        <th>Exam Date</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($exam_results as $result): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($result['submission_date']); ?></td>
                            <td><?php echo htmlspecialchars($result['total']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap 5 JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
