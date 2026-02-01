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
        $conn = mysqli_connect("localhost", "root", "", "dbApartmentManage");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        ?>
    </body>
</html>
