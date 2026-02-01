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
   GET USER ROLE
   ====================== */
$userRes = mysqli_query($conn, "SELECT user_role FROM tblUser WHERE user_id = $user_id");
$userData = mysqli_fetch_assoc($userRes);
$user_role = $userData['user_role'] ?? '';

/* ======================
   YEAR FILTER
   ====================== */
$apply_year = isset($_GET['year']) ? (int)$_GET['year'] : date("Y");

/* ======================
   FETCH MAINTENANCE DATA
   ====================== */
if ($user_role === 'admin') {

    $sql = "
        SELECT m.maintenance_id, m.year, m.amount, m.penalty, m.description,
               u.name,
               IFNULL(p.payment_status,'Pending') AS payment_status
        FROM tblMaintenance m
        LEFT JOIN tblPayment p ON m.maintenance_id = p.maintenance_id
        LEFT JOIN tblUser u ON p.user_id = u.user_id
        WHERE m.year = $apply_year
        GROUP BY m.maintenance_id
        ORDER BY m.maintenance_id DESC
    ";

} else {

    $sql = "
        SELECT m.*,
               IFNULL(p.payment_status,'Pending') AS payment_status
        FROM tblMaintenance m
        LEFT JOIN tblPayment p
            ON m.maintenance_id = p.maintenance_id
            AND p.user_id = $user_id
        WHERE m.year = $apply_year
        ORDER BY m.maintenance_id DESC
    ";
}

$result = mysqli_query($conn, $sql);
?>

<div class="content-card">

    <h3 class="text-center mb-4">Maintenance Management</h3>

    <!-- YEAR FILTER -->
    <div class="d-flex justify-content-center mb-4">
        <label class="me-2 mt-2">Enter Year:</label>
        <input type="number" id="apply_year"
               class="form-control w-auto me-2"
               value="<?= $apply_year ?>">
        <button class="btn btn-primary"
                onclick="loadMaintenanceHistory()">History</button>
    </div>

    <!-- ADD BUTTON (Secretary / Committee) -->
    <?php if ($user_role !== 'owner' && $user_role !== 'admin'): ?>
        <div class="text-end mb-3">
            <button class="btn btn-success"
                    onclick="loadPage('maintenanceApp.php')">
                + Add Maintenance
            </button>
        </div>
    <?php endif; ?>

    <!-- TABLE -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Year</th>
                    <th>Amount</th>
                    <th>Penalty</th>
                    <th>Description</th>

                    <?php if ($user_role === 'admin'): ?>
                        <th>Name</th>
                        <th>Status</th>
                    <?php endif; ?>

                    <?php if ($user_role !== 'admin'): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody class="text-center">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['maintenance_id'] ?></td>
                        <td><?= $row['year'] ?></td>
                        <td>₹<?= number_format($row['amount'],2) ?></td>
                        <td>₹<?= number_format($row['penalty'],2) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>

                        <?php if ($user_role === 'admin'): ?>
                            <td><?= htmlspecialchars($row['name'] ?? '-') ?></td>
                            <td>
                                <span class="badge <?= $row['payment_status']=='Paid'?'bg-success':'bg-warning' ?>">
                                    <?= $row['payment_status'] ?>
                                </span>
                            </td>
                        <?php endif; ?>

                        <?php if ($user_role !== 'admin'): ?>
                            <td>

                                <!-- OWNER PAY BUTTON -->
                                <?php if ($user_role === 'owner'): ?>

                                    <?php if ($row['payment_status'] === 'Paid'): ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php else: ?>
                                        <a href="pay.php?maintenance_id=<?= $row['maintenance_id'] ?>"
                                           class="btn btn-success btn-sm">
                                            Pay
                                        </a>
                                    <?php endif; ?>

                                <!-- OTHER USERS (EDIT) -->
                                <?php else: ?>
                                    <button class="btn btn-warning btn-sm"
                                            onclick="loadPage('maintenanceApp.php?maintenance_id=<?= $row['maintenance_id'] ?>')">
                                        Edit
                                    </button>
                                <?php endif; ?>

                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-muted">No records found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function loadMaintenanceHistory() {
    const year = document.getElementById("apply_year").value;
    loadPage("maintenance.php?year=" + year);
}
</script>
