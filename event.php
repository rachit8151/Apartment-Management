<?php 
session_start();
require 'dbFile/database.php';

if (empty($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

// Get user role
$username = $_SESSION['username'];
$sqlUser = "SELECT user_role FROM tblUser WHERE username = '$username'";
$resultUser = mysqli_query($conn, $sqlUser);
$userData = mysqli_fetch_assoc($resultUser);
$userRole = $userData['user_role']; // Get the user role
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .containerEve {
            margin-top: 40px;
        }
        .event-container {
            margin-bottom: 20px;
            text-align: right;
        }
        .event-table th, .event-table td {
            text-align: center;
        }
        .btn-custom {
            width: 100%;
        }
        .alert {
            margin-top: 20px;
        }
        table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="containerEve">
        <h1 class="text-center mb-4">Event Management</h1>
        <div class="event-container">
            <?php if ($userRole !== 'owner') : ?>
                <form method="post">
                    <input type="submit" name="btnAddEvent" value="Add Event" formaction="eventApp.php" class="btn btn-primary">
                </form>
            <?php endif; ?>
        </div>

        <?php
        // Fetch events from the database
        $sql = "SELECT * FROM tblEvents ORDER BY event_id DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table table-bordered table-striped event-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Description</th>';
                            if ($userRole !== 'owner') {
                                echo '<th>Action</th>';
                            }
            echo '</tr>
                </thead>
                <tbody>';

            // Loop through the events and display them
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['event_date']) . '</td>';
                echo '<td>' . htmlspecialchars($row['event_time']) . '</td>';
                echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                if ($userRole !== 'owner') {
                    echo '<td>
                            <a href="eventApp.php?event_id=' . $row['event_id'] . '" class="btn btn-warning btn-sm">Edit</a>
                            <a href="eventDelete.php?event_id=' . $row['event_id'] . '" 
                               onclick="return confirm(\'Are you sure you want to delete this event?\');" class="btn btn-danger btn-sm">Delete</a>
                          </td>';
                }
                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p class="alert alert-info text-center">No events available.</p>';
        }
        ?>
    </div>

    <!-- Bootstrap JS & dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
