<?php
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

$reservation_id = $_POST['reservation_id'];
$status = $_POST['status'];

// Check if reservation_id and status are set
if (isset($reservation_id) && isset($status)) {
    $stmt = $conn->prepare("UPDATE reservation SET status=? WHERE reservation_id=?");
    $stmt->bind_param("si", $status, $reservation_id);
    
    if ($stmt->execute() === TRUE) {
        echo "success";
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid parameters.";
}

$conn->close();
?>
