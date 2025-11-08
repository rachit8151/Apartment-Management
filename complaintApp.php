<?php
session_start();
require 'dbFile/database.php'; // Database connection
// Check if the user is logged in
if (empty($_SESSION['username'])) {
    header("location: login.php");
    exit();
}

// Handle the complaint submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_complaint'])) {
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
    $complaint_text = mysqli_real_escape_string($conn, $_POST['complaint_text']); // Sanitize input
    // Ensure that the complaint text is not empty
    if (!empty($complaint_text)) {
        // Insert the complaint into the database
        $sql = "INSERT INTO tblComplaints (user_id, complaint_text, complaint_date) VALUES ('$user_id', '$complaint_text', NOW())";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Your complaint has been submitted successfully.');</script>";
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Error submitting your complaint. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Complaint text cannot be empty.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Submit Complaint</title>
    </head>
    <body>

        <div class="complaint-box">
            <h3>Submit Your Complaint</h3>

            <!-- Complaint Form -->
            <form method="POST" action="">
                <textarea name="complaint_text" rows="4" placeholder="Describe your issue..." required></textarea><br>
                <input type="submit" name="submit_complaint" value="Submit Complaint">
            </form>
        </div>

    </body>
</html>
