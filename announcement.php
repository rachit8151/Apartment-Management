<?php
session_start();
require 'dbFile/database.php';

if (empty($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

// Get user role
$username = $_SESSION['username'];
$sqlUser = "SELECT user_role FROM tblUser WHERE username = '$username'";
$resultUser = mysqli_query($conn, $sqlUser);
$userData = mysqli_fetch_assoc($resultUser);
$userRole = $userData['user_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/maintenanceMain.css"> <!-- Optional custom CSS -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Font Awesome Icons -->
</head>
<body>
    <div class="container mt-3">
        <h2 class="text-center mb-4">Announcements Management</h2>

        <!-- Add Notice button for non-owners -->
        <?php if ($userRole !== 'owner') : ?>
        <div class="text-end mb-4">
            <form method="post">
                <input type="submit" name="btnAddNotice" value="Add Notice" formaction="announcementApp.php" class="btn btn-primary">
            </form>
        </div>
        <?php endif; ?>

        <!-- Table for displaying announcements -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Announcement ID</th>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <?php if ($userRole !== 'owner') : ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all announcements
                    $sql = "SELECT * FROM tblAnnouncement ORDER BY announcement_id DESC";
                    $r = mysqli_query($conn, $sql);
                    while ($q = mysqli_fetch_assoc($r)) {
                        echo "<tr>";
                        echo "<td>" . $q['announcement_id'] . "</td>";
                        echo "<td>" . $q['type'] . "</td>";
                        echo "<td>" . $q['title'] . "</td>";
                        echo "<td>" . $q['description'] . "</td>";
                        echo "<td>" . $q['date'] . "</td>";

                        // Only show actions if user is not an owner
                        if ($userRole !== 'owner') {
                            echo "<td>
                                <a href='announcementApp.php?announcement_id=" . $q['announcement_id'] . "' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i> Edit</a> 
                                <a href='announcementDelete.php?announcement_id=" . $q['announcement_id'] . "' 
                                   onclick=\"return confirm('Are you sure you want to delete this announcement?');\" class='btn btn-danger btn-sm'>
                                   <i class='fas fa-trash'></i> Delete
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

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
