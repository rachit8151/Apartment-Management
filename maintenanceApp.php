<?php 
session_start();
require 'dbFile/database.php';

// Check if user is logged in
if (empty($_SESSION['username']) || empty($_SESSION['password'])) {
    header("location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Get user details including user_id
$sqlUser = "SELECT user_id, user_role FROM tblUser WHERE username = '$username'";
$resultUser = mysqli_query($conn, $sqlUser);
$userData = mysqli_fetch_assoc($resultUser);
$user_id = $userData['user_id'];

// Function to get maintenance by ID
function getMaintenanceById($maintenance_id) {
    global $conn;
    $result = $conn->query("SELECT * FROM tblMaintenance WHERE maintenance_id = $maintenance_id");
    return $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['btnNotice'])) {
        if (isset($_POST['maintenance_id'])) {
            // Update existing maintenance
            getEdit($conn);
        } else {
            // Add new maintenance
            getAdd($conn);
        }
    }
}

// Function to add maintenance
function getAdd() {
    global $conn, $user_id;

    $year = $_POST['year'];
    $amount = 8000;  // Fixed amount
    $penalty = $_POST['penalty'];
    $description = $_POST['description'];

    $sql = "INSERT INTO tblMaintenance (year, amount, penalty, description) 
            VALUES ('$year', '$amount', '$penalty', '$description')";

    if (mysqli_query($conn, $sql)) {
        $maintenance_id = mysqli_insert_id($conn);
        $sqlInsertPayments = "
        INSERT INTO tblPayment (maintenance_id, user_id, payment_status)
        SELECT $maintenance_id, user_id, 'Pending'
        FROM tblUser
        WHERE user_role != 'admin'
    ";
        mysqli_query($conn, $sqlInsertPayments);
        echo '<script>alert("Maintenance added successfully."); window.location.href="dashboard.php";</script>';
    } else {
        echo '<script>alert("Something went wrong with the addition.");</script>';
    }
}

// Function to update maintenance details
function getEdit() {
    global $conn;

    $maintenance_id = intval($_POST['maintenance_id']);
    $year = intval($_POST['year']);
    $penalty = intval($_POST['penalty']);
    $description = $_POST['description'];
    $amount = 21500;

    $sql = "UPDATE tblMaintenance SET year = $year, amount = $amount, penalty = $penalty, description = '$description'
            WHERE maintenance_id = $maintenance_id";

    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Maintenance updated successfully."); window.location.href="dashboard.php";</script>';
    } else {
        echo '<script>alert("Something went wrong with the update.");</script>';
    }
}

$maintenance = null;
if (isset($_GET['maintenance_id'])) {
    $maintenance_id = intval($_GET['maintenance_id']);
    $maintenance = getMaintenanceById($maintenance_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $maintenance ? 'Edit Maintenance' : 'Add Maintenance'; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for additional styling -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 30px;
        }
        .form-label {
            font-weight: bold;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .form-container {
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2 class="text-center"><?php echo $maintenance ? 'Edit Maintenance' : 'Add Maintenance'; ?></h2>
        
        <form method="post" action="">
            <?php if ($maintenance): ?>
                <input type="hidden" name="maintenance_id" value="<?php echo htmlspecialchars($maintenance['maintenance_id']); ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="year" class="form-label">Year:</label>
                <input type="number" name="year" class="form-control" value="<?php echo htmlspecialchars($maintenance['year'] ?? date('Y')); ?>" required>
            </div>

            <div class="form-group">
                <label for="amount" class="form-label">Amount:</label>
                <input type="number" name="amount" class="form-control" value="21500" readonly>
            </div>

            <div class="form-group">
                <label for="penalty" class="form-label">Penalty:</label>
                <input type="number" name="penalty" class="form-control" value="<?php echo htmlspecialchars($maintenance['penalty'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description:</label>
                <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($maintenance['description'] ?? ''); ?></textarea>
            </div>

            <button type="submit" name="btnNotice" class="btn btn-primary"><?php echo $maintenance ? 'Update Maintenance' : 'Add Maintenance'; ?></button>
        </form>
    </div>
</div>

<!-- Bootstrap JS & dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
