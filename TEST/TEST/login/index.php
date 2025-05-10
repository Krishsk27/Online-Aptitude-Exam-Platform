<?php
// Start the session
include '../session_init.php';

$reg_no = $_SESSION['reg_no']; // Assuming reg_no is stored in session
$exam_id = ''; // Variable to store exam_id entered by user
$attempt_status = 0; // Dummy status for demonstration
$exam_exists = false; // Flag to check if exam exists

// Database connection
$db = new mysqli('localhost', 'root', '', 'mocks'); // Replace with your database connection

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if exam_id is set in the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exam_id = $_POST['exam_id'];

    // Store exam_id in the session
    $_SESSION['exam_id'] = $exam_id;

    // Prepare SQL statement to fetch attempt status
    $stmt = $db->prepare("SELECT attempt_status FROM test_attempts WHERE reg_no = ? AND exam_id = ?");
$stmt->bind_param("si", $reg_no, $exam_id); 
$stmt->execute();
$stmt->bind_result($attempt_status);
$stmt->fetch();
$stmt->close();


    // Check if the exam_id exists in the questions table
    $stmt = $db->prepare("SELECT COUNT(*) FROM questions WHERE exam_id = ?");
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $exam_exists = ($count > 0); // Set exam_exists to true if count is greater than 0
    $stmt->close();

    // Redirect to question.php if conditions are met
    if ($attempt_status == 0 && $exam_exists) {
        header("Location: ../test/question.php?exam_id=" . urlencode($exam_id));
        exit(); // Important to exit after redirection
    }
}

// Close the database connection
$db->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mock Test Instructions</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Parallax effect */
        body {
            background-color:#deedfc ;
            background-attachment: fixed;
            background-size: cover;
        }
        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
        }
        h1 {
            font-family: 'Roboto Slab', serif;
            margin-bottom: 30px;
            color: #343a40;
        }
        .instructions {
            padding: 15px;
            border-radius: 10px;
            background: #f8f9fa;
        }
        .btn {
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn.enabled {
            background-color: #007bff; /* Blue background for enabled button */
        }
        .btn.disabled {
            background-color: #6c757d; /* Gray background for disabled button */
            cursor: not-allowed;
        }
        /* Style for the back button */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000; /* Ensure it's on top of other elements */
        }
    </style>
</head>
<body>
    <a href="student_dashboard.php" class="btn btn-secondary back-button">Back To Dashboard</a> <!-- Back Button -->
    
    <div class="container mt-5">
        <h1 class="text-center">Aptitude Test Instructions</h1>
        <div class="row g-5">
            <!-- Instructions -->
            <div class="col-lg-12">
                <div class="instructions">
                    <h3>Instructions:</h3>
                    <ul class="list-group list-group-flush"><b>
                        <li class="list-group-item">Copying and pasting text from any source is strictly prohibited. This includes using keyboard shortcuts (Ctrl+C and Ctrl+V) or right-clicking to copy and paste</li>
                        <li class="list-group-item">Do not use Alt + Tab or any other method to switch between tabs or windows during the exam. You must stay on the exam page until you finish.
                        </li>
                        <li class="list-group-item">You are not allowed to minimize the exam window. Your tab must remain active and visible at all times.</li>
                        <li class="list-group-item">If you are found to have violated any of these rules more than 3 times, your exam will be terminated immediately</li>
                        <li class="list-group-item">Enter details correctly. <b>No second attempts</b> allowed.</li>
                        <li class="list-group-item">Once completed, click submit. Make sure your internet connection is stable.</li>
                    </ul></b>
                </div>
            </div>
        </div>

        <!-- Exam ID Input -->
        <form id="examForm" method="POST" class="text-center mt-4">
            <input type="text" name="exam_id" id="examIdInput" placeholder="Enter Exam ID" class="form-control w-50 mx-auto" required />
            <button id="beginTestButton" type="submit" class="btn btn-primary w-50 mt-3">BEGIN TEST</button>
        </form>

        <p id="statusMessage" class="mt-3 text-center">
            <?php
            // Display status message based on attempt status and exam existence
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if ($attempt_status == 1) {
                    echo 'You have already attempted this test.';
                } elseif (!$exam_exists) {
                    echo 'The Exam ID does not exist. Please check the Exam ID.';
                } else {
                    echo 'You can proceed to take the test.';
                }
            }
            ?>
        </p>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const startButton = document.getElementById('beginTestButton');
        const exitWarning = document.getElementById('exitWarning');

        // Function to launch full-screen
        function launchFullScreen(element) {
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) { // Firefox
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) { // Chrome, Safari and Opera
                element.webkitRequestFullscreen();
            } else if (element.msRequestFullscreen) { // IE/Edge
                element.msRequestFullscreen();
            }
        }

        // Function to detect if full-screen is exited
        function fullScreenCheck() {
            if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.mozFullScreenElement && !document.msFullscreenElement) {
                // Trigger warning or action when full-screen is exited
                exitWarning.style.display = 'block';
                alert('You have exited full-screen mode! Please return to full-screen.');
                // Optionally, you can also log the event to the backend (e.g., via an AJAX request)
            }
        }

        // Start the test and enter full-screen mode
        startButton.addEventListener('click', () => {
            launchFullScreen(document.documentElement); // Start full-screen mode
            startButton.style.display = 'none'; // Hide start button once test begins
        });

        // Listen for full-screen exit events
        document.addEventListener('fullscreenchange', fullScreenCheck);
        document.addEventListener('webkitfullscreenchange', fullScreenCheck);
        document.addEventListener('mozfullscreenchange', fullScreenCheck);
        document.addEventListener('MSFullscreenChange', fullScreenCheck);
    </script>
    <script>
        let exitCount = 0;

function fullScreenCheck() {
    if (!document.fullscreenElement) {
        exitCount += 1;
        exitWarning.style.display = 'block';

        if (exitCount >= 3) {
            alert('You have exited full-screen too many times. Your test is invalidated.');
            // Invalidate the test (e.g., end the session or log it as failed)
            window.location.href = "end_test.php";
        } else {
            alert('Warning: You have exited full-screen mode. Please return to full-screen.');
        }

        logFullScreenExit();  // Log the full-screen exit
    }
}
    </script>
</body>
</html>