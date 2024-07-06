<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/Applications/XAMPP/xamppfiles/htdocs/IntiShuttle/src/Exception.php';
require '/Applications/XAMPP/xamppfiles/htdocs/IntiShuttle/src/PHPMailer.php';
require '/Applications/XAMPP/xamppfiles/htdocs/IntiShuttle/src/SMTP.php';

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
$comment = isset($_POST['comment']) ? $_POST['comment'] : "";

// Check if reservation_id and status are set
if (isset($reservation_id) && isset($status)) {
    // Require comment if status is rejected
    if ($status === 'rejected' && empty($comment)) {
        echo "Comment is required when rejecting a reservation.";
        exit;
    }

    $stmt = $conn->prepare("UPDATE reservation SET status=? WHERE reservation_id=?");
    $stmt->bind_param("si", $status, $reservation_id);

    if ($stmt->execute() === TRUE) {
        echo "success";

        // Fetch reservation details
        $stmt = $conn->prepare("SELECT name, email, date, dep_time, ret_time FROM reservation WHERE reservation_id=?");
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $stmt->bind_result($name, $email, $date, $dep_time, $ret_time);
        $stmt->fetch();
        $stmt->close();

        // Check if email is fetched correctly
        if ($email) {
            // Send email notification
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'heartx8880@gmail.com';
                $mail->Password = 'npnm bymz edcc zaoa';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('heartx8880@gmail.com', 'Shuttle Bus Reservation');
                $mail->addAddress($email, $name); // Add a recipient
                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Reservation Status Update';
                $mail->Body = "Dear $name,<br><br>Your reservation has been <b>$status</b>.<br>Date: $date<br>Departure Time: $dep_time<br>Return Time: $ret_time<br><br><b>Admin Comment:</b> $comment<br><br>Best regards,<br>Admin<br>INTI INTERNATIONAL COLLEGE PENANG
1-Z, Lebuh Bukit Jambul
11900 Penang";
                $mail->AltBody = "Dear $name,\n\nYour reservation has been $status.\nDate: $date\nDeparture Time: $dep_time\nReturn Time: $ret_time\n\nAdmin Comment: $comment\n\nBest regards,\nAdmin\nINTI INTERNATIONAL COLLEGE PENANG
1-Z, Lebuh Bukit Jambul
11900 Penang";

                if ($mail->send()) {
                    echo 'Email has been sent';
                } else {
                    echo 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
                }
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Email address not found for the reservation.";
        }
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid parameters.";
}

$conn->close();
?>
