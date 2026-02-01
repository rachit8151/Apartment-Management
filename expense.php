<?php
session_start();
require 'dbFile/database.php';

/* ======================
   AUTH CHECK
   ====================== */
if (empty($_SESSION['username']) || empty($_SESSION['user_id'])) {
    exit("Unauthorized");
}

$user_id = (int)$_SESSION['user_id'];

/* ======================
   USER ROLE
   ====================== */
$roleRes = mysqli_query($conn, "SELECT user_role FROM tblUser WHERE user_id=$user_id");
$user_role = mysqli_fetch_assoc($roleRes)['user_role'] ?? '';

/* ======================
   SUMMARY TOTALS
   ====================== */
$total_maintenance = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(amount) total FROM tblMaintenance")
)['total'] ?? 0;

$total_hall_booking = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(payment_amount) total FROM tblHallBooking")
)['total'] ?? 0;

$total_amount = $total_maintenance + $total_hall_booking;

$total_expenses = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(amount) total FROM tblExpenses")
)['total'] ?? 0;

$remaining_amount = $total_amount - $total_expenses;

/* ======================
   FILTER & PAGINATION
   ====================== */
$limit = 5;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$selected_month = $_GET['month'] ?? date('Y-m');

/* ======================
   EXPENSE LIST
   ====================== */
$listSql = "
    SELECT name, amount, date
    FROM tblExpenses
    WHERE DATE_FORMAT(date,'%Y-%m') = '$selected_month'
    ORDER BY date DESC
    LIMIT $limit OFFSET $offset
";
$listRes = mysqli_query($conn, $listSql);

$countSql = "
    SELECT COUNT(*) total
    FROM tblExpenses
    WHERE DATE_FORMAT(date,'%Y-%m') = '$selected_month'
";
$total_rows = mysqli_fetch_assoc(mysqli_query($conn, $countSql))['total'] ?? 0;
$total_pages = ceil($total_rows / $limit);
?>

<div class="content-card">

    <h3 class="text-center mb-4">Expenses Overview</h3>

    <!-- ===== SUMMARY ===== -->
    <div class="row mb-4">
        <div class="col-md-6">
            <p>Total Maintenance Expenses: ₹<?= number_format($total_maintenance,2) ?></p>
            <p>Total Hall Booking Expenses: ₹<?= number_format($total_hall_booking,2) ?></p>
            <p><b>Total Amount:</b> ₹<?= number_format($total_amount,2) ?></p>
        </div>
        <div class="col-md-6">
            <p>Total Additional Expenses: ₹<?= number_format($total_expenses,2) ?></p>
            <p><b>Remaining Amount:</b> ₹<?= number_format($remaining_amount,2) ?></p>
        </div>
    </div>

    <!-- ===== FILTER ===== -->
    <div class="d-flex align-items-end gap-2 mb-3">
        <div>
            <label>Select Month</label>
            <input type="month" id="monthFilter"
                   class="form-control"
                   value="<?= htmlspecialchars($selected_month) ?>">
        </div>
        <button class="btn btn-primary"
                onclick="filterExpenses()">Filter</button>
    </div>

    <!-- ===== ADD BUTTON ===== -->
    <?php if ($user_role !== 'owner'): ?>
        <div class="mb-3">
            <button class="btn btn-success"
                    onclick="loadPage('expenseApp.php')">
                + Add Expense
            </button>
        </div>
    <?php endif; ?>

    <!-- ===== TABLE ===== -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>Expense Name</th>
                    <th>Amount (₹)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody class="text-center">

            <?php if ($total_rows > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($listRes)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= number_format($row['amount'],2) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-muted">
                        No expenses found for selected month
                    </td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>

    <!-- ===== PAGINATION ===== -->
    <?php if ($total_pages > 1): ?>
        <div class="pagination justify-content-center">
            <?php for ($i=1; $i<=$total_pages; $i++): ?>
                <a href="javascript:void(0)"
                   onclick="loadPage('expense.php?month=<?= $selected_month ?>&page=<?= $i ?>')"
                   class="<?= ($i==$page) ? 'active' : '' ?>">
                   <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</div>

<!-- ===== JS ===== -->
<script>
function filterExpenses() {
    const month = document.getElementById("monthFilter").value;
    loadPage("expense.php?month=" + month);
}
</script>
