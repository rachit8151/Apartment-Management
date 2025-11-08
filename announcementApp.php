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

// Handle announcement addition or update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['btnNotice'])) {
        if (isset($_POST['announcement_id'])) {
            // Update existing announcement
            getEdit($conn);
        } else {
            // Add new announcement
            getAdd($conn);
        }
    }
}

// Function to add an announcement
function getAdd($conn) {
    $date = $_POST['date'];

    // Validate the date
    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        echo '<script>alert("You cannot apply a previous date. Please choose a valid date."); window.history.back();</script>';
        return;
    }

    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO tblAnnouncement (type, title, description, date, user_id) 
            VALUES ('$type', '$title', '$description', '$date', $user_id)";
    
    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Announcement added successfully."); window.location.href="dashboard.php";</script>';
    } else {
        echo '<script>alert("Something went wrong with the announcement.");</script>';
    }
}

// Function to edit an announcement
function getEdit($conn) {
    $announcement_id = (int)$_POST['announcement_id']; // Cast to int for safety
    $date = $_POST['date'];

    // Validate the date
    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        echo '<script>alert("You cannot apply a previous date. Please choose a valid date."); window.history.back();</script>';
        return;
    }

    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "UPDATE tblAnnouncement SET type='$type', title='$title', description='$description', date='$date' WHERE announcement_id=$announcement_id";
    
    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Announcement updated successfully."); window.location.href="dashboard.php";</script>';
    } else {
        echo '<script>alert("Something went wrong with the update.");</script>';
    }
}

// Check if an announcement ID is set for editing
$announcement = null;
if (isset($_GET['announcement_id'])) {
    $announcement_id = (int)$_GET['announcement_id'];
    $sql = "SELECT * FROM tblAnnouncement WHERE announcement_id = $announcement_id";
    $result = mysqli_query($conn, $sql);
    $announcement = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $announcement ? 'Edit Announcement' : 'Add Announcement'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4"><?php echo $announcement ? 'Edit Announcement' : 'Add Announcement'; ?></h2>
        
        <form method="post">
            <?php if ($announcement): ?>
                <input type="hidden" name="announcement_id" value="<?php echo $announcement['announcement_id']; ?>">
            <?php endif; ?>

            <!-- Type of Announcement -->
            <div class="form-group">
                <label for="type" class="form-label">Type:</label>
                <select id="type" name="type" class="form-control" required>
                    <option value="">-select-</option>
                    <option value="meeting" <?php echo $announcement && $announcement['type'] === 'meeting' ? 'selected' : ''; ?>>Meeting</option>
                    <option value="event" <?php echo $announcement && $announcement['type'] === 'event' ? 'selected' : ''; ?>>Event</option>
                    <option value="notice" <?php echo $announcement && $announcement['type'] === 'notice' ? 'selected' : ''; ?>>Notice</option>
                    <option value="message" <?php echo $announcement && $announcement['type'] === 'message' ? 'selected' : ''; ?>>Message</option>
                </select>
            </div>

            <!-- Title of Announcement -->
            <div class="form-group">
                <label for="title" class="form-label">Title:</label>
                <input type="text" id="title" name="title" class="form-control" pattern="^[a-zA-Z ]*$" title="Only Characters" value="<?php echo $announcement['title'] ?? ''; ?>" required>
            </div>

            <!-- Description of Announcement -->
            <div class="form-group">
                <label for="description" class="form-label">Description:</label>
                <textarea id="description" name="description" class="form-control" rows="4" required><?php echo $announcement['description'] ?? ''; ?></textarea>
            </div>

            <!-- Date of Announcement -->
            <div class="form-group">
                <label for="date" class="form-label">Date:</label>
                <input type="date" id="date" name="date" class="form-control" value="<?php echo $announcement['date'] ?? ''; ?>" required>
            </div>

            <!-- Submit Button -->
            <div class="form-group text-center">
                <input type="submit" name="btnNotice" class="btn btn-custom" value="<?php echo $announcement ? 'Update Announcement' : 'Add Announcement'; ?>">
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
