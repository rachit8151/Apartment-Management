<?php 
session_start();
require 'dbFile/database.php';

if (empty($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

$message = ''; // For displaying messages

// Handle hide/show actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hall_name'])) {
    $hallName = mysqli_real_escape_string($conn, $_POST['hall_name']);
    $action = $_POST['action'];

    // Check if hall exists
    $sqlCheck = "SELECT * FROM tblHalls WHERE hall_name = '$hallName'";
    $resultCheck = mysqli_query($conn, $sqlCheck);

    if (!$resultCheck) {
        die("Database query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($resultCheck) == 0) {
        $message = "Hall does not exist.";
    } else {
        // Use a single query to avoid multiple calls to the database
        $newVisibility = ($action === 'hide') ? 0 : 1;
        $sqlUpdate = "UPDATE tblHalls SET visible = $newVisibility WHERE hall_name = '$hallName'";
        
        if (mysqli_query($conn, $sqlUpdate)) {
            $message = $action === 'hide' ? "Hall hidden successfully." : "Hall shown successfully.";
        } else {
            $message = "Error updating hall: " . mysqli_error($conn);
        }
    }

    // Redirect to dashboard.php with a message
    $_SESSION['message'] = $message; // Store the message in session
    header("location: dashboard.php");
    exit();
}
?>
