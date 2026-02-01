<?php
session_start();
require 'dbFile/database.php';

if (empty($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

if (isset($_POST['btnLogout'])) {
    session_unset();
    session_destroy();
    header("location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="css/dashboard-modern.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <!-- ===== TOP BAR ===== -->
    <div class="topbar">
        <div class="logo">Apartment Management</div>

        <div class="topbar-right">
            <span class="welcome-text">
                Welcome <?= $_SESSION['username'] ?>
            </span>

            <form method="post" class="topbar-actions">
                <button formaction="passwordChange.php" class="btn btn-sm btn-light">
                    Change Password
                </button>
                <button name="btnLogout" class="btn btn-sm btn-danger">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- ===== SIDEBAR ===== -->
    <div class="sidebar">
        <ul>
            <li><button data-page="announcement.php"><i class="fas fa-bullhorn"></i> Announcement</button></li>
            <li><button data-page="maintenance.php"><i class="fas fa-tools"></i> Maintenance</button></li>
            <li><button data-page="expense.php"><i class="fas fa-wallet"></i> Expense</button></li>
            <li><button data-page="hallBooking.php"><i class="fas fa-calendar"></i> Hall Booking</button></li>
            <li><button data-page="hall.php"><i class="fas fa-building"></i> Hall</button></li>
            <li><button data-page="event.php"><i class="fas fa-calendar-alt"></i> Event</button></li>
            <li><button data-page="userManage.php"><i class="fas fa-users-cog"></i> Manage User</button></li>
            <li><button data-page="viewUsers.php"><i class="fas fa-users"></i> View User</button></li>
            <li><button data-page="report.php"><i class="fas fa-chart-bar"></i> Report</button></li>
            <li><button data-page="complaint.php"><i class="fas fa-exclamation-circle"></i> Complaint</button></li>
        </ul>
    </div>
    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content" id="mainContent">

        <!-- PAGE CONTENT -->
        <div id="pageContainer">

            <!-- NOTIFICATION CARD -->
            <div class="content-card mb-4">
                <?php include 'notification.php'; ?>
            </div>

            <!-- GRAPH CARD -->
            <div class="content-card">
                <?php include 'graph.php'; ?>
            </div>

        </div>
    </div>


    <!-- ✅ ONLY ONE JS FILE -->
    <script src="js/dashboard.js"></script>

</body>

</html>