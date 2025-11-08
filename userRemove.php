<?php
session_start();
require 'dbFile/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']); // Ensure the user ID is an integer
        
        // Execute the delete query
        $deleteQuery = "DELETE FROM tblUser WHERE user_id = '$user_id'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            echo " removed successfully.";
            header('location: dashboard.php');
        } else {
            echo "Error removing : " . mysqli_error($conn);
        }
    } else {
        echo "No user ID provided.";
    }
}

mysqli_close($conn);
?>
