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

// Handle form submissions for Add/Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btnHall'])) {
        if (isset($_POST['hall_id'])) {
            // Update existing hall
            getEdit($conn);
        } else {
            // Add new hall
            getAdd($conn);
        }
    }
}

// Function to add a hall
function getAdd($conn) {
    $hall_name = mysqli_real_escape_string($conn, $_POST['hall_name']);
    $capacity = (int)$_POST['capacity'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $amenities = mysqli_real_escape_string($conn, $_POST['amenities']);

    $sql = "INSERT INTO tblHalls (hall_name, capacity, location, amenities) VALUES ('$hall_name', $capacity, '$location', '$amenities')";
    
    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Hall added successfully."); window.location.href="dashboard.php";</script>';
    } else {
        echo '<script>alert("Something went wrong with adding the hall.");</script>';
    }
}

// Function to edit a hall
function getEdit($conn) {
    $hall_id = (int)$_POST['hall_id']; // Cast to int for safety
    $hall_name = mysqli_real_escape_string($conn, $_POST['hall_name']);
    $capacity = (int)$_POST['capacity'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $amenities = mysqli_real_escape_string($conn, $_POST['amenities']);

    $sql = "UPDATE tblHalls SET hall_name='$hall_name', capacity=$capacity, location='$location', amenities='$amenities' WHERE hall_id=$hall_id";
    
    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Hall updated successfully."); window.location.href="dashboard.php";</script>';
    } else {
        echo '<script>alert("Something went wrong with the update.");</script>';
    }
}

// Check if a hall ID is set for editing
$hall = null;
if (isset($_GET['hall_id'])) {
    $hall_id = (int)$_GET['hall_id'];
    $sql = "SELECT * FROM tblHalls WHERE hall_id = $hall_id";
    $result = mysqli_query($conn, $sql);
    $hall = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $hall ? 'Edit Hall' : 'Add Hall'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .container {
            margin-top: 40px;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            padding: 10px;
        }
        textarea.form-control {
            min-height: 150px;
        }
        .btn-custom {
            width: 100%;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Halls</h1>
        <h2><?php echo $hall ? 'Edit Hall' : 'Add Hall'; ?></h2>
        
        <form method="post" action="">
            <?php if ($hall): ?>
                <input type="hidden" name="hall_id" value="<?php echo htmlspecialchars($hall['hall_id']); ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="hall_name">Hall Name</label>
                <input type="text" class="form-control" id="hall_name" name="hall_name" value="<?php echo htmlspecialchars($hall['hall_name'] ?? ''); ?>" placeholder="Enter Hall Name" required>
            </div>

            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo htmlspecialchars($hall['capacity'] ?? ''); ?>" placeholder="Enter Capacity" required>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($hall['location'] ?? ''); ?>" placeholder="Enter Location" required>
            </div>

            <div class="form-group">
                <label for="amenities">Amenities</label>
                <textarea class="form-control" id="amenities" name="amenities" placeholder="Enter Amenities" required><?php echo htmlspecialchars($hall['amenities'] ?? ''); ?></textarea>
            </div>

            <button type="submit" name="btnHall" class="btn btn-primary btn-custom">
                <?php echo $hall ? 'Update Hall' : 'Add Hall'; ?>
            </button>
        </form>

        <!-- Optional success/error message -->
        <?php if (isset($message)) : ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS & dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
