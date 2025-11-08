<?php 
session_start();
require 'dbFile/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);
    $sql = "SELECT * FROM tblHallBooking WHERE booking_id = $booking_id";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die('Query failed: ' . mysqli_error($conn));
    }

    $hall = mysqli_fetch_assoc($result);
    if (!$hall) {
        die('Record not found.');
    }
}
?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $booking_id = intval($_GET['booking_id']);
        $qu = "delete from tblHallBooking where booking_id= $booking_id";
        $result = mysqli_query($conn, $qu);
        if ($result) {
            header("location: dashboard.php");
        } else {
            echo 'Failed to delete.';
        }
        ?>
    </body>
</html>
