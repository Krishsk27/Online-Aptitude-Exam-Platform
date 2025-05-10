<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'mocks');

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ""; // Initialize error message variable
$success = ""; // Initialize success message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reg_no = $_POST['reg_no'];
    $name = $_POST['name'];
    $dept = $_POST['dept'];
    $email = $_POST['email'];

    // Insert user details into the database
    $query = "INSERT INTO users (reg_no, name, dept, email) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $reg_no, $name, $dept, $email);

    if ($stmt->execute()) {
        // Set session variables after successful registration
        $_SESSION['logged_in'] = true;
        $_SESSION['reg_no'] = $reg_no; // Store registration number

        $success = "Registration successful! You can now log in.";
        header('Location: student_login.php'); // Redirect to student dashboard
        exit(); // Exit to prevent further execution
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #007bff, #6f42c1);
            color: #1649b7;
        }
        .area {
            background: #4e54c8;  
            background: -webkit-linear-gradient(to left, #8f94fb, #4e54c8);  
            width: 100%;
            height: 100vh;
            position: relative; /* Enable positioning for child elements */
        }

        .circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .circles li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.2);
            animation: animate 25s linear infinite;
            bottom: -150px;
        }

        .circles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .circles li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .circles li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .circles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .circles li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        .circles li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
        .circles li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
        .circles li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
        .circles li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
        .circles li:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

        @keyframes animate {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }
        .register-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 100px;
            border-radius: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-top: 5%;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .alert {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100">
        <div class="register-container">
            <h2 class="text-center">Student Registration</h2>
            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php } ?>
            <?php if (!empty($success)) { ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php } ?>
            <form method="POST" class="w-100">
                <div class="mb-3">
                    <label for="reg_no" class="form-label">Registration Number</label>
                    <input type="text" name="reg_no" class="form-control" id="reg_no" placeholder="Enter your registration number" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter your name" required>
                </div>
                <div class="mb-3">
                    <label for="dept" class="form-label">Department</label>
                    <input type="text" name="dept" class="form-control" id="dept" placeholder="Enter your department" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <div class="text-center mt-3">
                <a href="student_login.php">Already have an account? Login here</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
