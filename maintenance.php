<?php 
session_start();
require 'dbFile/database.php';

$user_id = $_SESSION['user_id'];

// Check if user is logged in
if (empty($_SESSION['username']) || empty($_SESSION['password'])) {
    header("location: login.php");
    exit();
}

// Validate user_id
if (!isset($user_id) || !is_numeric($user_id)) {
    header("location: login.php");
    exit();
}

// Retrieve user role securely
$sqlUser = "SELECT user_role FROM tblUser WHERE user_id = '$user_id'";
$resultUser = mysqli_query($conn, $sqlUser);

if ($resultUser) {
    $userData = mysqli_fetch_assoc($resultUser);
    $user_role = $userData['user_role'];
} else {
    echo "Error retrieving user role.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Maintenance</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for page styling -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-title {
            text-align: center;
            margin-top: 30px;
            font-size: 2rem;
            font-weight: bold;
        }
        .maintenance-container {
            text-align: right;
            margin-bottom: 20px;
        }
        .maintenance-container form input[type="submit"] {
            width: 150px;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
        }
        .maintenance-table {
            margin-top: 30px;
        }
        .maintenance-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .maintenance-table th, .maintenance-table td {
            padding: 10px;
            text-align: center;
        }
        .maintenance-table th {
            
            color: white;
        }
        .action-buttons a {
            margin-right: 10px;
        }
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-title">
        <h2>Maintenance Management</h2>
    </div>

    <?php
    // Hide the form for selecting the year for certain roles
    if ($user_role !== 'committee member' && $user_role !== 'secretary' && $user_role !== 'owner') :
    ?>
        <!-- Form for selecting the year -->
        <form method="post" action="" class="form-inline justify-content-center">
            <label for="apply_year" class="mr-2">Enter Year:</label>
            <input type="number" id="apply_year" name="apply_year" 
                   class="form-control" 
                   value="<?php echo isset($_POST['apply_year']) ? $_POST['apply_year'] : date('Y'); ?>" 
                   min="2000" max="<?php echo date('Y'); ?>" required>
            <input type="submit" value="History" class="btn btn-primary ml-2">
        </form>
    <?php endif; ?>

    <?php
    // Get the year entered in the form, default to current year if not provided
    $apply_year = isset($_POST['apply_year']) ? intval($_POST['apply_year']) : date("Y");

    // If the user is an admin, retrieve maintenance with payment details
    if ($user_role === 'admin') {
        $sql = "SELECT m.maintenance_id, m.year, m.amount, m.penalty, m.description, 
                       p.user_id, p.payment_status, u.name 
                FROM tblPayment p 
                JOIN tblMaintenance m ON p.maintenance_id = m.maintenance_id
                JOIN tblUser u ON p.user_id = u.user_id
                WHERE YEAR(p.created_at) = $apply_year";  
        $result = mysqli_query($conn, $sql);
    } else {
        $sqlpay = "SELECT payment_status FROM tblPayment WHERE user_id = $user_id";
        $res = mysqli_query($conn, $sqlpay);
        $pay = mysqli_fetch_assoc($res);
        // Normal maintenance details for non-admin users
        $sql = "SELECT * FROM tblMaintenance WHERE year = $apply_year ORDER BY year DESC;";
        $result = mysqli_query($conn, $sql);
    }
    ?>

    <div class="maintenance-container">
        <?php if ($user_role !== 'owner' && $user_role !== 'admin') : ?>
            <form method="post">
                <input type="submit" name="btnAddMaintenance" value="Add Maintenance" formaction="maintenanceApp.php" class="btn btn-success">
            </form>
        <?php endif; ?>
    </div>

    <div class="maintenance-table">
        <?php
        // Output the maintenance table
        if ($result) {
            echo "<table class='table table-bordered table-striped table-hover'>";
            echo "<thead class='thead-dark'>
                    <tr>
                        <th>Maintenance Id</th>
                        <th>Year</th>
                        <th>Amount</th>
                        <th>Penalty</th>
                        <th>Description</th>";
            // Display payment-related columns for admin only
            if ($user_role === 'admin') {
                echo "<th>Name</th><th>Payment Status</th>";
            }
            // Display action column based on user role
            if ($user_role !== 'admin') {
                echo "<th>Action</th>";
            }
            echo "</tr></thead><tbody>";

            while ($q = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($q['maintenance_id']) . "</td>";
                echo "<td>" . htmlspecialchars($q['year']) . "</td>";
                echo "<td>" . htmlspecialchars($q['amount']) . "</td>";
                echo "<td>" . htmlspecialchars($q['penalty']) . "</td>";
                echo "<td>" . htmlspecialchars($q['description']) . "</td>";

                if ($user_role === 'admin') {
                    echo "<td>" . htmlspecialchars($q['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($q['payment_status']) . "</td>";
                }

                // Display action buttons based on user role (only for non-admins)
                if ($user_role !== 'admin') {
                    echo "<td class='action-buttons'>";
                    if ($user_role !== 'owner') {
                        echo "<a href='maintenanceApp.php?maintenance_id=" . htmlspecialchars($q['maintenance_id']) . "' class='btn btn-warning btn-sm'>Edit</a>";
                    }
                    if ($pay['payment_status'] !== 'Paid') {
                        echo "<a href='pay.php?maintenance_id=" . htmlspecialchars($q['maintenance_id']) . "' class='btn btn-success btn-sm'>Pay</a>";
                    } else {
                        echo "<span class='badge badge-success'>Paid</span>";
                    }
                    echo "</td>";
                }
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-warning'>No maintenance records found for the selected year.</div>";
        }
        ?>
    </div>
</div>

<!-- Bootstrap JS & dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
