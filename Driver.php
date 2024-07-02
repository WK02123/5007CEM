<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Driver Page</title>
        <link rel="stylesheet" href="css/Style6.css"/> <!-- Replace with your CSS file -->
    </head>
    <body>
        <h1>Accepted Reservations</h1>
        <h1>Welcome, <?php echo($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : 'Driver'; ?></h1>

        <table id="acceptedReservations">
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Route</th>
                    <th>Departure Time</th>
                    <th>Return Time</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table rows will be populated dynamically with JavaScript -->
            </tbody>
        </table>

        <script> document.addEventListener('DOMContentLoaded', function () {
                fetch('Driver_fetch.php') // Create this PHP file to fetch accepted reservations
                        .then(response => response.json())
                        .then(data => {
                            let tableBody = document.querySelector('#acceptedReservations tbody');
                            data.forEach(reservation => {
                                let row = tableBody.insertRow();
                                row.insertCell(0).innerText = reservation.reservation_id;
                                row.insertCell(1).innerText = reservation.name;
                                row.insertCell(2).innerText = reservation.email;
                                row.insertCell(3).innerText = reservation.phone;
                                row.insertCell(4).innerText = reservation.date;
                                row.insertCell(5).innerText = reservation.route;
                                row.insertCell(6).innerText = reservation.dep_time;
                                row.insertCell(7).innerText = reservation.ret_time;
                            });
                        })
                        .catch(error => console.error("Fetch error:", error)); // Add error handling for fetch
            });
            
            

        </script> <!-- Replace with your JavaScript file -->
    </body>
</html>


