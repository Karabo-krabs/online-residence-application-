<?php
// Start session
session_start();

// Check if the student is logged in
if (!isset($_SESSION['studentNumber'])) {
    // If not, redirect to the login page
    header("Location: login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "OneTwoThree"; // Update with your database password
$dbname = "InformationSystems";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch residences from the database
$sql = "SELECT residenceName, address, images, links FROM Residences";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPU On-Campus Accommodations</title>
    <link rel="stylesheet" href="Home.css">
</head>
<body>
    <header>
        <nav>
            <h1 class="Jan">SPU On-Campus Accommodations</h1>
        </nav>
        
        <button class="open-btn" onclick="openSidePanel()">☰ </button>

        <div id="sidePanel" class="side-panel">
            <a href="javascript:void(0)" class="close-btn" onclick="closeSidePanel()">×</a>
            <a href="track_application.html">Check Applications Status</a>
            <a href="logout.php">Logout</a> <!-- Changed the link to logout -->
        </div>
    </header>
    <header id="logo" class="logo">
        
    </header>

    <main>
        <section id="home">
            <h1>Providing the best accommodations</h1>
        </section>
    </main>

    <center>
        <h3>RESIDENCES</h3>
        <div class="catalogue-container">
            <?php
            // Check if there are any residences in the database
            if ($result->num_rows > 0) {
                // Output each residence as a product card
                while($row = $result->fetch_assoc()) {
                    $residenceName = $row['residenceName'];
                    $address = $row['address'];
                    $image = $row['images'];
                    $imageSrc = $image; // Assuming the image is a path stored in the database
                    $link = $row['links']; // Fetch the associated link for each residence

                    echo "
                    <div class='product'>
                        <img src='$imageSrc' alt='$residenceName'>
                        <h3 style='color: black;'>$residenceName</h3>
                        <p>$address</p>
                        <button>
                            <b><a href='$link' style='color: white;' onclick='checkLink(\"$residenceName\", \"$link\")'>View</a></b>
                        </button>
                    </div>";
                }
            } else {
                echo "<p>No residences available at the moment.</p>";
            }

            // Close the database connection
            $conn->close();
            ?>
        </div>

        <!-- Floating Apply Button -->
        <button id="applyNowButton" class="apply-button">Apply Now</button>
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
                <b><p style="color:white;">&copy; 2024 Sol Plaatjie University On-Campus Accommodations. All rights reserved.</p></b>
            </div>
        </center>
    </footer>

    <script>
        // Script for showing/hiding the apply button based on user scroll/activity
        const applyButton = document.getElementById('applyNowButton');
        let inactivityTimer;

        function showApplyButton() {
            applyButton.style.display = 'block';
            resetInactivityTimer();
        }

        function hideApplyButton() {
            applyButton.style.display = 'none';
        }

        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(hideApplyButton, 3000); // Hide after 3 seconds of inactivity
        }

        window.addEventListener('scroll', () => {
            showApplyButton();
        });

        window.addEventListener('mousemove', () => {
            showApplyButton();
        });

        window.addEventListener('keypress', () => {
            showApplyButton();
        });

        applyButton.addEventListener('click', () => {
            window.location.href = 'application.php'; // Replace with your destination URL
        });

        resetInactivityTimer();

        function checkLink(residenceName, link) {
            if (link === "") {
                alert("The contents of " + residenceName + " will be ready soon.");
            } else {
                window.location.href = link; // Redirect to the link if it is not empty
            }
        }

        function openSidePanel() {
            document.getElementById("sidePanel").classList.add("open-side-panel");
            document.getElementById("mainContent").classList.add("open-main-content");
            document.addEventListener('click', outsideClickListener);
        }

        function closeSidePanel() {
            document.getElementById("sidePanel").classList.remove("open-side-panel");
            document.getElementById("mainContent").classList.remove("open-main-content");
            document.removeEventListener('click', outsideClickListener);
        }

        function outsideClickListener(event) {
            const sidePanel = document.getElementById("sidePanel");
            const openButton = document.querySelector(".open-btn");
            if (!sidePanel.contains(event.target) && event.target !== openButton) {
                closeSidePanel();
            }
        }
    </script>
</body>
</html>
