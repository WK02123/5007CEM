<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$servername = "localhost";
$username = "root"; // your phpMyAdmin username
$password = ""; // your phpMyAdmin password
$dbname = "student";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$student_id = $_POST['student_id'];
$password = $_POST['password'];

// Prepare and bind
$stmt = $conn->prepare("SELECT * FROM student2 WHERE student_id = ? AND password = ?");
$stmt->bind_param("ss", $student_id, $password);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    // Correct login
    $_SESSION['student_id'] = $student_id;
    header("Location: Student.html");
    exit();
} else {
    // Incorrect login
    echo "<script>alert('Incorrect username or password'); window.location.href='LoginPage.html';</script>";
}

// Close the connection
$stmt->close();
$conn->close();
?>