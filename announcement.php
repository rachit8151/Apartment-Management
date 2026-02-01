<?php
session_start();
require 'dbFile/database.php';

if (!isset($_SESSION['username'])) exit;

$user = $_SESSION['username'];
$role = $conn->query("SELECT user_role FROM tblUser WHERE username='$user'")
             ->fetch_assoc()['user_role'];
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Announcements</h3>

        <?php if ($role !== 'owner'): ?>
            <button class="btn btn-primary"
                    onclick="loadPage('announcementApp.php')">
                <i class="fas fa-plus"></i> Add Notice
            </button>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date</th>
                    <?php if ($role !== 'owner'): ?><th>Action</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php
            $res = $conn->query("SELECT * FROM tblAnnouncement ORDER BY announcement_id DESC");
            if ($res->num_rows == 0) {
                echo "<tr><td colspan='6' class='text-center text-muted'>No announcements</td></tr>";
            }

            while ($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['announcement_id']}</td>
                        <td><span class='badge bg-info'>{$row['type']}</span></td>
                        <td>{$row['title']}</td>
                        <td class='text-truncate' style='max-width:300px'>{$row['description']}</td>
                        <td>{$row['date']}</td>";

                if ($role !== 'owner') {
                    echo "<td>
                        <button class='btn btn-warning btn-sm'
                            onclick=\"loadPage('announcementApp.php?announcement_id={$row['announcement_id']}')\">
                            <i class='fas fa-edit'></i>
                        </button>
                        <a href='announcementDelete.php?announcement_id={$row['announcement_id']}'
                           class='btn btn-danger btn-sm'
                           onclick=\"return confirm('Delete this announcement?')\">
                           <i class='fas fa-trash'></i>
                        </a>
                    </td>";
                }
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
