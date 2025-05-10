<?php

include '../admin_session_init.php';

$conn = new mysqli('localhost', 'root', '', 'mocks');

    // Check for connection error
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Set the filename for the CSV file
    $filename = 'leaderboard.csv';

    // Set the header for the output
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write the column headers
    fputcsv($output, ['Register Num.', 'Total Marks', 'Name', 'E-Mail ID', 'Rank']);

    // Query to fetch leaderboard data
    $query = "
        SELECT 
            users.reg_no, 
            SUM(scores.total) AS total,
            users.name,
            users.email
        FROM users
        INNER JOIN scores ON users.reg_no = scores.reg_no
        GROUP BY users.reg_no, users.name, users.email
        ORDER BY total DESC"; // Order by total score

    $result = $conn->query($query);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    // Initialize rank
    $rank = 0; 
    $previousTotal = null; // Previous total score for rank calculation

    // Check if there are results and fetch them
    while ($student = $result->fetch_assoc()) {
        // Increment rank if the total score is different from the previous one
        if ($student["total"] !== $previousTotal) {
            $rank++;
        }

        // Write each student's data to the CSV
        fputcsv($output, [
            '"' . $student["reg_no"] . '"', // Force reg_no to be treated as a string
            $student["total"],
            $student["name"],
            $student["email"],
            $rank
        ]);

        $previousTotal = $student["total"]; // Update previous total
    }

    // Close the output stream
    fclose($output);
    exit;
?>
