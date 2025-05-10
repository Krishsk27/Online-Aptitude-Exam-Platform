<?php
include '../session_init.php';

$conn = new mysqli('localhost', 'root', '', 'mocks');

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user info from session
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Not Provided';
$dept = isset($_SESSION['dept']) ? $_SESSION['dept'] : 'Not Provided';
$reg_no = isset($_SESSION['reg_no']) ? $_SESSION['reg_no'] : null; // Retrieve registration number

// Function to fetch all results using mysqli
function fetchAllResults($stmt) {
    $result = [];
    while ($row = $stmt->fetch_assoc()) {
        $result[] = $row;
    }
    return $result;
}

if (!isset($_SESSION['exam_date'])) {
    $_SESSION['exam_date'] = date('Y-m-d');
}

// Retrieve questions
$sql_stmt = "SELECT QuestionText, OptA, OptB, OptC, OptD, Picture FROM questions LIMIT 100"; // Adjust LIMIT as needed
$stmt = $conn->query($sql_stmt);
$questions = fetchAllResults($stmt);

// Shuffle questions using reg_no string
if ($reg_no) {
    // Sort questions using the hash of the reg_no and question index
    usort($questions, function($a, $b) use ($reg_no) {
        $hashA = md5($reg_no . $a['QuestionText']); // Hash based on reg_no and question text
        $hashB = md5($reg_no . $b['QuestionText']);
        return strcmp($hashA, $hashB); // Sort by comparing the hashes
    });
}   

$totalQuestions = count($questions);
$timePerQuestion = 90; // 1.5 minutes for each question
$totalTime = $totalQuestions * $timePerQuestion; // Total time in seconds
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aptitude Test - Mocks 2022</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai+Looped:wght@500;600&family=Roboto+Slab:wght@300;400;500;600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background: url('../static/images/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 60px; /* Adjust margin to prevent content overlap with timer */
        }

        .question-card {
            display: none; /* Hide all question cards initially */
        }

        .attempted {
            background-color: #d4edda; /* Light green background for attempted questions */
        }

        footer {
            margin-top: 30px;
            text-align: center;
        }

        #timer {
            font-size: 24px;
            font-weight: bold;
            color: green; /* Change timer color to green */
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <span class="navbar-text">
                Welcome, <?= $name ?> | <strong>Test: Aptitude</strong>
            </span>
            <span class="navbar-text" id="attemptedCount">Attempted: 0 / <?= $totalQuestions ?></span>
            <span class="navbar-text" id="timer">Time Left: <span id="time">00:00</span></span> <!-- Timer added here -->
        </div>
    </nav>

    <div class="container mt-5 p-4">
        <!-- Questions Form -->
        <form method="POST" action="php/test-index.php" name="test" id="test">
        <?php foreach ($questions as $index => $row) : ?>
    <?php
    $question = nl2br($row['QuestionText']);
    $optA  = nl2br($row['OptA']);
    $optB  = nl2br($row['OptB']);
    $optC  = nl2br($row['OptC']);
    $optD  = nl2br($row['OptD']);
    $image = $row["Picture"];
    ?>
    <!-- Question Card -->
    <div class="card question-card" id="question<?= $index ?>" style="<?= $index === 0 ? 'display: block;' : 'display: none;' ?>">
        <div class="card-body">
            <h5 class="card-title"><?= $index + 1 ?>. <?= $question ?></h5>
            <?php if (!empty($image) && $image !== "None") : ?>
                <?php $imagePath = "../uploads/{$image}"; ?>
                <img src="<?= $imagePath ?>" alt="Question Image" class="img-fluid mb-3" width="1200" />
                
            <?php else: ?>
                <p>No image available</p> <!-- Indicate when no image is available -->
            <?php endif; ?>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="question<?= $index ?>" value="A" onchange="markAttempted(this, <?= $index ?>)" required>
                <label class="form-check-label"><?= $optA ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="question<?= $index ?>" value="B" onchange="markAttempted(this, <?= $index ?>)">
                <label class="form-check-label"><?= $optB ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="question<?= $index ?>" value="C" onchange="markAttempted(this, <?= $index ?>)">
                <label class="form-check-label"><?= $optC ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="question<?= $index ?>" value="D" onchange="markAttempted(this, <?= $index ?>)">
                <label class="form-check-label"><?= $optD ?></label>
            </div><br>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearResponse(<?= $index ?>)">Clear Response</button>
        </div>
    </div>
<?php endforeach; ?>

            <input type="hidden" name="department" value="<?= $dept ?>">
            <input type="hidden" name="regnumber" value="<?= $_SESSION['reg_no'] ?>">
            <div class="text-end"><br>
                <button type="button" class="btn btn-primary" id="nextButton" onclick="nextQuestion()">Next</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let currentQuestion = 0; // Track the current question index
        const totalQuestions = <?= $totalQuestions ?>; // Total number of questions
        let attemptedQuestions = 0; // Track the number of attempted questions
        let timer; // Timer variable
        let totalTime = <?= $totalTime ?>; // Total time in seconds

        // Start the timer
        function startTimer() {
            timer = setInterval(function() {
                if (totalTime <= 0) {
                    clearInterval(timer);
                    alert('Time is up! Submitting your test now.');
                    document.getElementById('test').submit();
                } else {
                    const minutes = Math.floor(totalTime / 60);
                    const seconds = totalTime % 60;
                    document.getElementById('time').textContent = `${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                    totalTime--;
                }
            }, 1000);
        }

        // Function to navigate to the next question
        function nextQuestion() {
            const questions = document.querySelectorAll('.question-card');
            if (currentQuestion < totalQuestions - 1) {
                questions[currentQuestion].style.display = 'none'; // Hide current question
                currentQuestion++; // Move to the next question
                questions[currentQuestion].style.display = 'block'; // Show next question
            } else {
                // If it's the last question, submit the form
                document.getElementById('test').submit();
            }
        }

        // Function to mark a question as attempted
        function markAttempted(radio, index) {
            const questions = document.querySelectorAll('.question-card');
            if (!questions[index].classList.contains('attempted')) {
                questions[index].classList.add('attempted');
                attemptedQuestions++;
                document.getElementById('attemptedCount').textContent = `Attempted: ${attemptedQuestions} / ${totalQuestions}`;
            }
        }

        // Function to clear response for a specific question
        function clearResponse(index) {
            const questions = document.querySelectorAll('.question-card');
            const radios = questions[index].querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.checked = false; // Uncheck each radio button
            });
            questions[index].classList.remove('attempted'); // Remove attempted class
            attemptedQuestions--;
            document.getElementById('attemptedCount').textContent = `Attempted: ${attemptedQuestions} / ${totalQuestions}`;
        }

        // Request fullscreen mode on test start
        function requestFullscreen() {
            const elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) { // Firefox
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) { // Chrome, Safari and Opera
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { // IE/Edge
                elem.msRequestFullscreen();
            }
        }

        // Call fullscreen request on page load
        window.onload = function() {
            requestFullscreen();
            startTimer(); // Start the timer

		let tabSwitchCount = 0; // Counter for detecting how many times user switched tabs
const allowedTabSwitches = 2; // Number of allowed tab switches

// Detect when the user switches away from the page
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        tabSwitchCount++;
        alert(' Are you trying to earn a PhD in malpractices? Keep it up, and you might just graduate with honors!.Switching tabs is not allowed!');
        if (tabSwitchCount >= allowedTabSwitches) {
            alert(' Time up! You have been logged out for malpractice. Please return when you are ready to take the test honestly!');
            document.getElementById('test').submit(); // Automatically submit the test
        }
    }
});

// Optional: Block certain key combinations like Ctrl+C, Ctrl+V, F12, etc.
document.addEventListener('keydown', function(e) {
    if ((e.altKey && e.key === 'Tab') || (e.key === 'F12') || 
        (e.ctrlKey && (e.key === 'c' || e.key === 'v'))) {
        e.preventDefault();
        alert('Are you trying to earn a PhD in malpractices? Keep it up, and you might just graduate with honors!.This action is not allowed during the test!');
    }
});
        }

// Disable right-click context menu
document.addEventListener('contextmenu', function(event) {
    event.preventDefault(); // Prevent default right-click action
});
    </script>
</body>
</html>
