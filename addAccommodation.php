<?php
// Start the session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['adminNumber'])) {
    // If not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

// Database connection parameters
$host = 'localhost'; // Change to your database host
$username = 'root'; // Change to your database username
$password = 'OneTwoThree'; // Change to your database password
$database = 'InformationSystems'; // Change to your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $residenceName = $_POST['residenceName'];
    $totalRooms = $_POST['totalRooms'];
    $address = $_POST['address'];
    $images = $_POST['images'];
    $links = $_POST['links']; // Get the new links input

    // Prepare the SQL statement to insert into Residences
    $sql = "INSERT INTO Residences (residenceName, totalRooms, address, images, links) VALUES (?, ?, ?, ?, ?)";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisss", $residenceName, $totalRooms, $address, $images, $links); // Include links

    // Execute the statement
    if ($stmt->execute()) {
        echo "<p>New residence added successfully!</p>";

        // Create a new table for the residence
        $tableName = $residenceName; // Use residence name as table name
        $createTableSQL = "CREATE TABLE IF NOT EXISTS `$tableName` (
            {$tableName}ID INT AUTO_INCREMENT PRIMARY KEY,
            studentNumber INT NOT NULL,
            roomNumber INT NOT NULL
        )";

        if ($conn->query($createTableSQL) === TRUE) {
            echo "<p>Table '$tableName' created successfully!</p>";
        } else {
            echo "<p>Error creating table: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Residence</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
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

        header {
            background-color: #b22222;
            color: white;
            padding: 20px;
            text-align: center;
        }

        h1 {
            margin: 0;
            font-size: 30px;
        }

        .form-container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .form-container h2 {
            text-align: center;
            color: #b22222;
            font-size: 24px;
        }

        .form-container label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-container input[type="text"],
        .form-container input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #b22222;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-container input[type="submit"]:hover {
            background-color: #a02020;
        }
    </style>
</head>
<body>

<header>
    <a href="adminDashboard.php" class="back-button">Back</a>
    <h1>Add Residence</h1>
</header>

<div class="form-container">
    <h2>Residence Information</h2>
    <form action="" method="POST">
        <label for="residenceName">Residence Name:</label>
        <input type="text" id="residenceName" name="residenceName" required>

        <label for="totalRooms">Total Rooms:</label>
        <input type="number" id="totalRooms" name="totalRooms" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>

        <label for="images">Images (filename):</label>
        <input type="text" id="images" name="images" required>

        <label for="links">Link:</label>
        <input type="text" id="links" name="links">

        <input type="submit" value="Add Residence">
    </form>
</div>

</body>
</html>
