<?php
// Start session
session_start();

// Check if the student is logged in
if (!isset($_SESSION['studentNumber'])) {
    // If not, redirect to the login page
    header("Location: login.html");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "OneTwoThree";  // Your MySQL root password
$dbname = "InformationSystems";  // Name of your database

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $student_number = $_POST['student_number'];
    $gender = $_POST['Gender'];
    $accommodation = $_POST['accommodation'];

    // Check if the student number already exists
    $checkSql = "SELECT * FROM studentRegistration WHERE studentNumber = $student_number";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        echo "<script>alert('Student number already exists! Please enter a different number.');</script>";
    } else {
        // Validate if the entered name, surname, and student number match the Student table
        $validateSql = "SELECT * FROM Student WHERE studentNumber = $student_number AND studentName = '$name' AND studentSurname = '$surname'";
        $validateResult = $conn->query($validateSql);

        if ($validateResult->num_rows == 0) {
            echo "<script>alert('Error: The name, surname, and student number do not match any records in the Student table.');</script>";
        } else {
            // SQL to insert data into the table
            $sql = "INSERT INTO studentRegistration (studentName, studentSurname, studentNumber, gender, appStatus, accommodation) 
                    VALUES ('$name', '$surname', $student_number, '$gender', 'Application Submitted', '$accommodation')";

            // Execute the query and check for success
            if ($conn->query($sql) === TRUE) {
                // Increment StudentsApplied for the given accommodation
                $updateAccommodationSQL = "UPDATE residences SET StudentsApplied = StudentsApplied + 1 WHERE residenceName = '$accommodation'";
                if ($conn->query($updateAccommodationSQL) === TRUE) {
                    echo "<script>alert('Application submitted successfully!');</script>";
                    echo "<script>window.location.href = 'homeStu.php';</script>";
                } else {
                    echo "Error updating accommodation: " . $conn->error;
                }
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

// Fetch accommodations from the database
$accommodations = [];
$accommodationQuery = "SELECT residenceName FROM Residences";
$accommodationResult = $conn->query($accommodationQuery);
if ($accommodationResult->num_rows > 0) {
    while ($row = $accommodationResult->fetch_assoc()) {
        $accommodations[] = $row['residenceName'];
    }
}

// Close the connection
$conn->close();
?>

<!-- The HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accommodation Application</title>
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

        .radio-group {
            margin-bottom: 20px;
        }

        .radio-group label {
            margin-right: 10px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #b22222;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-container button:hover {
            background-color: #a02020;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: rgb(92, 16, 16);
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center; /* Center text for link */
        }

        .btn:hover {
            background-color: darkred;
        }
    </style>

    <!-- JavaScript for Validation -->
    <script>
        function validateForm() {
            // Get the selected gender and accommodation
            var gender = document.querySelector('input[name="Gender"]:checked').value;
            var accommodation = document.querySelector('input[name="accommodation"]:checked').value;

            // Validate for males applying for Umnandi
            if (gender === "Male" && accommodation === "Umnandi") {
                alert("Application unsuccessful, you cannot apply for females accommodation when you are male.");
                return false; // Prevent form submission
            }

            // Validate for females applying for Hannetjie
            if (gender === "Female" && accommodation === "Hannetjie") {
                alert("Application unsuccessful, you cannot apply for males accommodation when you are female.");
                return false; // Prevent form submission
            }

            // If no issues, submit the form
            return true;
        }

        // JavaScript function to update the hidden input field with the selected accommodation
        function updateAccommodation(value) {
            document.getElementById('selected-accommodation').value = value;
        }
    </script>
</head>
<body>

<header>
    <a href="homeStu.php" class="back-button">Back</a>
    <h1>Accommodation Application</h1>
</header>

<div class="form-container">
    <h2>Apply for Accommodation</h2>
     
    <!-- Add onsubmit event handler for validation -->
    <form action="" method="POST" onsubmit="return validateForm()">
        <!-- User Information -->
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Enter your name" required>

        <label for="surname">Surname</label>
        <input type="text" id="surname" name="surname" placeholder="Enter your surname" required>

        <label for="student-number">Student Number</label>
        <input type="number" id="student-number" name="student_number" placeholder="Enter your student number" required>

        <!-- Gender Selection -->
        <div class="radio-group">
            <p><strong>Select Gender:</strong></p>
            <label>
                <input type="radio" name="Gender" value="Male" required> Male
            </label>
            <label>
                <input type="radio" name="Gender" value="Female"> Female
            </label>
        </div>

        <!-- Radio Buttons for Accommodation Selection -->
        <div class="radio-group">
            <p><strong>Select Accommodation:</strong></p>
            <?php foreach ($accommodations as $accommodation): ?>
                <label>
                    <input type="radio" name="accommodation" value="<?php echo $accommodation; ?>" required onclick="updateAccommodation(this.value)"> <?php echo $accommodation; ?>
                </label>
            <?php endforeach; ?>
        </div>

        <!-- Hidden input field for the selected accommodation -->
        <input type="hidden" id="selected-accommodation" name="selected_accommodation" value="">

        <!-- Submit Button -->
        <button type="submit">Submit Application</button>
    </form>
</div>

</body>
</html>
