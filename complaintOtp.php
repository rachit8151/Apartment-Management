<?php
session_start();
require 'dbFile/database.php'; // Database connection
// Check if OTP is set in session
if (!isset($_SESSION['otp'])) {
    header("Location: complaint.php"); // If no OTP is stored in session, redirect to complaint page
    exit();
}

if (isset($_POST['verify_otp'])) {
    $inputOtp = $_POST['otp'];

    // Check if entered OTP matches the one stored in the session
    if ($_SESSION['otp'] == $inputOtp) {
        // Update complaint status to 'Resolved'
        $complaint_id = $_SESSION['complaint_id'];
        $sql_update = "UPDATE tblComplaints SET status = 'Resolved' WHERE complaint_id = '$complaint_id'";
        if (mysqli_query($conn, $sql_update)) {
            echo "<script>alert('Complaint resolved successfully.');</script>";
            header("Location: dashboard.php"); // Redirect to dashboard after resolving
            exit();
        } else {
            echo "<script>alert('Error resolving the complaint.');</script>";
        }
    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify OTP</title>
    </head>
    <body>
        <h2>Enter OTP to Resolve Complaint</h2>
        <form method="POST" action="">
            <label for="otp">OTP:</label>
            <input type="text" name="otp" id="otp" required>
            <input type="submit" name="verify_otp" value="Verify OTP">
        </form>
    </body>
</html>
