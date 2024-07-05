<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";  // Change this to your phpMyAdmin username
$password = "";  // Change this to your phpMyAdmin password
$dbname = "api_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGet($conn);
        break;
    case 'POST':
        handlePost($conn);
        break;
    case 'PUT':
        handlePut($conn);
        break;
    case 'DELETE':
        handleDelete($conn);
        break;
    default:
        echo json_encode(["error" => "Invalid request method"]);
        break;
}

$conn->close();

function handleGet($conn) {
    $id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;
    $sql = $id ? "SELECT * FROM students WHERE student_id = $id" : "SELECT * FROM students";
    $result = $conn->query($sql);

    $students = [];
    while($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);
}

function handlePost($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $password = $conn->real_escape_string($data['password']);
    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $phone = $conn->real_escape_string($data['phone']);

    $sql = "INSERT INTO students (password, name, email, phone) VALUES ('$password', '$name', '$email', '$phone')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "New student created successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $conn->error]);
    }
}

function handlePut($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $student_id = intval($data['student_id']);
    $password = $conn->real_escape_string($data['password']);
    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $phone = $conn->real_escape_string($data['phone']);

    $sql = "UPDATE students SET password='$password', name='$name', email='$email', phone='$phone' WHERE student_id=$student_id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Student updated successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $conn->error]);
    }
}

function handleDelete($conn) {
    $student_id = intval($_GET['student_id']);
    $sql = "DELETE FROM students WHERE student_id=$student_id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Student deleted successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $conn->error]);
    }
}
?>
