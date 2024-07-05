<?php
session_start();
if (!isset($_SESSION['route'])) {
    echo json_encode([]);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$driver_route = $_SESSION['route'];
$sql = "SELECT * FROM reservation WHERE status='accepted' AND route=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $driver_route);
$stmt->execute();
$result = $stmt->get_result();

$reservations = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}
$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($reservations);
?>
