<?php
session_start();
require 'dbFile/database.php';

if (empty($_SESSION['user_id'])) {
    exit("Unauthorized");
}

$user_id = (int)$_SESSION['user_id'];

$roleRes = mysqli_query($conn, "SELECT user_role FROM tblUser WHERE user_id=$user_id");
$user_role = mysqli_fetch_assoc($roleRes)['user_role'] ?? '';

if ($user_role === 'admin') {
    $sql = "
        SELECT c.complaint_id, c.complaint_text, c.complaint_date, c.status, u.name
        FROM tblComplaints c
        JOIN tblUser u ON c.user_id = u.user_id
        ORDER BY c.complaint_date DESC
    ";
} else {
    $sql = "
        SELECT complaint_id, complaint_text, complaint_date, status
        FROM tblComplaints
        WHERE user_id = $user_id
        ORDER BY complaint_date DESC
    ";
}

$result = mysqli_query($conn, $sql);
?>

<div class="content-card">
    <h3 class="text-center mb-3">Complaints</h3>

    <div class="mb-3 text-end">
        <button class="btn btn-primary"
                onclick="loadPage('complaintApp.php')">
            + Add Complaint
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <?php if ($user_role === 'admin'): ?>
                        <th>User</th>
                    <?php endif; ?>
                    <th>Complaint</th>
                    <th>Date</th>
                    <th>Status</th>
                    <?php if ($user_role === 'admin'): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-center">

            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <?php if ($user_role === 'admin'): ?>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                        <?php endif; ?>
                        <td><?= htmlspecialchars($row['complaint_text']) ?></td>
                        <td><?= $row['complaint_date'] ?></td>
                        <td><?= $row['status'] ?></td>

                        <?php if ($user_role === 'admin'): ?>
                            <td>
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <button class="btn btn-success btn-sm"
                                            onclick="resolveComplaint(<?= $row['complaint_id'] ?>)">
                                        Resolve
                                    </button>
                                <?php else: ?>
                                    <span class="badge bg-success">Resolved</span>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-muted">No complaints found</td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>
