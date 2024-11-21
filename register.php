<?php
// Database connection parameters
$host = 'localhost'; 
$db = 'InformationSystems'; 
$user = 'root'; 
$pass = 'OneTwoThree'; 

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("<script>alert('Connection failed: " . $conn->connect_error . "');</script>");
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $conn->real_escape_string(trim($_POST['name']));
    $surname = $conn->real_escape_string(trim($_POST['surname']));
    $number = trim($_POST['StudentNumber']);
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password1 = $conn->real_escape_string(trim($_POST['password1']));
    $password2 = $conn->real_escape_string(trim($_POST['password2']));
    $role = $_POST['role'];

    // Validate required fields
    if (empty($name) || empty($surname) || empty($number) || empty($email) || empty($password1) || empty($password2) || empty($role)) {
        echo "<script>alert('All fields are required.');</script>";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
        exit;
    }

    // Validate passwords match
    if ($password1 !== $password2) {
        echo "<script>alert('Passwords do not match.');</script>";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

    if ($role == 'student') {
        // Check if the student is already registered
        $check_stmt = $conn->prepare("SELECT * FROM Student WHERE studentNumber = ? OR studentEmail = ?");
        $check_stmt->bind_param("is", $number, $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "<script>alert('Student already registered with this number or email.');</script>";
            exit;
        }
        $check_stmt->close();

        // Student registration
        $stmt = $conn->prepare("INSERT INTO Student (studentName, studentSurname, studentNumber, studentEmail, studentPassword) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $name, $surname, $number, $email, $hashed_password);
        
        if ($stmt->execute()) {
            echo "<script>alert('Student registration successful!');</script>";
            echo "<script>window.location.href = 'login.html';</script>";
        } else {
            echo "<script>alert('Error during student registration: " . $stmt->error . "');</script>";
        }
        
        $stmt->close();

    } else if ($role == 'admin') {
        // Check if employee exists in Employee table
        $stmt = $conn->prepare("SELECT * FROM Employee WHERE employeeName = ? AND employeeSurname = ? AND employeeNumber = ?");
        $stmt->bind_param("sss", $name, $surname, $number);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            
            // Check if the admin is already registered
            $check_stmt = $conn->prepare("SELECT * FROM Admin WHERE adminNumber = ? OR adminEmail = ?");
            $check_stmt->bind_param("is", $number, $email);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                echo "<script>alert('Admin already registered with this number or email.');</script>";
                exit;
            }
            $check_stmt->close();

            // Admin registration
            $stmt = $conn->prepare("INSERT INTO Admin (adminName, adminSurname, adminNumber, adminEmail, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $surname, $number, $email, $hashed_password);
            
            if ($stmt->execute()) {
                echo "<script>alert('Admin registration successful!');</script>";
                echo "<script>window.location.href = 'login.html';</script>";
            } else {
                echo "<script>alert('Error during admin registration: " . $stmt->error . "');</script>";
            }

        } else {
            // Employee does not exist
            echo "<script>alert('You are not a registered employee.');</script>";
        }
        
        $stmt->close();
    }
}

$conn->close();
?>
