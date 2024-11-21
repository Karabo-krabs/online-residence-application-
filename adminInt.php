
<?php
// Start the session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['adminNumber'])) {
    // If not logged in, redirect to login page
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Applications</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: gray; /* Light background color */
            color: white; /* Dark text color */
        }
        .back-button {
            position: absolute;
            top: 25px;
            left: 25px;
            background-color: white;
            color: #b22222;
            border: 2px solid #b22222;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #b22222;
            color: white;
        }

        h1 {
            text-align: center; /* Center the title */
            color: white; /* Dark red color */
        }
        /* Header Style */
        .h1 {
            background-color: rgb(179, 40, 40); /* Reddish background color */
            color: white; /* White text color */
            padding: 20px; /* Padding around the text */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Slight shadow for depth */
            text-align: center; /* Center the text */
        }


        /* Application Entry Styles */
        div {
        /* White background for each application */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Light shadow for depth */
            padding: 15px; /* Padding inside the application box */
            margin-bottom: 15px; /* Space between application entries */
        }

        /* Application Details */
        p {
            margin: 5px 0; /* Space between paragraphs */
        }

        /* Button Styles */
        button {
            padding: 10px 15px; /* Padding inside buttons */
            margin: 5px; /* Space between buttons */
            background-color: rgb(92, 16, 16); /* Dark red background */
            color: white; /* White text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }

        button:hover {
            background-color: darkred; /* Darker red on hover */
        }

    </style>
    
</head>
<body>
    <div class = "h1">
    <a href="adminDashboard.php" class="back-button">Back</a>
        <h1>Accommodation Applications</h1>
    </div>
    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "OneTwoThree"; // Your MySQL root password
    $dbname = "InformationSystems";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch applications
    $sql = "SELECT * FROM studentRegistration WHERE appStatus = 'Application Submitted'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<p>Name: " . $row['studentName'] . " " . $row['studentSurname'] . "</p>";
            echo "<p>Student Number: " . $row['studentNumber'] . "</p>";
            echo "<p>Gender: " . $row['gender'] . "</p>";
            echo "<p>Accommodation: " . $row['accommodation'] . "</p>";

            // Form for Accept
            echo "<form action='updateApplication.php' method='POST' style='display:inline;'>";
            echo "<input type='hidden' name='student_number' value='" . $row['studentNumber'] . "'>";
            echo "<input type='hidden' name='action' value='Accepted'>";
            echo "<input type='hidden' name='accommodation' value='" . $row['accommodation'] . "'>"; // Pa
            echo "<button type='submit'>Accept</button>";
            echo "</form>";

            // Form for Decline
            echo "<form action='updateApplication.php' method='POST' style='display:inline;'>";
            echo "<input type='hidden' name='student_number' value='" . $row['studentNumber'] . "'>";
            echo "<input type='hidden' name='action' value='Declined'>";
            echo "<input type='hidden' name='accommodation' value='" . $row['accommodation'] . "'>"; // Pa
            echo "<button type='submit'>Decline</button>";
            echo "</form>";

            echo "</div><hr>";
        }
    } else {
        echo "<h3>No applications submitted.</h3>";
    }

    // Close the connection
    $conn->close();
    ?>
</body>
</html>
