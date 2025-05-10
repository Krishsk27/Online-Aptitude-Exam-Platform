<?php 
include '../../session_init.php';

$conn = new mysqli('localhost', 'root', '', 'mocks');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user info from session
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Not Provided';   
$regNum = $_SESSION['reg_no'];

// Fetch a new exam_id from the questions table
$sql_stmt = "SELECT DISTINCT exam_id FROM questions ORDER BY RAND() LIMIT 1"; // Get a random exam_id from the questions
$stmt_exam = $conn->prepare($sql_stmt);
$stmt_exam->execute();
$result_exam = $stmt_exam->get_result();
$exam_data = $result_exam->fetch_assoc();
$exam_id = $exam_data['exam_id']; // Store the exam ID

// Insert the test attempt into the test_attempts table, creating a new record if it doesn't exist
$insert_attempt = "INSERT INTO test_attempts (reg_no, exam_id, attempt_status) VALUES (?, ?, 1) 
                   ON DUPLICATE KEY UPDATE exam_id=?, attempt_status=1";
$stmt_insert = $conn->prepare($insert_attempt);
$stmt_insert->bind_param('sii', $regNum, $exam_id, $exam_id); 
$stmt_insert->execute();


// Fetch correct answers from the database
$correct_answers = [];
$sql_stmt = "SELECT CorrectOpt FROM questions WHERE exam_id = ?";
$stmt = $conn->prepare($sql_stmt);
$stmt->bind_param('i', $exam_id);
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

foreach ($questions as $question) {
    $correct_answers[] = $question['CorrectOpt'];
}

$no_of_questions = count($correct_answers);
$correct = 0;
$wrong = 0;
$skipped = 0;
$incorrect_questions = [];

// Evaluate user's answers
for ($i = 0; $i < $no_of_questions; $i++) {
    $question_num = $i + 1;
    $user_answer = isset($_POST["question$question_num"]) ? $_POST["question$question_num"] : "";

    if ($user_answer === "") {
        $skipped++; // Count as skipped if no answer is submitted
    } elseif ($user_answer == $correct_answers[$i]) {
        $correct++; // Count as correct if the user's answer matches the correct answer
    } else {
        $wrong++; // Count as wrong if the user's answer doesn't match
        $incorrect_questions[] = $question_num; // Store the question number of incorrect answers
    }
}

// Prepare insert statement for scores
$sql_query = "INSERT INTO scores (reg_no, total, exam_id, submission_date) VALUES (?, ?, ?, ?)";
$current_date = date('Y-m-d');  // Date in 'Y-m-d' format
$stmt = $conn->prepare($sql_query);
$stmt->bind_param('siis', $regNum, $correct, $exam_id, $current_date); 
$stmt->execute();


// Calculate the average score across all users
$sql_avg = "SELECT AVG(total) AS average_score FROM scores";
$result_avg = $conn->query($sql_avg);
$avg_score = $result_avg->fetch_assoc()['average_score'];

// Display the results using Bootstrap and Chart.js
echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #deedfc;
            margin: 0;
            padding: 20px;
        }
        .result-container {
            margin: auto;
            width: 60%;
            padding: 30px;
        }
        .result-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .chart-container {
            max-width: 400px;
            margin: auto;
            margin-top: 20px;
        }
        .exit-button {
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="result-container">
        <div class="card result-card">
            <div class="card-body">
                <h3 class="card-title text-center">Test Results</h3>
                <hr>
                <div class="row text-center my-3">
                    <div class="col-md-4">
                        <h4 class="text-success">Correct Answers</h4>
                        <p class="fs-4">'. $correct .'</p>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-danger">Wrong Answers</h4>
                        <p class="fs-4">'. $wrong .'</p>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-secondary">Skipped Questions</h4>
                        <p class="fs-4">'. $skipped .'</p>
                    </div>
                </div>
                <div class="text-center my-3">
                    <h5>Average Score by Others: '. round($avg_score, 2) .'%</h5>
                </div>
                <div class="chart-container">
                    <canvas id="pieChart"></canvas>
                </div>

                <div class="exit-button">
                 <a href="/TEST/login/student_dashboard.php" class="btn btn-primary">Exit</a>
                </div>

            </div>
        </div>
    </div>

    <script>
        var ctx = document.getElementById("pieChart").getContext("2d");
        var myPieChart = new Chart(ctx, {
            type: "pie",
            data: {
                labels: ["Correct", "Wrong", "Skipped"],
                datasets: [{
                    data: ['. $correct .', '. $wrong .', '. $skipped .'],
                    backgroundColor: ["#4caf50", "#f44336", "#9e9e9e"],
                    borderColor: "#ffffff",
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "bottom",
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';

// Close the statement and connection
$stmt->close();
$conn->close();
?>
