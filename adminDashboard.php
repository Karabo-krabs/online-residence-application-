<?php
// Start the session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['adminNumber'])) {
    // If not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "OneTwoThree";
$dbname = "InformationSystems";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a delete request was made
if (isset($_POST['delete'])) {
    $residenceName = $_POST['residenceName'];

    // Prepare the delete statement
    $deleteSql = "DELETE FROM Residences WHERE residenceName = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("s", $residenceName);

    if ($stmt->execute()) {
        echo "Accommodation deleted successfully.";
    } else {
        echo "Error deleting accommodation: " . $conn->error;
    }

    $stmt->close();
}

// Query to get the residences data
$sql = "SELECT residenceName, totalRooms, StudentsApplied, studentsAccepted, images FROM Residences";
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
        <button class="open-btn" onclick="openSidePanel()">☰</button>
        <div id="sidePanel" class="side-panel">
            <a href="javascript:void(0)" class="close-btn" onclick="closeSidePanel()">×</a>
            <a href="adminInt.php">Check Applications</a>
            <a href="logout.php">Logout</a>
        </div>
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
            if ($result->num_rows > 0) {
                // Output data for each residence
                while($row = $result->fetch_assoc()) {
                    $residenceName = $row['residenceName'];
                    $totalRooms = $row['totalRooms'];
                    $studentsAccepted = $row['studentsAccepted'];
                    $studentsApplied = $row['StudentsApplied'];
                    $image = $row['images'];

                    // Check if the residence is full
                    $isFull = ($studentsAccepted >= $totalRooms);

                    echo "<div class='product'>";
                    echo "<img src='{$image}' alt='{$residenceName}'>";
                    echo "<i><h3 style='color: black;'>{$residenceName}</h3></i>";
                    echo "<p>{$studentsAccepted}/{$totalRooms} students accepted</p>";
                    echo "<p>{$studentsApplied} students applied</p>";

                    // Add a delete form
                    echo "<form method='post' action=''>";
                    echo "<input type='hidden' name='residenceName' value='{$residenceName}'>";
                    echo "<button type='submit' name='delete' onclick='return confirm(\"This action cannot be undone. Are you sure you want to delete this accommodation?\")'>Delete {$residenceName} </button>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "No residences available.";
            }
            ?>
        </div>

        <button id="applyNowButton" class="apply-button">Add Accommodation</button>
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

        window.addEventListener('scroll', showApplyButton);
        window.addEventListener('mousemove', showApplyButton);
        window.addEventListener('keypress', showApplyButton);

        applyButton.addEventListener('click', () => {
            window.location.href = 'addAccommodation.php';
        });

        resetInactivityTimer();

        // Side panel script
        function openSidePanel() {
            document.getElementById("sidePanel").classList.add("open-side-panel");
            document.addEventListener('click', outsideClickListener);
        }

        function closeSidePanel() {
            document.getElementById("sidePanel").classList.remove("open-side-panel");
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
