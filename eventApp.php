<?php 
session_start();
require 'dbFile/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Get user role
$username = $_SESSION['username'];
$sqlUser = "SELECT user_role FROM tblUser WHERE username = '$username'";
$resultUser = mysqli_query($conn, $sqlUser);
$userData = mysqli_fetch_assoc($resultUser);
$userRole = $userData['user_role'];

// Handle event addition or update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['btnEvent'])) {
        if (isset($_POST['event_id'])) {
            // Update existing event
            getEdit($conn);
        } else {
            // Add new event
            getAdd($conn);
        }
    }
}

// Add event function
function getAdd($conn) {
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    if (strtotime($event_date) < strtotime(date('Y-m-d'))) {
        echo '<script>alert("You cannot apply a previous date. Please choose a valid date."); window.history.back();</script>';
        return;
    }
    $event_time = mysqli_real_escape_string($conn, $_POST['event_time']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO tblEvents (event_date, event_time, description, user_id) VALUES ('$event_date', '$event_time', '$description', $user_id)";
    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Event added successfully."); window.location.href="dashboard.php";</script>';
    } else {
        echo '<script>alert("Something went wrong with the event.");</script>';
    }
}

// Edit event function
function getEdit($conn) {
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    if (strtotime($event_date) < strtotime(date('Y-m-d'))) {
        echo '<script>alert("You cannot apply a previous date. Please choose a valid date."); window.history.back();</script>';
        return;
    }
    $event_time = mysqli_real_escape_string($conn, $_POST['event_time']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $event_id = intval($_POST['event_id']);

    $sql = "UPDATE tblEvents SET event_date='$event_date', event_time='$event_time', description='$description' WHERE event_id=$event_id";

    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Event updated successfully."); window.location.href="dashboard.php";</script>';
    } else {
        echo '<script>alert("Something went wrong with the update.");</script>';
    }
}

// Check if an event ID is set for editing
$event = null;
if (isset($_GET['event_id'])) {
    $event_id = (int) $_GET['event_id'];
    $sql = "SELECT * FROM tblEvents WHERE event_id = $event_id"; // Ensure the correct table is used
    $result = mysqli_query($conn, $sql);
    $event = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $event ? 'Edit Event' : 'Add Event'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .container {
            margin-top: 40px;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container label {
            font-weight: bold;
        }
        .form-container input, .form-container textarea {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .form-container input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2><?php echo $event ? 'Edit Event' : 'Add Event'; ?></h2>
        <form method="post" action="">
            <?php if ($event): ?>
                <input type="hidden" name="event_id" value="<?php echo isset($event['event_id']) ? htmlspecialchars($event['event_id']) : ''; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event['event_date'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="event_time">Event Time</label>
                <input type="time" id="event_time" name="event_time" value="<?php echo htmlspecialchars($event['event_time'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($event['description'] ?? ''); ?></textarea>
            </div>

            <input type="submit" name="btnEvent" value="<?php echo $event ? 'Update Event' : 'Add Event'; ?>" class="btn btn-primary btn-block">
        </form>
    </div>
</div>

<!-- Bootstrap JS & dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
