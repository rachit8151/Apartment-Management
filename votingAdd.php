<?php
session_start();
require 'dbFile/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id']; // Assuming the user is logged in

    // Check if the user has already applied
    $checkQuery = "SELECT * FROM tblSecretaryApplications WHERE user_id = $userId";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "You have already applied for the secretary position.";
    } else {
        // Insert the user's application for secretary
        $insertQuery = "INSERT INTO tblSecretaryApplications (user_id) VALUES ($userId)";
        if (mysqli_query($conn, $insertQuery)) {
            echo "You have successfully applied for the secretary position!";
        } else {
            echo "Error: Could not apply.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Secretary</title>
</head>
<body>
    <h2>Apply for Secretary Position</h2>
    <form method="POST">
        <button type="submit">Apply</button>
    </form>
</body>
</html>
