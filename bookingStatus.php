<?php
session_start();
require 'dbFile/database.php';

// Check if user is logged in
if (empty($_SESSION['username']) || empty($_SESSION['password'])) {
    header("location: login.php");
    exit();
}

// Check if booking_id and action are set in the URL
if (isset($_GET['booking_id']) && isset($_GET['action'])) {
    // Sanitize input to prevent SQL injection
    $booking_id = (int) $_GET['booking_id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        // Approve the booking
        $sqlApprove = "UPDATE tblHallBooking SET booking_status = 'Approved' WHERE booking_id = $booking_id";
        if (!mysqli_query($conn, $sqlApprove)) {
            echo "<p>Error approving booking: " . mysqli_error($conn) . "</p>";
        }
    } elseif ($action === 'deny') {
        // Deny the booking
        $sqlReject = "UPDATE tblHallBooking SET booking_status = 'Rejected' WHERE booking_id = $booking_id";
        if (!mysqli_query($conn, $sqlReject)) {
            echo "<p>Error rejecting booking: " . mysqli_error($conn) . "</p>";
        }
    }
    header("Location: dashboard.php");
    exit();
} else {
    echo "<p>Invalid request.</p>";
}
?>
