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
// Check if the form is submitted
if (isset($_POST['submit'])) {
    
    // Allowed mime types
    $csvMimes = array('text/csv', 'application/csv', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel');
    
    // Validate whether selected file is a CSV file
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {
        
        // Open uploaded CSV file with read-only mode
        if (($handle = fopen($_FILES['file']['tmp_name'], 'r')) !== FALSE) {
            
            // Skip the first row (header)
            fgetcsv($handle);
            
            // Loop through the CSV file
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                
                // Prepare the data to insert
                $sno = $data[0];
                $reg_no = $data[1];
                $name = $data[2];
                $dept = $data[3];
                $email = $data[4];
                
                // Check if the email already exists
                $check = $conn->query("SELECT * FROM users WHERE reg_no = '$reg_no'");
                
                if ($check->num_rows == 0) {
                    // Insert data into the database
                    $conn->query("INSERT INTO users (SNO, reg_no, name, dept, email) VALUES ('$sno', '$reg_no', '$name', '$dept', '$email')");
                } else {
                    // Update existing record (optional)
                    $conn->query("UPDATE users SET name='$name', dept='$dept', email='$email' WHERE reg_no='$reg_no'");
                }
            }
            
            // Close opened CSV file
            fclose($handle);
            
            echo "<div class='alert alert-success'>Data successfully imported!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error opening the file.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Please upload a valid CSV file.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Load Students</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:  #deedfc;
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
        <h2 class="text-center">Upload CSV to Import Student Data</h2>
        <form enctype="multipart/form-data" method="POST">
            <div class="mb-3">
                <label for="file" class="form-label">Select CSV File</label>
                <input type="file" class="form-control" name="file" accept=".csv" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Upload CSV</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
