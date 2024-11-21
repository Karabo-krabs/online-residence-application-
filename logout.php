<?php
// Start the session
session_start();

// Destroy all session data
session_destroy();

// Redirect to login page
header("Location: login.html");
exit();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - SPU Accommodations</title>
    <link rel="stylesheet" href="Home.css"> <!-- Same CSS as your login page -->
</head>
<body>
    <header>
        <nav>
            <h1 class="Jan">SPU On-Campus Accommodations</h1>
        </nav>
    </header>
    
    <center>
        <div class="logout-box">
            <h2>You have successfully logged out!</h2>
            <p>Thank you for using SPU Accommodations.</p>
            <button><a href="login.html" style="color: white;">Go to Login</a></button>
        </div>
    </center>

    <footer class="footer">
        <div>
            <b><p>Private Bag X5008</p></b>
            <b><p>North Campus</p></b>
            <p><b>Chapel Street</b></p>
            <p><b>Kimberley</b></p>
            <p><b>8300</b></p>
        </div>
        <div>
            <b><p>For more info: 0679933997</p></b>
            <b><p>Email: 202213141@spu.ac.za</p></b>
            <b><p>Mon-Fri, 9 AM - 5 PM</p></b>
            <b><p>Sat-Sun, 9 AM - 2 PM</p></b>
        </div>
    </footer>

    <footer style="background-color: #333;">
        <center>
            <div>
                <b><p style = "color:white;">&copy; 2024 Sol Plaatjie University On-Campus Accommodations. All rights reserved.</p></b>
            </div>
        </center>
    </footer>
</body>
</html>
