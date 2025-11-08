<?php

session_start();
require 'dbFile/database.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$id = $_GET['id'];
if ($id == 1) {
// Check for unread announcements (read_status = 0)
    $ann_sql = "SELECT COUNT(*) AS unread_count FROM tblAnnouncement WHERE read_status = 0";
    $ann_result = $conn->query($ann_sql);
// Get the count of unread announcements
    if ($ann_result->num_rows > 0) {
        $ann_row = $ann_result->fetch_assoc();
        echo $ann_row['unread_count']; // Output unread count
    } else {
        echo 0; // No unread announcements
    }
} elseif ($id == 2) {
    $eve_sql = "SELECT COUNT(*) AS unread_count FROM tblEvents WHERE read_status = 0";
    $eve_result = $conn->query($eve_sql);
// Get the count of unread 
    if ($eve_result->num_rows > 0) {
        $eve_row = $eve_result->fetch_assoc();
        echo $eve_row['unread_count']; // Output unread count
    } else {
        echo 0; // No unread announcements
    }
} elseif ($id == 3) {
    $hallbook_sql = "SELECT COUNT(*) AS unread_count FROM tblHallBooking WHERE read_status = 0";
    $hallbook_result = $conn->query($hallbook_sql);
// Get the count of unread 
    if ($hallbook_result->num_rows > 0) {
        $hallbook_row = $hallbook_result->fetch_assoc();
        echo $hallbook_row['unread_count']; // Output unread count
    } else {
        echo 0; // No unread
    }
}

/*
  if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  echo "id: " . $row["id"]. " - Notification: " . $row["description"];
  }
  } else {
  echo "0 results";
  }
 */
$conn->close();
?>
