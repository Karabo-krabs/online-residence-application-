<?php
// Database connection details
$servername = "localhost";
$db_username = "root"; // Your MySQL username
$db_password = "OneTwoThree"; // Your MySQL password
$dbname = "InformationSystems"; // Your database name

// Create a connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form input values
    $input_username = trim($_POST['username']);
    $input_password = trim($_POST['password']);
    $userType = $_POST['userType']; // Check which user type is selected (student or admin)

    // Validate if inputs are not empty
    if (empty($input_username) || empty($input_password)) {
        echo "<script>alert('Please fill in all fields.');</script>";
        exit;
    }

    if ($userType == 'student') {
        // If user is a student
        $stmt = $conn->prepare("SELECT studentPassword FROM Student WHERE studentNumber = ?");
        
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Validate student number is numeric
        if (!is_numeric($input_username)) {
            echo "<script>alert('Invalid Student Number format.');</script>";
            exit;
        }

        $stmt->bind_param("i", $input_username); // Assuming studentNumber is an integer

    } else if ($userType == 'admin') {
        // If user is an admin, first check if the username exists in the Employee table
        $employeeCheckStmt = $conn->prepare("SELECT employeeNumber FROM Employee WHERE employeeNumber = ?");

        if (!$employeeCheckStmt) {
            die("Prepare failed: " . $conn->error);
        }

        $employeeCheckStmt->bind_param("s", $input_username); // Assuming employeeNumber is a string
        $employeeCheckStmt->execute();
        $employeeCheckStmt->store_result();

        // Check if the user exists in the Employee table
        if ($employeeCheckStmt->num_rows > 0) {
            // Now check the admin table for the corresponding admin number
            $stmt = $conn->prepare("SELECT password FROM Admin WHERE adminNumber = ?");
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("s", $input_username); // Assuming adminNumber is a string
        } else {
            echo "<script>alert('You are not a registered employee.');</script>";
            exit; // Stop further execution if not an employee
        }

        $employeeCheckStmt->close();
    }

    // Execute the statement
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        // Fetch the password hash
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($input_password, $hashed_password)) {
            // Start a session and regenerate session ID
            session_start();
            session_regenerate_id(true);
            if ($userType == 'student') {
                $_SESSION['studentNumber'] = $input_username; // Store student number in session
                header("Location: homeStu.php");
            } else if ($userType == 'admin') {
                $_SESSION['adminNumber'] = $input_username; // Store admin number in session
                header("Location: adminDashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid password.');</script>";
            echo"<script>window.location.href = 'login.html';</script>";
        }
    } else {
        echo "<script>alert('No user found with that username.');</script>";
        echo"<script>window.location.href = 'login.html';</script>";
        exit;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
