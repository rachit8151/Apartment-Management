<?php
session_start();
require 'dbFile/database.php'; // Database connection

// Check if the user is logged in
if (empty($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

// Get user role
$username = $_SESSION['username'];
$sqlUser = "SELECT user_role, user_id FROM tblUser WHERE username = '$username'";
$resultUser = mysqli_query($conn, $sqlUser);
$userData = mysqli_fetch_assoc($resultUser);
$userRole = $userData['user_role'];
$userId = $userData['user_id'];

// Set the SQL query based on the user role
if ($userRole === 'admin') {
    // Admin sees all complaints
    $sql = "SELECT c.complaint_id, c.complaint_text, c.complaint_date, u.name, u.email, c.status
            FROM tblComplaints c
            JOIN tblUser u ON c.user_id = u.user_id
            ORDER BY c.complaint_date DESC";
} else {
    // Non-admins see only their own complaints
    $sql = "SELECT c.complaint_id, c.complaint_text, c.complaint_date, u.name, u.email, c.status
            FROM tblComplaints c
            JOIN tblUser u ON c.user_id = u.user_id
            WHERE c.user_id = '$userId'
            ORDER BY c.complaint_date DESC";
}

$result = mysqli_query($conn, $sql);

// Resolve complaint action (Admin Only)
if (isset($_GET['resolve']) && $userRole === 'admin') {
    $complaint_id = $_GET['resolve'];

    // Fetch the user's email for OTP
    $sqlFetchUserEmail = "SELECT email FROM tblUser WHERE user_id = (SELECT user_id FROM tblComplaints WHERE complaint_id = '$complaint_id')";
    $resultEmail = mysqli_query($conn, $sqlFetchUserEmail);
    $userEmail = mysqli_fetch_assoc($resultEmail)['email'];

    // Generate OTP (4 digits)
    $otp = rand(1000, 9999);

    // Store OTP and complaint ID in session for later verification
    $_SESSION['otp'] = $otp;
    $_SESSION['complaint_id'] = $complaint_id;

    // Send OTP to the user's email
    $subject = "Your OTP Code for Complaint Resolution";
    $message = "Your OTP code for complaint resolution is: $otp";
    $headers = "From: rachit1575@gmail.com";

    // Send email using PHP's mail() function
    if (mail($userEmail, $subject, $message, $headers)) {
        header('Location: complaintOtp.php'); // Redirect to OTP verification page
        exit();
    } else {
        echo '<div class="alert alert-danger" role="alert">Failed to send OTP. Please try again.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaints</title>
    <!-- Link to Bootstrap 4.5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .complain-container {
            margin: 20px;
            text-align: right;
        }
        .complaint-box {
            margin-top: 20px;
        }
        .btn-resolve {
            color: #007BFF;
            text-decoration: none;
        }
        .btn-resolve:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center">Manage Complaints</h3>
        <div class="complain-container">
            <form method="post">
                <input type="submit" name="btnComp" value="Add Complaint" formaction="complaintApp.php" class="btn btn-primary mb-3">
            </form>
        </div>
        
        <div class="complaint-box">
            <?php
            if (mysqli_num_rows($result) > 0) {
            ?>
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>User</th>
                            <th>Complaint</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <?php if ($userRole === 'admin') { ?>
                                <th>Action</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['complaint_text']); ?></td>
                            <td><?php echo $row['complaint_date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <?php if ($userRole === 'admin' && $row['status'] == 'Pending') { ?>
                                <td>
                                    <a href="complaint.php?resolve=<?php echo $row['complaint_id']; ?>" class="btn-resolve">Resolve</a>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No complaints submitted yet.</p>
            <?php } ?>
        </div>
    </div>

    <!-- Link to Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
