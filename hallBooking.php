<?php
session_start();
require 'dbFile/database.php';

if (empty($_SESSION['username'])) {
    exit("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$sqlUser = "SELECT user_role FROM tblUser WHERE user_id=$user_id";
$userRole = mysqli_fetch_assoc(mysqli_query($conn,$sqlUser))['user_role'];
?>

<div class="content-card">
    <h3 class="text-center mb-4">Hall Booking Management</h3>

    <div class="text-end mb-3">
        <button class="btn btn-primary"
                onclick="loadPage('hallBookingApp.php')">
            + Add Hall Booking
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Hall</th>
                    <th>Date</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            <?php
            $res = mysqli_query($conn,"SELECT * FROM tblHallBooking ORDER BY booking_date DESC");
            if (mysqli_num_rows($res)>0):
                while ($row=mysqli_fetch_assoc($res)):
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['hall_name']) ?></td>
                    <td><?= $row['booking_date'] ?></td>
                    <td><?= $row['booking_time'] ?></td>
                    <td><?= $row['end_time'] ?></td>
                    <td><?= htmlspecialchars($row['purpose']) ?></td>
                    <td><?= $row['booking_status'] ?></td>

                    <td>
                    <?php if ($userRole==='secretary' && $row['booking_status']==='Pending'): ?>
                        <button class="btn btn-success btn-sm"
                                onclick="updateBookingStatus(<?= $row['booking_id'] ?>,'Approved')">
                            Approve
                        </button>
                        <button class="btn btn-danger btn-sm"
                                onclick="updateBookingStatus(<?= $row['booking_id'] ?>,'Rejected')">
                            Reject
                        </button>

                    <?php elseif ($userRole==='owner' && $row['booking_status']==='Pending'): ?>
                        <button class="btn btn-warning btn-sm"
                                onclick="loadPage('hallBookingApp.php?booking_id=<?= $row['booking_id'] ?>')">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm"
                                onclick="deleteHallBooking(<?= $row['booking_id'] ?>)">
                            Delete
                        </button>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="7">No bookings found</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
