<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "OneTwoThree"; // Your MySQL root password
$dbname = "InformationSystems";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the POST data from the form
$student_number = $_POST['student_number'];
$action = $_POST['action'];  // Either 'Accepted' or 'Declined'
$accommodation = $_POST['accommodation'];  // Now it will always be passed

// Sanitize inputs
$student_number = intval($student_number);
$action = $conn->real_escape_string($action);
$accommodation = $conn->real_escape_string($accommodation); // Prevent SQL injection

// Update the application status based on the action (Accept/Decline)
$sql = "UPDATE studentRegistration SET appStatus = '$action' WHERE studentNumber = $student_number";

if ($conn->query($sql) === TRUE) {
    if ($action == 'Accepted' && !empty($accommodation)) {
        // Increment studentsAccepted for the given accommodation
        $updateAccommodationSQL = "UPDATE residences SET studentsAccepted = studentsAccepted + 1 WHERE residenceName = '$accommodation'";
        
        if ($conn->query($updateAccommodationSQL) === TRUE) {
            // Determine the accommodation table based on the accommodation name
            $accommodationTable = '';
            switch($accommodation) {
                case 'Moroka':
                    $accommodationTable = 'Moroka';
                    break;
                case 'Umnandi':
                    $accommodationTable = 'Umnandi';
                    break;
                case 'Hannetjie':
                    $accommodationTable = 'Hannetjie';
                    break;
                case 'Mhudi':
                    $accommodationTable = 'Mhudi';
                    break;
                case 'Rathaga':
                    $accommodationTable = 'Rathaga';
                    break;
                case 'Tauana':
                    $accommodationTable = 'Tauana';
                    break;
                default:
                    echo "Invalid accommodation selection.";
                    exit();
            }

            // Get the next room number for the accommodation
            $getRoomNumberSQL = "SELECT COALESCE(MAX(roomNumber), 0) + 1 AS nextRoomNumber FROM $accommodationTable";
            $result = $conn->query($getRoomNumberSQL);
            $row = $result->fetch_assoc();
            $roomNumber = $row['nextRoomNumber'];

            // Insert the student number and room number into the accommodation table
            $insertRoomSQL = "INSERT INTO $accommodationTable (studentNumber, roomNumber) VALUES ($student_number, $roomNumber)";
            if ($conn->query($insertRoomSQL) === TRUE) {
                echo "<script>alert('Application status updated. Students accepted for accommodation: <strong>" . $accommodation . "</strong> with room number: " . $roomNumber . ".');";

                echo "window.location.href = 'adminInt.php';</script>";
            } else {
                echo "Error inserting into accommodation table: " . $conn->error; // Log the error
            }
        } else {
            echo "Error updating accommodation for Accepted: " . $conn->error; // Log the error
        }
    } else {
        echo "<script>alert('Application Status Updated.');</script>";
    }
} else {
    echo "Error updating application: " . $conn->error;
}
?>
