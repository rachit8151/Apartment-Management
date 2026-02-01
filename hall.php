<?php
session_start();
require 'dbFile/database.php';

if (empty($_SESSION['username'])) {
    exit("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$userRes = mysqli_query($conn, "SELECT user_role FROM tblUser WHERE user_id=$user_id");
$userData = mysqli_fetch_assoc($userRes);
$userRole = $userData['user_role'];
?>

<div class="content-card">
    <h3 class="text-center mb-4">Halls Management</h3>

    <?php if ($userRole !== 'owner'): ?>
        <div class="text-end mb-3">
            <button class="btn btn-primary"
                onclick="loadPage('hallApp.php')">
                + Add Hall
            </button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th>Name</th>
                    <th>Capacity</th>
                    <th>Location</th>
                    <th>Amenities</th>
                    <?php if ($userRole !== 'owner'): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-center">
            <?php
            $res = mysqli_query($conn, "SELECT * FROM tblHalls ORDER BY hall_id DESC");
            if (mysqli_num_rows($res) > 0):
                while ($row = mysqli_fetch_assoc($res)):
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['hall_name']) ?></td>
                    <td><?= $row['capacity'] ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= htmlspecialchars($row['amenities']) ?></td>

                    <?php if ($userRole !== 'owner'): ?>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="loadPage('hallApp.php?hall_id=<?= $row['hall_id'] ?>')">
                                Edit
                            </button>

                            <button class="btn btn-danger btn-sm ms-1"
                                onclick="deleteHall(<?= $row['hall_id'] ?>)">
                                Delete
                            </button>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; else: ?>
                <tr>
                    <td colspan="5">No halls found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
