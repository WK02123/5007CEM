<?php
session_start();

$confirmation_message = ""; // Initialize confirmation message variable
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $name = htmlspecialchars($_SESSION['name']);
    $email = htmlspecialchars($_SESSION['email']);
    $phone = htmlspecialchars($_SESSION['phone']);
    $date = htmlspecialchars($_POST['date']);
    $route = htmlspecialchars($_POST['pickup']); // Assuming 'pickup' corresponds to 'route'
    $dep_time = htmlspecialchars($_POST['departure']);
    $ret_time = htmlspecialchars($_POST['return']);
    $student_id = $_SESSION['student_id']; // Retrieve student_id from session
    // Validate input fields
    if (empty($date) || empty($route) || empty($dep_time) || empty($ret_time)) {
        $confirmation_message = "Please fill out all required fields.";
    } else {
        // Connect to your MySQL database (adjust these settings according to your configuration)
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

        // Prepare SQL query to insert reservation data into the 'reservation' table
        $sql = "INSERT INTO reservation (student_id, name, email, phone, date, route, dep_time, ret_time)
                VALUES ('$student_id', '$name', '$email', '$phone', '$date', '$route', '$dep_time', '$ret_time')";

        // Attempt to execute SQL query
        if ($conn->query($sql) === TRUE) {
            $confirmation_message = "Reservation successful!";

            // Redirect to homepage.html after 3 seconds
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'homepage.html';
                    }, 3000);
                </script>";
        } else {
            $confirmation_message = "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Student Shuttle Reservation</title>
        <link rel="stylesheet" href="css/Style7.css"/>
        <style>
            .confirmation-dialog {
                display: none;
                position: fixed;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                background-color: #fff;
                border: 1px solid #ccc;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                max-width: 80%;
            }
            .confirmation-dialog h3 {
                margin-top: 0;
            }
            .confirmation-dialog p {
                margin-bottom: 10px;
            }
            .confirmation-dialog .btn {
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                border-radius: 5px;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="reservation-box">
                <h2>Shuttle Bus Reservation</h2>
                <form id="reservationForm" method="post">
                    <!-- Hidden input field for student_id -->
                    <input type="hidden" id="student_id" name="student_id" value="<?php echo $_SESSION['student_id']; ?>">

                    <div class="textbox">
                        <input type="text" id="name" name="name" readonly>
                    </div>
                    <div class="textbox">
                        <input type="email" id="email" name="email" readonly>
                    </div>
                    <div class="textbox">
                        <input type="tel"  id="phone" name="phone" readonly>
                    </div>
                    <div class="textbox">
                        <input type="date" name="date" required>
                    </div>
                    <div class="textbox">
                        <label for="pickup">Pickup Location:</label>
                        <select id="pickup" name="pickup" required>
                            <option value="Relau">Relau</option>
                            <option value="Bukit Jambul">Bukit Jambul</option>
                            <option value="Sungai Ara">Sungai Ara</option>
                        </select>
                    </div>
                    <div class="textbox">
                        <label for="departure">Departure Time:</label>
                        <select id="departure" name="departure" required>
                            <option value="7am">7am</option>
                            <option value="9am">9am</option>
                            <option value="11am">11am</option>
                        </select>
                    </div>
                    <div class="textbox">
                        <label for="return">Return Time:</label>
                        <select id="return" name="return" required>
                            <option value="12.20pm">12.20pm</option>
                            <option value="2.20pm">2.20pm</option>
                            <option value="3.20pm">3.20pm</option>
                            <option value="4.20pm">4.20pm</option>
                            <option value="6.20pm">6.20pm</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">Reserve</button>
                </form>
            </div>
        </div>

        <!-- Confirmation Dialog -->
        <div id="confirmationDialog" class="confirmation-dialog">
            <h3>Reservation Confirmation</h3>
            <p id="confirmationMessage"></p>
            <button id="closeDialogBtn" class="btn">Close</button>
        </div>

        <script>
            // JavaScript to populate form fields with session data
            document.getElementById('name').value = "<?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : ''; ?>";
            document.getElementById('email').value = "<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>";
            document.getElementById('phone').value = "<?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : ''; ?>";

            // Display confirmation dialog if PHP sets confirmation message
<?php if (!empty($confirmation_message)) { ?>
                var confirmationDialog = document.getElementById('confirmationDialog');
                var confirmationMessage = document.getElementById('confirmationMessage');
                var closeDialogBtn = document.getElementById('closeDialogBtn');

                confirmationMessage.innerHTML = "<?php echo $confirmation_message; ?>";
                confirmationDialog.style.display = 'block';

                // Close dialog and redirect when Close button is clicked
                closeDialogBtn.addEventListener('click', function () {
                    confirmationDialog.style.display = 'none';
                    window.location.href = 'LoginPage.html'; // Redirect to homepage
                });
<?php } ?>
        </script>

    </body>
</html>
