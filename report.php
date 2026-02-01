<?php
session_start();
require 'dbFile/database.php';

/* ======================
   AUTH CHECK
   ====================== */
if (empty($_SESSION['username'])) {
    exit("Unauthorized");
}

/* ======================
   YEAR FILTER (AJAX SAFE)
   ====================== */
$report_year = isset($_GET['year'])
    ? (int)$_GET['year']
    : date("Y");

/* ======================
   USERS
   ====================== */
$user_result = $conn->query("
    SELECT username, email, user_role, name, contact
    FROM tblUser
    WHERE YEAR(created_at) = $report_year
");

/* ======================
   ANNOUNCEMENTS
   ====================== */
$total_announcements = $conn->query("
    SELECT COUNT(*) total
    FROM tblAnnouncement
    WHERE YEAR(date) = $report_year
")->fetch_assoc()['total'] ?? 0;

/* ======================
   MAINTENANCE
   ====================== */
$maintenance = $conn->query("
    SELECT
        SUM(amount) total_amount,
        SUM(penalty) total_penalty
    FROM tblMaintenance
    WHERE year = $report_year
")->fetch_assoc();

$total_amount  = (int)($maintenance['total_amount'] ?? 0);
$total_penalty = (int)($maintenance['total_penalty'] ?? 0);

/* ======================
   EVENTS
   ====================== */
$event_result = $conn->query("
    SELECT event_date, COUNT(*) total_events
    FROM tblEvents
    WHERE YEAR(event_date) = $report_year
    GROUP BY event_date
");

/* ======================
   HALL BOOKINGS
   ====================== */
$booking_result = $conn->query("
    SELECT hall_name,
           COUNT(*) total_bookings,
           SUM(payment_amount) total_revenue
    FROM tblHallBooking
    WHERE YEAR(booking_date) = $report_year
    GROUP BY hall_name
");

$total_revenue = $conn->query("
    SELECT SUM(payment_amount) total
    FROM tblHallBooking
    WHERE YEAR(booking_date) = $report_year
")->fetch_assoc()['total'] ?? 0;

/* ======================
   EXPENSES
   ====================== */
$total_expenses = $conn->query("
    SELECT SUM(amount) total
    FROM tblExpenses
    WHERE YEAR(date) = $report_year
")->fetch_assoc()['total'] ?? 0;

/* ======================
   FINAL CALCULATION
   ====================== */
$total_costs = ($total_amount + $total_revenue) - $total_expenses;
?>

<div class="content-card">

    <h3 class="text-center mb-4">📊 Yearly Report (<?= $report_year ?>)</h3>

    <!-- ===== FILTER ===== -->
    <div class="d-flex justify-content-center mb-4">
        <input type="number"
               id="reportYear"
               class="form-control w-25"
               value="<?= $report_year ?>"
               min="2000"
               max="<?= date('Y') ?>">
        <button class="btn btn-primary ms-2"
                onclick="loadPage('report.php?year=' + document.getElementById('reportYear').value)">
            Generate
        </button>
    </div>

    <!-- ===== USERS ===== -->
    <h5>User Overview</h5>
    <table class="table table-bordered table-striped">
        <thead class="table-dark text-center">
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Name</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody class="text-center">
        <?php while ($u = $user_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['user_role']) ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['contact']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- ===== SUMMARY ===== -->
    <h5>Summary</h5>
    <ul>
        <li>Total Announcements: <b><?= $total_announcements ?></b></li>
        <li>Total Maintenance Amount: <b>₹<?= $total_amount ?></b></li>
        <li>Total Maintenance Penalty: <b>₹<?= $total_penalty ?></b></li>
        <li>Total Hall Revenue: <b>₹<?= $total_revenue ?></b></li>
        <li>Total Expenses: <b>₹<?= $total_expenses ?></b></li>
        <li><b>Total Cost Balance: ₹<?= $total_costs ?></b></li>
    </ul>

    <!-- ===== EVENTS ===== -->
    <h5>Events</h5>
    <table class="table table-bordered">
        <thead class="table-dark text-center">
            <tr>
                <th>Date</th>
                <th>Total Events</th>
            </tr>
        </thead>
        <tbody class="text-center">
        <?php while ($e = $event_result->fetch_assoc()): ?>
            <tr>
                <td><?= $e['event_date'] ?></td>
                <td><?= $e['total_events'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- ===== HALL BOOKINGS ===== -->
    <h5>Hall Bookings</h5>
    <table class="table table-bordered">
        <thead class="table-dark text-center">
            <tr>
                <th>Hall</th>
                <th>Total Bookings</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody class="text-center">
        <?php while ($b = $booking_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($b['hall_name']) ?></td>
                <td><?= $b['total_bookings'] ?></td>
                <td>₹<?= $b['total_revenue'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- ===== PRINT ===== -->
    <div class="text-center mt-4">
        <button class="btn btn-secondary" onclick="window.print()">🖨 Print</button>
    </div>

</div>
