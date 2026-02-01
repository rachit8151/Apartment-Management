<?php
session_start();
require 'dbFile/database.php';

if (empty($_SESSION['username'])) {
    exit("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$roleRes = mysqli_query($conn, "SELECT user_role FROM tblUser WHERE user_id=$user_id");
$userRole = mysqli_fetch_assoc($roleRes)['user_role'];
?>

<div class="content-card">
    <h3 class="text-center mb-4">Event Management</h3>

    <?php if ($userRole !== 'owner'): ?>
        <div class="text-end mb-3">
            <button class="btn btn-primary"
                onclick="loadPage('eventApp.php')">
                + Add Event
            </button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Description</th>
                    <?php if ($userRole !== 'owner'): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-center">
            <?php
            $res = mysqli_query($conn, "SELECT * FROM tblEvents ORDER BY event_date DESC");
            if (mysqli_num_rows($res) > 0):
                while ($row = mysqli_fetch_assoc($res)):
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['event_date']) ?></td>
                    <td><?= htmlspecialchars($row['event_time']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>

                    <?php if ($userRole !== 'owner'): ?>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="loadPage('eventApp.php?event_id=<?= $row['event_id'] ?>')">
                                Edit
                            </button>

                            <button class="btn btn-danger btn-sm ms-1"
                                onclick="deleteEvent(<?= $row['event_id'] ?>)">
                                Delete
                            </button>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; else: ?>
                <tr>
                    <td colspan="4">No events found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
