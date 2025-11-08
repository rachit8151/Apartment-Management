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
$userRole = $userData['user_role'];

$booking_id = null;

// Handle booking addition or update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['btnBooking'])) {
        if (isset($_POST['booking_id'])) {
            // Update existing booking
            getEdit($conn);
        } else {
            // Add new booking
            getAdd($conn);
        }
    }
}

// Function to add a Hall Booking
function getAdd($conn) {
    $hall_name = mysqli_real_escape_string($conn, $_POST['hall_name']);
    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);
    $booking_time = mysqli_real_escape_string($conn, $_POST['booking_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
    $booking_status = 'Pending'; // Default booking status
    $user_id = $_SESSION['user_id'];

    // Validate the date
    if (strtotime($booking_date) < strtotime(date('Y-m-d'))) {
        echo '<script>alert("You cannot apply a previous date. Please choose a valid date."); window.history.back();</script>';
        return;
    }

    // Check for existing bookings
    $sqlCheck = "SELECT * FROM tblHallBooking WHERE hall_name = '$hall_name' AND booking_date = '$booking_date'";
    $resultCheck = mysqli_query($conn, $sqlCheck);

    if (!$resultCheck) {
        die("Database query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($resultCheck) > 0) {
        echo '<script>alert("Hall is already booked on this date."); window.history.back();</script>';
        return;
    }

    // Insert new booking
    $sql = "INSERT INTO tblHallBooking (user_id, hall_name, booking_date, booking_time, end_time, purpose, booking_status) 
            VALUES ('$user_id', '$hall_name', '$booking_date', '$booking_time', '$end_time', '$purpose', '$booking_status')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Hall Booking added successfully."); window.location.href="dashboard.php";</script>';
    } else {
        echo '<script>alert("Something went wrong while adding the Hall Booking: ' . mysqli_error($conn) . '");</script>';
    }
}

// Function to edit a Hall Booking
function getEdit($conn) {
    $booking_id = (int) $_POST['booking_id'];
    $hall_name = mysqli_real_escape_string($conn, $_POST['hall_name']);
    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);
    $booking_time = mysqli_real_escape_string($conn, $_POST['booking_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);

    // Validate the date
    if (strtotime($booking_date) < strtotime(date('Y-m-d'))) {
        echo '<script>alert("You cannot apply a previous date. Please choose a valid date."); window.history.back();</script>';
        return;
    }

    // Check for existing bookings excluding the current one
    $sqlCheck = "SELECT * FROM tblHallBooking WHERE hall_name = '$hall_name' AND booking_date = '$booking_date' AND booking_id != $booking_id";
    $resultCheck = mysqli_query($conn, $sqlCheck);

    if (!$resultCheck) {
        die("Database query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($resultCheck) > 0) {
        echo '<script>alert("Hall is already booked on this date."); window.history.back();</script>';
        return;
    }

    // Update the booking
    $sql = "UPDATE tblHallBooking SET hall_name='$hall_name', booking_date='$booking_date', booking_time='$booking_time', 
            end_time='$end_time', purpose='$purpose' 
            WHERE booking_id=$booking_id";

    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Hall Booking updated successfully."); window.location.href="dashboard.php";</script>';
    } else {
        echo '<script>alert("Something went wrong while updating the Hall Booking: ' . mysqli_error($conn) . '");</script>';
    }
}

// Check if a booking ID is set for editing
$hallbook = null;
if (isset($_GET['booking_id'])) {
    $booking_id = (int) $_GET['booking_id'];
    $sql = "SELECT * FROM tblHallBooking WHERE booking_id = $booking_id";
    $result = mysqli_query($conn, $sql);
    $hallbook = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $booking_id ? 'Update Hall Booking' : 'Add Hall Booking'; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        h4 {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-submit {
            width: 100%;
            padding: 10px;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="container">
    <h4><?php echo $booking_id ? 'Update Hall Booking' : 'Add Hall Booking'; ?></h4>

    <form method="post">
        <?php if ($hallbook): ?>
            <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="hall_name">Hall Name:</label>
            <input type="text" id="hall_name" name="hall_name" class="form-control" value="<?php echo $hallbook['hall_name'] ?? ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="booking_date">Booking Date:</label>
            <input type="date" id="booking_date" name="booking_date" class="form-control" value="<?php echo $hallbook['booking_date'] ?? ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="booking_time">Booking Time:</label>
            <input type="time" id="booking_time" name="booking_time" class="form-control" value="<?php echo $hallbook['booking_time'] ?? ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="end_time">End Time:</label>
            <input type="time" id="end_time" name="end_time" class="form-control" value="<?php echo $hallbook['end_time'] ?? ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="purpose">Purpose:</label>
            <textarea id="purpose" name="purpose" class="form-control" required><?php echo $hallbook['purpose'] ?? ''; ?></textarea>
        </div>

        <input type="submit" name="btnBooking" class="btn btn-primary btn-submit" value="<?php echo $hallbook ? 'Update Hall Booking' : 'Add Hall Booking'; ?>">
    </form>
</div>

<!-- Bootstrap JS & dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
