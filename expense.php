<?php
session_start();
require 'dbFile/database.php'; // Include your database connection

// Check if the user is logged in
if (empty($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

// Fetch user role
$user_id = $_SESSION['user_id'];
$user_role_sql = "SELECT user_role FROM tblUser WHERE user_id = '$user_id'";
$user_role_result = mysqli_query($conn, $user_role_sql);
$user_role_row = mysqli_fetch_assoc($user_role_result);
$user_role = $user_role_row['user_role'] ?? '';

// Initialize variables
$total_maintenance = 0;
$total_hall_booking = 0;
$total_expenses = 0;
$total_amount = 0;
$remaining_amount = 0;

// Fetch total expenses from maintenance table
$maintenance_sql = "SELECT SUM(amount) AS total FROM tblMaintenance";
$maintenance_result = mysqli_query($conn, $maintenance_sql);
$total_maintenance = mysqli_fetch_assoc($maintenance_result)['total'] ?? 0;

// Fetch total expenses from hall_booking table
$hall_booking_sql = "SELECT SUM(payment_amount) AS total FROM tblHallBooking";
$hall_booking_result = mysqli_query($conn, $hall_booking_sql);
$total_hall_booking = mysqli_fetch_assoc($hall_booking_result)['total'] ?? 0;

// Calculate total amount
$total_amount = $total_maintenance + $total_hall_booking;

// Fetch total additional expenses
$total_expenses_sql = "SELECT SUM(amount) AS total FROM tblExpenses WHERE user_id = '$user_id'";
$total_expenses_result = mysqli_query($conn, $total_expenses_sql);
$total_expenses = mysqli_fetch_assoc($total_expenses_result)['total'] ?? 0;

// Calculate remaining amount
$remaining_amount = $total_amount - $total_expenses;

// Pagination
$limit = 5; // number of records per page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Month filter
$selected_month = isset($_POST['month']) ? $_POST['month'] : date('Y-m');

// Fetch added expenses based on the selected month with pagination
$expenses_list_sql = "SELECT name, amount, date FROM tblExpenses 
                       WHERE DATE_FORMAT(date, '%Y-%m') = '$selected_month' 
                       LIMIT $limit OFFSET $offset";
$expenses_list_result = mysqli_query($conn, $expenses_list_sql);

// Count total expenses for pagination
$total_expenses_count_sql = "SELECT COUNT(*) AS total FROM tblExpenses 
                              WHERE DATE_FORMAT(date, '%Y-%m') = '$selected_month'";
$total_expenses_count_result = mysqli_query($conn, $total_expenses_count_sql);
$total_expenses_count = mysqli_fetch_assoc($total_expenses_count_result)['total'] ?? 0;
$total_pages = ceil($total_expenses_count / $limit);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .containerEx {
            max-width: 900px;
            margin-top: 30px;
        }
        .expense-table th, .expense-table td {
            text-align: center;
            vertical-align: middle;
        }
        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: black;
        }
        .pagination .active {
            font-weight: bold;
            color: red;
            text-decoration: underline;
        }
        .pagination a:hover {
            text-decoration: underline;
        }
        .card {
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="containerEx">
    <div class="card">
        <h2 class="text-center mb-4">Expenses Overview</h2>
        <div class="row">
            <div class="col-md-6">
                <p>Total Maintenance Expenses: ₹<?php echo number_format($total_maintenance, 2); ?></p>
                <p>Total Hall Booking Expenses: ₹<?php echo number_format($total_hall_booking, 2); ?></p>
                <p>Total Amount (Maintenance + Hall Booking): ₹<?php echo number_format($total_amount, 2); ?></p>
            </div>
            <div class="col-md-6">
                <p>Total Additional Expenses: ₹<?php echo number_format($total_expenses, 2); ?></p>
                <p>Remaining Amount: ₹<?php echo number_format($remaining_amount, 2); ?></p>
            </div>
        </div>

        <h3 class="mt-5">Recorded Expenses</h3>
        <form method="POST" id="monthForm" class="mb-4">
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <label for="month" class="mr-2">Select Month:</label>
                    <input type="month" id="month" name="month" class="form-control" value="<?php echo htmlspecialchars($selected_month); ?>" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <div>
            <?php if ($total_expenses_count > 0): ?>
                <?php if ($user_role !== 'owner'): ?>
                    <a href="expenseApp.php" class="btn btn-success mb-3">Add Expense</a>
                <?php endif; ?>

                <table class="table table-striped expense-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Expense Name</th>
                            <th>Amount (₹)</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($expense = mysqli_fetch_assoc($expenses_list_result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($expense['name']); ?></td>
                                <td><?php echo number_format($expense['amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($expense['date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?month=<?php echo htmlspecialchars($selected_month); ?>&page=<?php echo $i; ?>" class="<?php echo ($i === $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                </div>

            <?php else: ?>
                <p>No expenses found for the selected month.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS & dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
        $('#month').change(function () {
            $('#monthForm').submit(); // Submit the form on month change
        });
    });
</script>

</body>
</html>
