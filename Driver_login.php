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
$driver_id = $_POST['driver_id'];
$password = $_POST['password'];

// Prepare and bind
$stmt = $conn->prepare("SELECT * FROM driver WHERE driver_id = ? AND password = ?");
$stmt->bind_param("ss", $driver_id, $password);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Correct login
    $row = $result->fetch_assoc();
    
    // Store user data in session variables
    $_SESSION['driver_id'] = $row['driver_id'];
    $_SESSION['name'] = $row['name'];
    $_SESSION['phone'] = $row['phone'];
    
    // Redirect to Student.html
    header("Location: Driver_Login.php");
    exit();
} else {
    // Incorrect login
    echo "<script>alert('Incorrect username or password'); window.location.href='Driver_Login.php';</script>";
}

// Close the connection
$stmt->close();
$conn->close();
?>

