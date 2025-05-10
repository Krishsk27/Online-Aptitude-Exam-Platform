<?php
include '../session_init.php';

$conn = new mysqli('localhost', 'root', '', 'mocks');

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Redirect to login page if not logged in
//if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    //header('Location: student_login.php');
    //exit();
//}


// Get the logged-in student's reg_no
$reg_no = $_SESSION['reg_no'];

// Fetch student details
$query = "SELECT reg_no, name, dept, email FROM users WHERE reg_no = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $reg_no);
$stmt->execute();
$result = $stmt->get_result();

// Debugging output
if ($result === false) {
    die('Query Error: ' . $conn->error);
}

// Check if any rows were returned
if ($result->num_rows === 0) {
    die('No results found for Reg. No: ' . htmlspecialchars($reg_no));
}

// Store student info
$student_info = $result->fetch_assoc();

// Fetch scores to calculate performance metrics
$scores_query = "SELECT total FROM scores WHERE reg_no = ?";
$stmt_scores = $conn->prepare($scores_query);
$stmt_scores->bind_param('s', $reg_no);
$stmt_scores->execute();
$scores_result = $stmt_scores->get_result();

$scores = [];
while ($row = $scores_result->fetch_assoc()) {
    $scores[] = $row['total'];
}

// Initialize variables for highest and lowest scores
$highest_score = null;
$lowest_score = null;
$total_scores = count($scores);

// Only calculate if there are scores
if ($total_scores > 0) {
    $highest_score = max($scores);
    $lowest_score = min($scores);
} else {
    $highest_score = 0; // Default value if no scores exist
    $lowest_score = 0; // Default value if no scores exist
}

// Calculate the student's leaderboard position
$position_query = "
    SELECT COUNT(*) AS rank 
    FROM scores 
    WHERE total > (
        SELECT MAX(total) 
        FROM scores 
        WHERE reg_no = ?
    )";

$stmt_position = $conn->prepare($position_query);
$stmt_position->bind_param('s', $reg_no);
$stmt_position->execute();
$position_result = $stmt_position->get_result();
$position_row = $position_result->fetch_assoc();
$leaderboard_position = $position_row['rank'] + 1; // +1 because rank starts at 1

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #deedfc;
        }

        /* Main container for the entire page */
        .main-container {
            margin-top: 50px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Container for performance metrics */
        .performance-container {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #fafafa;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Container for the chart */
        .chart-container {
            padding: 20px;
            background-color: #fafafa;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .ui.left.floated.button {
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .ui.right.floated.button {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        /* Responsive layout for performance and chart */
        @media (min-width: 768px) {
            .ui.grid .row {
                display: flex;
                justify-content: space-between;
            }

            .performance-container,
            .chart-container {
                flex: 1;
                margin: 10px;
            }
        }
       


    .page-title {
        font-family: 'Bebas Neue', sans-serif; /* Apply Bebas Neue */
        font-weight: 400; /* Bebas Neue is naturally bold */
        font-size: 4em; /* Adjust size to make it stand out */
        color: black; /* Change color to fit your design */
        text-align: center; /* Center align the text */
        letter-spacing: 2px; /* Add spacing between the letters */
        text-transform: uppercase; /* Make all text uppercase */
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Optional text shadow */
        margin: 20px 0;
    }
    </style>
</head>
<body>
<div class="ui container main-container">
    <div class="ui container center aligned" style="display: flex; align-items: center; justify-content: space-between; margin: 20px 0;">
        <div style="flex: 1; height: 2px; background-color: #cccccc; margin-right: 10px;"></div>
        <h1 class="page-title" style="margin: 0;">Student Dashboard</h1>
        <div style="flex: 1; height: 2px; background-color: #cccccc; margin-left: 10px;"></div>
    </div>

<style>
    .divider {
        border-top: 2px solid #cccccc; /* Line color */
        width: 100%; /* Full width */
        margin: 20px 0; /* Space around the line */
    }
</style>

<div class="ui container">
    <a href="../leaderboard/index.php" class="ui primary button">LEADERBOARD</a> <!-- Leaderboard button -->
    <a href="view_past_results.php" class="ui  primary button" style="margin-top: 60px;">EXAM RESULT</a> <!-- View Past Exam Result button -->
    <a href="index.php" class="ui  primary button">TAKE TEST</a> 
    <a href="logout.php" class="ui  primary red button">LOGOUT</a><!-- Redirect button -->
<!-- Add the Logout Button Here -->
<style>
    .button-container {
        display: flex;
        justify-content: flex-end; /* Aligns buttons to the right */
        gap: 10px; /* Adds some spacing between buttons */
    }

    .button-container a {
        margin-top: 0; /* Ensures consistent alignment of buttons */
    }
</style>
<b><br>
    <h2>Welcome, <?php echo htmlspecialchars($student_info['name']); ?></h2>
    <p>Reg. No: <?php echo htmlspecialchars($student_info['reg_no']); ?></p>
    <p>Dept: <?php echo htmlspecialchars($student_info['dept']); ?></p>
    <p>Email: <?php echo htmlspecialchars($student_info['email']); ?></p></b><br>
    <div class="ui grid">
            <div class="row">
                <!-- Performance Metrics Container -->
                <div class="ui segment performance-container">
    <h3>Performance Metrics</h3>
    <p><b>Highest Score:</b> <?php echo $highest_score; ?></p>
    <p><b>Lowest Score: </b><?php echo $lowest_score; ?></p>
    <p><b>Your Leaderboard Position: </b><?php echo $leaderboard_position; ?></p>

    <canvas id="scoreDistribution" width="400" height="200"></canvas>
</div>
</div>
</div>
</div>

<script>
    const scores = <?php echo json_encode($scores); ?>;
    const ctx = document.getElementById('scoreDistribution').getContext('2d');
    const scoreCounts = {};

    // Count occurrences of each score
    scores.forEach(score => {
        scoreCounts[score] = (scoreCounts[score] || 0) + 1;
    });

    // Create labels and data for the chart
    const labels = Object.keys(scoreCounts);
    const data = Object.values(scoreCounts);

    const scoreChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Scores Distribution',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>
