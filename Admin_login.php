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
$admin_id = $_POST['admin_id'];
$password = $_POST['password'];

// Prepare and bind
$stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id = ? AND password = ?");
$stmt->bind_param("ss", $admin_id, $password);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    // Correct login
    $_SESSION['admin_id'] = $admin_id;
    header("Location: Admin.html");
    exit();
} else {
    // Incorrect login
    echo "<script>alert('Incorrect admin_id or password'); window.location.href='Admin_LOGIN.html';</script>";
}

// Close the connection
$stmt->close();
$conn->close();
?>
