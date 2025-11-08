<?php
require 'dbFile/database.php';

$user_id = $_GET["user_id"];
$q = "select * from tblUser where username like '%$user_id%' or email like '%$user_id%'";

$r = mysqli_query($conn, $q);

echo "<table border='1'>";
echo "<tr>";
echo "<th> Username </th>";
echo "<th>Email</th>";
echo "<th>user role</th>";
echo "<th>name</th>";
echo "<th>contact</th>";
echo "<th> wings</th>";
echo "<th> flat no</th>";
echo "</tr>";
while ($q = mysqli_fetch_assoc($r)) {
    echo "<tr>";
    echo "<td>" . $q['username'] . "</td>";
    echo "<td>" . $q['email'] . "</td>";
    echo "<td>" . $q['user_role'] . "</td>";
    echo "<td>" . $q['name'] . "</td>";
    echo "<td>" . $q['contact'] . "</td>";
    echo "<td>" . $q['wings'] . "</td>";
    echo "<td>" . $q['flat_no'] . "</td>";
    echo "</tr>";
}
echo "</table>";
