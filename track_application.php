<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
    <link rel="stylesheet" href="path/to/your/styles.css"> <!-- Link to your CSS file -->
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: white; /* Light background color */
    color: white; /* Dark text color */
}

.result-container {
    background-color: gray; /* Slightly transparent white background */
    border-radius: 8px; /* Rounded corners */
    padding: 20px; /* Padding inside the container */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Light shadow for depth */
    margin: 20px auto; /* Center the result box on the page */
    max-width: 600px; /* Maximum width for the result box */

}

/* Header Style */
h1 {
    text-align: center; /* Center the title */
    color: white; /* White color for the title */
}

/* Paragraph Styles */
p {
    margin: 5px 0; /* Space between paragraphs */
}

/* Strong Text Style */
strong {
    color: white; /* Dark red color for strong text */
}

/* Back Button Styles */
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

/* Responsive Design */
@media (max-width: 600px) {
    .result-container {
        width: 90%; /* Responsive width for smaller screens */
    }
}

    </style>
</head>
<body>
    <a href="homeStu.php" class="back-button">Back</a>
    
    <?php
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "OneTwoThree";  // Your MySQL root password
    $dbname = "InformationSystems";  // Name of your database

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get student number from the form
        $student_number = $_POST['student_number'];

        // Prepare SQL to fetch application status
        $sql = "SELECT studentName, studentSurname, studentNumber, accommodation, appStatus FROM studentRegistration WHERE studentNumber = ?";
        $stmt = $conn->prepare($sql);

        // Check if the query preparation failed
        if ($stmt === false) {
            die("Error preparing the SQL statement: " . $conn->error);  // Display the error
        }

        // Bind the student number parameter
        $stmt->bind_param("i", $student_number);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any result was returned
        if ($result->num_rows > 0) {
            // Fetch the data and display it
            while ($row = $result->fetch_assoc()) {
                echo "<div class='result-container'>";
                echo "<h1>Application Status</h1>";
                echo "<p><strong>Name:</strong> " . $row['studentName'] . " " . $row['studentSurname'] . "</p>";
                echo "<p><strong>Student Number:</strong> " . $row['studentNumber'] . "</p>";
                echo "<p><strong>Accommodation Applied For:</strong> " . $row['accommodation'] . "</p>";
                echo "<p><strong>Status:</strong> " . $row['appStatus'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<div class='result-container'>";
            echo "<p>No application found for the provided student number.</p>";
            echo "</div>";
        }

        // Close connection
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
