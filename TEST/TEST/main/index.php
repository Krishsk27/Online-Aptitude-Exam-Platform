<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panimalar Aptitude Platform</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Exo:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: white;
        }

        .area {
            background: #4e54c8;  
            background: -webkit-linear-gradient(to left, #8f94fb, #4e54c8);  
            width: 100%;
            height: 100vh;
            position: relative;
            overflow: hidden; /* Ensure circles are contained within */
        }

        /* Animation circles */
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

        /* Main content */
        .content-section {
            position: relative;
            z-index: 1;
            text-align: center; /* Center align the content */
            padding: 50px;
        }

        .hero-text {
            font-size: 3rem;
            font-weight: 600;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
            color: rgb(224, 224, 224);
        }

        .btn-group {
            margin-top: 20px;
        }

        .btn {
            font-size: 1.1rem;
            font-weight: bold;
            padding: 10px 25px;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        /* Centering the message content */
        .lead {
            position: relative;
            width: 100%;
            height: 150px; /* Adjust the height based on your need */
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin-top: 100px; /* Increased margin to lower messages */
            font-size: 1.3rem; /* Increased font size */
            line-height: 1.5; /* Improved line height */
            font-family: 'Roboto', sans-serif; /* New font for messages */
        }

        .message {
            position: absolute;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            width: 100%;
            color: rgb(224, 224, 224);
            font-family: 'Montserrat', sans-serif; /* Updated font */
        }
        

        .message.active {
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- Background Animation -->
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

        <!-- Main content section -->
        <section class="content-section container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="hero-text">Panimalar Aptitude Platform</h1>
                    <!-- Messages container -->
                    <p class="lead mt-4">
                        <span class="message active">Don’t worry, this aptitude test won’t bite... unless you ask it to! Just relax, give it your best, and maybe even have a little fun!</span>
                        <span class="message">Why worry? It's just an aptitude test. Worst case, you'll walk away with some extra knowledge... and maybe a funny story!</span>
                        <span class="message">Success doesn’t come from what you do occasionally, it comes from what you do consistently. Believe in yourself, and give your best!</span>
                    </p>
                    <div class="btn-group">
                        <a href="../login/student_login.php" class="btn btn-danger">Login as a Student</a>
                        <a href="../login/admin.php" class="btn btn-outline-light">Login as an Admin</a>
                    </div>
                </div>
                <div class="col-md-6 photo-section">
                    <div class="photo-wrapper">
                        <img src="../images/pic1.png" alt="Photo" class="img-fluid">

                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript to handle fading messages -->
    <script>
        const messages = document.querySelectorAll('.message');
        let currentMessage = 0;

        function showNextMessage() {
            messages[currentMessage].classList.remove('active');
            currentMessage = (currentMessage + 1) % messages.length;
            messages[currentMessage].classList.add('active');
        }

        // Change message every 4 seconds
        setInterval(showNextMessage, 2500);
    </script>
</body>
</html>