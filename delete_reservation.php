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

// Check if reservation_id is set
if (isset($reservation_id)) {
    $stmt = $conn->prepare("DELETE FROM reservation WHERE reservation_id=?");
    $stmt->bind_param("i", $reservation_id);
    
    if ($stmt->execute() === TRUE) {
        echo "success";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid parameters.";
}

$conn->close();
?>
