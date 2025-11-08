<?php
session_start();
require 'dbFile/database.php';

// Set the year for the report based on user input
$report_year = isset($_POST['report_year']) ? intval($_POST['report_year']) : date("Y");
// Fetch users
$user_query = "SELECT username, email, user_role, name, contact FROM tblUser WHERE YEAR(created_at) = $report_year";
$user_result = $conn->query($user_query);
// Fetch total announcements
$announcement_query = "SELECT COUNT(*) AS total_announcements FROM tblAnnouncement WHERE YEAR(date) = $report_year";
$announcement_result = $conn->query($announcement_query);
$total_announcements = $announcement_result->fetch_assoc()['total_announcements'] ?? 0;
// Fetch maintenance costs
$maintenance_query = "SELECT SUM(amount) AS total_amount, SUM(penalty) AS total_penalty FROM tblmaintenance WHERE year = $report_year";
$maintenance_result = $conn->query($maintenance_query);
$maintenance_data = $maintenance_result->fetch_assoc();
$total_amount = $maintenance_data['total_amount'] ?? 0;
$total_penalty = $maintenance_data['total_penalty'] ?? 0;
// Fetch events
$event_query = "SELECT COUNT(*) AS total_events, event_date FROM tblEvents WHERE YEAR(event_date) = $report_year GROUP BY event_date";
$event_result = $conn->query($event_query);
// Fetch hall bookings
$booking_query = "SELECT hall_name, COUNT(*) AS total_bookings, SUM(payment_amount) AS total_revenue FROM tblHallBooking WHERE YEAR(booking_date) = $report_year GROUP BY hall_name";
$booking_result = $conn->query($booking_query);
// Fetch total revenue
$total_revenue_query = "SELECT SUM(payment_amount) AS total_revenue FROM tblHallBooking WHERE YEAR(booking_date) = $report_year";
$total_revenue_result = $conn->query($total_revenue_query);
$total_revenue = $total_revenue_result->fetch_assoc()['total_revenue'] ?? 0;
// Fetch total expenses
$total_expenses_query = "SELECT SUM(amount) AS total_expenses FROM tblExpenses WHERE YEAR(date) = $report_year";
$total_expenses_result = $conn->query($total_expenses_query);
$total_expenses = (int) ($total_expenses_result->fetch_assoc()['total_expenses'] ?? 0); // Cast to int
// Calculate total costs
$total_costs = $total_amount + $total_revenue - $total_expenses;
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearly Report</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            
            font-family: Arial, sans-serif;
        }

        h1, h2 {
            color: #007bff;
        }

        table {
            margin-top: 20px;
            border: 1px solid #ddd;
            background-color: #fff;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            margin-top: 20px;
        }

        .button {
            margin-top: 20px;
        }

        @media print {
            .button, input[type="submit"] {
                display: none;
            }
        }

    </style>

</head>
<body>

    <div class="containerRp">
        <h1 class="text-center">Yearly Report</h1>

        <!-- Year Input Form -->
        <form method="post" action="" class="form-inline justify-content-center">
            <div class="form-group">
                <label for="report_year" class="mr-2">Enter Year:</label>
                <input type="number" id="report_year" name="report_year" class="form-control" value="<?php echo $report_year; ?>" min="2000" max="<?php echo date("Y"); ?>" required>
                <input type="submit" value="Generate Report" class="btn btn-primary ml-2">
            </div>
        </form>

        <!-- User Overview -->
        <h2>User Overview for <?php echo $report_year; ?></h2>
        <table class="table table-bordered table-striped">
            <thead class='thead-dark'>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>User Role</th>
                    <th>Name</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $user_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['user_role']); ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['contact']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Announcements -->
        <h2>Total Announcements</h2>
        <p>Total Announcements: <strong><?php echo $total_announcements; ?></strong></p>

        <!-- Maintenance Costs -->
        <h2>Total Maintenance Costs</h2>
        <p>Total Amount: <strong><?php echo $total_amount; ?></strong></p>
        <p>Total Penalty: <strong><?php echo $total_penalty; ?></strong></p>

        <!-- Events Held -->
        <h2>Events Held</h2>
        <table class="table table-bordered table-striped">
            <thead class='thead-dark'>
                <tr>
                    <th>Total Events</th>
                    <th>Event Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($event = $event_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $event['total_events']; ?></td>
                        <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Hall Bookings -->
        <h2>Hall Bookings</h2>
        <table class="table table-bordered table-striped">
            <thead class='thead-dark'>
                <tr>
                    <th>Hall Name</th>
                    <th>Total Bookings</th>
                    <th>Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($booking = $booking_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['hall_name']); ?></td>
                        <td><?php echo $booking['total_bookings']; ?></td>
                        <td><?php echo $booking['total_revenue']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p>Total Revenue: <strong><?php echo $total_revenue; ?></strong></p>

        <!-- Total Costs Calculation -->
        <h2>Total Costs Calculation</h2>
        <p>Total Maintenance Amount: <strong><?php echo $total_amount; ?></strong></p>
        <p>Total Hall Booking Revenue: <strong><?php echo $total_revenue; ?></strong></p>
        <p>Total Expenses: <strong><?php echo $total_expenses; ?></strong></p>
        <p><strong>Total Costs: <?php echo $total_costs; ?></strong></p>

        <!-- Print Button -->
        <div class="button text-center">
            <button class="btn btn-secondary" onclick="window.print()">Print Result</button>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
