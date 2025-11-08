<?php 
session_start();
require 'dbFile/database.php';

// Check if user is logged in
if (empty($_SESSION['username']) || empty($_SESSION['password'])) {
    header("location: login.php");
    exit();
}

// Get user role
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch user role
$sqlUser = "SELECT user_role FROM tblUser WHERE username = '$username'";
$resultUser = mysqli_query($conn, $sqlUser);
$userData = mysqli_fetch_assoc($resultUser);
$userRole = $userData['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Booking Management</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;   
        }
        .containerEx {
            max-width: 900px;
            margin-top: 30px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-primary {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        table th, table td {
            text-align: center;
            padding: 10px;
        }
        
        .hallbook-container {
            text-align: right;
            margin-bottom: 20px;
        }
        
        table th {
            background-color: #007bff;
            color: white;
        }
        .action-btns a {
            margin-right: 10px;
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .action-btns .approve-btn {
            background-color: #28a745;
        }
        .action-btns .deny-btn {
            background-color: #dc3545;
        }
        .action-btns .edit-btn {
            background-color: #ffc107;
        }
        .action-btns .delete-btn {
            background-color: #dc3545;
        }
        .action-btns a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
<div class="containerHB">
    <h2>Hall Booking Management</h2>
    <!-- Add Hall Booking Button -->
    <div class="hallbook-container">
        <form method="post">
            <input type="submit" class="btn btn-primary" name="btnHallBook" value="Add Hall Book" formaction="hallBookingApp.php">
        </form>
    </div>

    <div class="table-responsive">
        <?php
        // Fetch data from the tblHallBooking table
        $sql = "SELECT * FROM tblHallBooking ORDER BY booking_date DESC";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<table class='table table-bordered'>";
                echo "<thead class='thead-dark'>
                    <tr>
                        <th>Hall Name</th>
                        <th>Booking Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Purpose</th>
                        <th>Booking Status</th>
                        <th>Action</th>
                    </tr>
                </thead>";
                echo "<tbody>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['hall_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['booking_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['end_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['purpose']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['booking_status']) . "</td>";

                    // Action buttons based on user role and booking status
                    echo "<td class='action-btns'>";
                    if ($userRole == 'secretary' && $row['booking_status'] === 'Pending') {
                        echo '<a href="bookingStatus.php?booking_id=' . htmlspecialchars($row['booking_id']) . '&action=approve" class="approve-btn">Approve</a> | 
                        <a href="bookingStatus.php?booking_id=' . htmlspecialchars($row['booking_id']) . '&action=deny" class="deny-btn">Deny</a>';
                    } elseif ($userRole == 'owner') {
                        if ($row['booking_status'] === 'Pending') {
                            echo '<a href="hallBookingApp.php?booking_id=' . htmlspecialchars($row['booking_id']) . '" class="edit-btn">Edit</a> |
                            <a href="hallBookingDelete.php?booking_id=' . htmlspecialchars($row['booking_id']) . '" class="delete-btn">Delete</a>';
                        } else {
                            echo '-'; // No action for approved/rejected bookings
                        }
                    } else {
                        echo '-'; // Other roles do not get action buttons
                    }
                    echo "</td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>No bookings found.</p>";
            }
        } else {
            echo "<p>Error fetching data: " . mysqli_error($conn) . "</p>";
        }
        ?>
    </div>
</div>

<!-- Bootstrap JS & dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
