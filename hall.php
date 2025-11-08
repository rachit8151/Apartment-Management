<?php
session_start();
require 'dbFile/database.php';

if (empty($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

// Get user role
$username = mysqli_real_escape_string($conn, $_SESSION['username']);
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
        <title>Manage Halls</title>
        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa;
            }
            .announcement-container {
                margin-bottom: 20px;
            }
            h2 {
                text-align: center;
                margin-top: 20px;
            }
            .table th, .table td {
                text-align: center;
                vertical-align: middle;
            }
            .btn-custom {
                margin: 5px;
            }
            .form-inline input, .form-inline button {
                margin-right: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container mt-3">
            <h2>Halls Management</h2>
            <div class="text-end mb-4">
                <?php if ($userRole !== 'owner') : ?>
                    <form method="post" action="hallApp.php">
                        <input type="submit" name="btnAddHall" class="btn btn-primary" value="Add Hall">
                    </form>
                <?php endif; ?>
            </div>
            <?php if (isset($message) && $message) : ?>
                <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <?php if ($userRole !== 'owner') : ?>
                <div class="form-inline mb-3">
                    <form method="post" action="hallAvailable.php">
                        <input type="text" name="hall_name" class="form-control" placeholder="Enter hall name" required>
                        <button type="submit" name="action" value="hide" class="btn btn-warning btn-custom">Hide</button>
                        <button type="submit" name="action" value="show" class="btn btn-info btn-custom">Show</button>
                    </form>
                </div>
            <?php endif; ?>
            <h6>Available Halls</h6>
            <?php
            // Fetch existing halls
            $sqlHalls = "SELECT hall_id, hall_name, capacity, location, amenities FROM tblHalls WHERE visible = 1";
            $result = mysqli_query($conn, $sqlHalls);

            if (!$result) {
                die("Database query failed: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($result) > 0) {
                echo '<table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>Capacity</th>
                        <th>Location</th>
                        <th>Amenities</th>';
                if ($userRole !== 'owner') {
                    echo '<th>Actions</th>';
                }
                echo '</tr>
                </thead>
                <tbody>';

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                    <td>' . htmlspecialchars($row['hall_name']) . '</td>
                    <td>' . htmlspecialchars($row['capacity']) . '</td>
                    <td>' . htmlspecialchars($row['location']) . '</td>
                    <td>' . htmlspecialchars($row['amenities']) . '</td>';
                    if ($userRole !== 'owner') {
                        echo '<td>
                        <a href="hallApp.php?hall_id=' . htmlspecialchars($row['hall_id']) . '" class="btn btn-primary btn-sm">Edit</a> &nbsp;
                        <a href="hallDelete.php?hall_id=' . htmlspecialchars($row['hall_id']) . '" 
                           onclick="return confirm(\'Are you sure you want to delete this hall?\');" class="btn btn-danger btn-sm">Delete</a>
                    </td>';
                    }
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<div class="alert alert-warning">No halls available.</div>';
            }
            ?>
        </div>

        <!-- Bootstrap JS & dependencies -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>
