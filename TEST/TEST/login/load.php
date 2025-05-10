<?php 
include '../admin_session_init.php';

// Database connection
$conn = new mysqli('localhost', 'root', '', 'mocks');

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect to login page if not logged in
/*
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: ../index.php');
    exit();
}
*/

// Check if a CSV file is uploaded
if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
    $csvFile = $_FILES['csv_file']['tmp_name'];

    // Start a transaction for this batch of questions
    $conn->begin_transaction();

    // Generate a new random exam_id
    do {
        $exam_id = random_int(1000, 9999); // Generates a random 4-digit number

        // Check if the generated exam_id already exists
        $checkExamIdQuery = $conn->prepare("SELECT COUNT(*) FROM questions WHERE exam_id = ?");
        $checkExamIdQuery->bind_param("i", $exam_id);
        $checkExamIdQuery->execute();
        $checkExamIdQuery->bind_result($count);
        $checkExamIdQuery->fetch();
        $checkExamIdQuery->close();
    } while ($count > 0); // If the exam_id already exists, generate a new one

    // Open the CSV file
    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        // Skip the first row (header)
        fgetcsv($handle);

        // Prepare the SQL statement without the Picture column
        $stmt = $conn->prepare("INSERT INTO questions (SNO, QuestionText, OptA, OptB, OptC, OptD, exam_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssi", $sno, $questionText, $optA, $optB, $optC, $optD, $exam_id);

        // Read each row from the CSV
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            // Check if the row has at least 6 elements
            if (count($data) >= 6) {
                $sno = $data[0];
                $questionText = $data[1];
                $optA = $data[2];
                $optB = $data[3];
                $optC = $data[4];
                $optD = $data[5];

                // Check for existing SNO
                $checkQuery = $conn->prepare("SELECT COUNT(*) FROM questions WHERE SNO = ?");
                $checkQuery->bind_param("i", $sno);
                $checkQuery->execute();
                $checkQuery->bind_result($count);
                $checkQuery->fetch();
                $checkQuery->close();

                if ($count == 0) {
                    // Execute the prepared statement
                    $stmt->execute();
                } else {
                    echo "<div class='alert alert-warning'>Skipping row with duplicate SNO: " . $sno . "</div>";
                }
            } else {
                // Output the skipped row for debugging
                echo "<div class='alert alert-danger'>Skipping row due to insufficient columns: " . implode(", ", $data) . "</div>";
            }
        }

        // Close the file and statement
        fclose($handle);
        $stmt->close();

        // Commit the transaction
        $conn->commit();

        echo "<div class='alert alert-success'>Data successfully loaded into the database with exam_id = $exam_id!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error opening the CSV file.</div>";
        // Rollback the transaction in case of an error
        $conn->rollback();
    }
} 

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Upload CSV File</h2>
        <form action="load.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="csv_file" class="form-label">Select CSV File</label>
                <input type="file" class="form-control" name="csv_file" accept=".csv" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
