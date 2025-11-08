<?php
session_start();
require 'dbFile/database.php';

// Check if the user is logged in
if (empty($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

// Logout functionality
if (isset($_POST['btnLogout'])) {
    session_unset();
    session_destroy();
    header("location: login.php");
    exit();
}

// Fetch total expenses from maintenance table
$maintenance_sql = "
    SELECT SUM(t1.amount) AS total
    FROM tblMaintenance t1
    JOIN tblPayment t2 ON t1.maintenance_id = t2.maintenance_id
    WHERE t2.payment_status = 'Paid'
";
$maintenance_result = mysqli_query($conn, $maintenance_sql);
$maintenance_row = mysqli_fetch_assoc($maintenance_result);
$total_maintenance = $maintenance_row['total'] ? $maintenance_row['total'] : 0;

// Fetch total expenses from hall_booking table
$hall_booking_sql = "SELECT SUM(payment_amount) AS total FROM tblHallBooking";
$hall_booking_result = mysqli_query($conn, $hall_booking_sql);
$hall_booking_row = mysqli_fetch_assoc($hall_booking_result);
$total_hall_booking = $hall_booking_row['total'] ? $hall_booking_row['total'] : 0;

// Fetch total additional expenses
$user_id = $_SESSION['user_id'];  // Assuming the user_id is stored in session
$expenses_sql = "SELECT SUM(amount) AS total FROM tblExpenses";
$expenses_result = mysqli_query($conn, $expenses_sql);
$expenses_row = mysqli_fetch_assoc($expenses_result);
$total_expenses = $expenses_row['total'] ? $expenses_row['total'] : 0;

// Calculate total amount
$total_amount = $total_maintenance + $total_hall_booking + $total_expenses;

// Calculate percentages for each category
$percent_maintenance = ($total_amount > 0) ? ($total_maintenance / $total_amount) * 100 : 0;
$percent_hall_booking = ($total_amount > 0) ? ($total_hall_booking / $total_amount) * 100 : 0;
$percent_expenses = ($total_amount > 0) ? ($total_expenses / $total_amount) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Panel</title>
        <link rel="stylesheet" href="css/style.css">
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>  <!-- Chart.js Library -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const toggleBtn = document.getElementById('toggle-btn');
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('main-content');

                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('expanded');
                    mainContent.classList.toggle('expanded');
                });

                // Function to load content via AJAX
                function loadForm(page) {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (this.readyState === 4) {
                            if (this.status === 200) {
                                mainContent.innerHTML = this.responseText;
                            } else {
                                mainContent.innerHTML = '<p>Error loading content.</p>';
                            }
                        }
                    };
                    xhr.open('GET', page, true);
                    xhr.send();
                }

                document.getElementById('loadProfile').addEventListener('click', function () {
                    loadForm('profile.php'); // Load the profile page content dynamically
                });
                document.getElementById('loadContact').addEventListener('click', function () {
                    loadForm('html/contact.html'); // Load the profile page content dynamically
                });
                document.getElementById('loadAbout').addEventListener('click', function () {
                    loadForm('html/aboutus.html'); // Load the profile page content dynamically
                });
                document.getElementById('loadService').addEventListener('click', function () {
                    loadForm('html/service.html'); // Load the profile page content dynamically
                });
                document.getElementById('loadAnn').addEventListener('click', function () {
                    loadForm('announcement.php');
                });
                document.getElementById('loadMain').addEventListener('click', function () {
                    loadForm('maintenance.php');
                });
                document.getElementById('loadExp').addEventListener('click', function () {
                    loadForm('expense.php');
                });
                document.getElementById('loadHallBook').addEventListener('click', function () {
                    loadForm('hallBooking.php');
                });
                document.getElementById('loadHall').addEventListener('click', function () {
                    loadForm('hall.php');
                });
                document.getElementById('loadEvent').addEventListener('click', function () {
                    loadForm('event.php');
                });
                document.getElementById('loadManageUser').addEventListener('click', function () {
                    loadForm('userManage.php');
                });
                document.getElementById('loadView').addEventListener('click', function () {
                    loadForm('viewUsers.php');
                });
                document.getElementById('loadReport').addEventListener('click', function () {
                    loadForm('report.php');
                });
                document.getElementById('loadComp').addEventListener('click', function () {
                    loadForm('complaint.php');
                });
            });
        </script>
    </head>
    <body>
        <?php include 'header.php'; ?>
         <?php// include 'notification.php'; ?>
        <div class="sidebar" id="sidebar">
            <ul>
                <li>
                    <button id="loadAnn" class="sidebar-btn">
                        <i class="fas fa-fw fa-table"></i> Announcement
                    </button>
                </li>
                <li>
                    <button id="loadMain" class="sidebar-btn">
                        <i class="fas fa-fw fa-table"></i> Maintenance
                    </button>
                </li>
                <li>
                    <button id="loadExp" class="sidebar-btn">
                        <i class="fas fa-fw fa-table"></i> Expense
                    </button>
                </li>
                <li>
                    <button id="loadHallBook" class="sidebar-btn">
                        <i class="fas fa-fw fa-table"></i> Hall Booking
                    </button>
                </li>
                <li>
                    <button id="loadHall" class="sidebar-btn">
                        <i class="fas fa-fw fa-table"></i> Hall
                    </button>
                </li>
                <li>
                    <button id="loadEvent" class="sidebar-btn">
                        <i class="fas fa-fw fa-table"></i> Event
                    </button>
                </li>
                <li><button id="loadManageUser" class="sidebar-btn">
                        <i class="fas fa-fw fa-tools"></i> Manage User
                    </button> 
                </li>
                <li><button id="loadView" class="sidebar-btn">
                        <i class="fas fa-fw fa-tools"></i> View User
                    </button>
                </li>
                <li><button id="loadReport" class="sidebar-btn">
                        <i class="fas fa-fw fa-tools"></i> Report
                    </button> 
                </li>
                <li>
                    <button id="loadComp" class="sidebar-btn">
                        <i class="fas fa-fw fa-table"></i> Complain
                    </button>
                </li>
            </ul>
        </div>

       
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Toggle sidebar options (e.g., logout, change password)
                document.getElementById('toggle-btn').addEventListener('click', function () {
                    var sidebarOptions = document.getElementById('sidebar-options');
                    sidebarOptions.style.display = (sidebarOptions.style.display === 'block') ? 'none' : 'block';
                });
            });
        </script>
        <div class="main-content" id="main-content">
            <button class="toggle-btn" id="toggle-btn">☰</button>

            <!-- Sidebar form will appear when the button is clicked -->
            <div id="sidebar-options" style="display:none;">
                <form method="post" class="sidebar-form">
                    <input type="submit" name="btnChangePassword" value="Change Password" formaction="passwordChange.php">
                    <input type="submit" name="btnLogout" value="Logout">
                </form>
            </div>
            <?php include 'notification.php'; ?>
            <?php include 'graph.php'; ?> 
        </div>
    </body>
</html>