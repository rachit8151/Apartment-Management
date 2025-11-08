<?php
session_start();
require 'dbFile/database.php';
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
        $announcement_id = intval($_GET['announcement_id']);
        $qu = "delete from tblAnnouncement where announcement_id= $announcement_id ";
        $result = mysqli_query($conn, $qu);
        if ($result) {
            header("location: dashboard.php");
        } else {
            echo 'Failed to delete.';
        }
        ?>
    </body>
</html>
