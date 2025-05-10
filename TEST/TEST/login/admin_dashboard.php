<?php
// Start the session
include '../admin_session_init.php';



// HTML for the admin dashboard
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Exo:400,700" rel="stylesheet">

    <style>
        body {
            font-family: 'Exo', sans-serif;
            overflow: hidden; /* Prevent scrolling */
        }

        .area {
            background: #4e54c8;  
            background: -webkit-linear-gradient(to left, #8f94fb, #4e54c8);  
            width: 100%;
            height: 100vh;
            position: relative; /* Position relative to place the context */
        }

        .context {
            width: 100%;
            position: absolute;
            top: 50vh;
            transform: translateY(-50%); /* Center vertically */
            text-align: center; /* Center align text */
            z-index: 1; /* Above the circles */
        }

        .context h1 {
            color: #fff;
            font-size: 50px;
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

        .dashboard-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 50px;
            border-radius: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-top: 5%;
            position: relative; /* Ensures it stays on top of the background */
            z-index: 2; /* Above the background circles */
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .nav-link {
            color: black; /* Default text color */
        }

        .nav-link:hover {
            color: white; /* Hover text color */
        }

        footer {
            margin-top: 20px;
            color: #333;
        }

        /* Button Styling */
        .btn {
            background-color: white; /* Default background color */
            color: black; /* Default text color */
            font-weight: bold; /* Make the text bold */
            border: 2px solid black; /* Add a black border */
            transition: background-color 0.3s, color 0.3s; /* Smooth transition */
        }

        .btn:hover {
            background-color: #5c64ee; /* Change background to black on hover */
            color: white; /* Change text to white on hover */
        }

        /* Special hover colors for danger and secondary buttons */
        .btn-danger:hover {
            background-color: #c82333; /* Darker red on hover */
            color: white; /* Text color on hover for danger */
        }

        .btn-secondary:hover {
            background-color: #6c757d; /* Darker gray on hover */
            color: white; /* Text color on hover for secondary */
        }
    </style>
</head>
<body>
    <div class="area">
        <ul class="circles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
        <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100">
            <div class="dashboard-container">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['message']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['message']); unset($_SESSION['msg_type']); // Clear the message after displaying ?>
                <?php endif; ?>

                <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
                <p>Select an option from the navigation bar below to manage students or questions.</p>
                
                <div class="container">
    <div class="row mb-4 justify-content-center">
        <div class="col-md-3 mb-3">
            <a class="btn btn-primary w-100" href="add_student.php">Add Student</a>
        </div>
        <div class="col-md-3 mb-3">
            <a class="btn btn-primary w-100" href="load_student.php">Load Student</a>
        </div>
        <div class="col-md-3 mb-3">
            <a class="btn btn-primary w-100" href="load.php">Load Question</a>
        </div>
        <div class="col-md-3 mb-3">
            <a class="btn btn-primary w-100" href="edit_questions.php">Edit Questions</a>
        </div>
    </div>
    <div class="row mb-4 justify-content-center">
        <div class="col-md-3 mb-3">
            <a class="btn btn-danger w-100" href="delete_questions.php">Delete Question</a>
        </div>
        <div class="col-md-3 mb-3">
            <a class="btn btn-danger w-100" href="export_leaderboard.php">Export Leaderboard</a>
        </div>
        <div class="col-md-3 mb-3">
            <a class="btn btn-danger w-100" href="delete_leaderboard.php" onclick="return confirm('Are you sure you want to delete the entire leaderboard? This action cannot be undone.');">Delete Leaderboard</a>
        </div>
        <div class="col-md-3 mb-3">
            <a class="btn btn-secondary w-100" href="admin_logout.php">Logout</a>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
