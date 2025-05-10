<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai+Looped:wght@500;600&family=Roboto+Slab:wght@300;400;500;600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css"/>

    <!-- SemanticUI CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css'/>

    <!-- DataTables SemanticUI CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.semanticui.min.css">

    <!-- Responsive CSS -->
    <link rel="stylesheet" href="../static/css/leaderboard.css">
    
    <title>Leaderboard</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-color: #deedfc;
            background-size: cover;
        }
        .header {
    color: white;
    text-align: center;
    padding: 30px 30px;
    background-color: rgba(22, 73, 183, 0.4); /* Adjust opacity to make it more glass-like */
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px); /* Frosted glass effect */
    -webkit-backdrop-filter: blur(10px); /* Safari support */
    border: 1px solid rgba(255, 255, 255, 0.2); /* Optional border for a more glassy effect */
}
    
        .table-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        table {
            margin-top: 20px;
        }
        th {
            background-color: rgba(22, 73, 183, 0.7);
            color: white;
        }
        tr:hover {
            background-color: rgba(22, 73, 183, 0.2);
        }
        footer {
            text-align: center;
            padding: 20px 0;
            background-color: rgba(22, 73, 183, 0.8);
            color: white;
            position: relative;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="ui header">Leaderboard</h1>
    </div>
    <br><br>
    <div class="ui container table-container">
        <!-- Date Filter Form -->
        <form method="GET" action=" ">
            <div class="mb-3">
                <label for="exam_date" class="form-label">Select Exam Date:</label>
                <input type="date" id="exam_date" name="exam_date" class="form-control" required>
            </div>
            <br>
            <button type="submit" class="ui primary button">Filter</button>
            <a href="../login/student_dashboard.php" class="ui button">Back</a>
        </form>

        <table class="ui celled compact selectable responsive unstackable table" id="scores">
            <thead>
                <tr>
                    <th>Register Num.</th>
                    <th>Total Marks</th>
                    <th>Name</th>
                    <th>E-Mail ID</th>
                    <th>Rank</th>
                </tr>
            </thead>
        
            <tbody>
            <?php
include '../session_init.php';
$conn = new mysqli('localhost', 'root', '', 'mocks');

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected exam date from the query string
$selected_date = isset($_GET['exam_date']) ? $_GET['exam_date'] : null;

// Query to fetch leaderboard data with the total score from scores table and user information
// Query to fetch leaderboard data with the total score from scores table and user information
// Query to fetch leaderboard data with the total score from scores table and user information
// Query to fetch leaderboard data with the total score from scores table and user information
$query = "
    SELECT 
        users.reg_no, 
        SUM(scores.total) AS total,
        users.name,
        users.email
    FROM users
    INNER JOIN scores ON users.reg_no = scores.reg_no
";

// If an exam date is selected, filter results by that date
if ($selected_date) {
    $query .= " WHERE DATE(scores.submission_date) = '" . $conn->real_escape_string($selected_date) . "'";
}

$query .= "
    GROUP BY users.reg_no, users.name, users.email
    ORDER BY total DESC"; // Order by total score

$result = $conn->query($query);
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Check if there are results and fetch them
if ($result->num_rows > 0) {
    $rank = 0; // Initialize rank
    $previousTotal = null; // Previous total score for rank calculation

    while ($student = $result->fetch_assoc()) {
        // Increment rank if the total score is different from the previous one
        if ($student["total"] !== $previousTotal) {
            $rank++;
        }

        echo '<tr>
            <td>' . htmlspecialchars($student["reg_no"]) . '</td>
            <td>' . htmlspecialchars($student["total"]) . '</td>
            <td>' . htmlspecialchars($student["name"]) . '</td>
            <td>' . htmlspecialchars($student["email"]) . '</td>
            <td>' . $rank . '</td>
        </tr>';

        $previousTotal = $student["total"]; // Update previous total
    }
} else {
    echo '<tr><td colspan="5">No data available for the selected date.</td></tr>';
}




// Close the database connection
$conn->close();
?>
            </tbody>
        </table>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!-- SemanticUI JS -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.js'></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>

    <!-- DataTables SemanticUI JS -->
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.semanticui.min.js"></script>

    <!-- Initialize Datatables -->
    <script src="../static/js/leaderboard.js"></script>
</body>
</html>
