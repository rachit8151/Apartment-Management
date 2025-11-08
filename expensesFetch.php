<?php

session_start();
require 'dbFile/database.php'; // Include your database connection
// Check if the user is logged in
if (empty($_SESSION['username'])) {
    exit('Not authorized');
}

// Get the selected month
$selected_month = isset($_POST['month']) ? $_POST['month'] : date('Y-m');

// Fetch added expenses based on the selected month
$expenses_list_sql = "SELECT name, amount, date FROM tblExpenses 
                       WHERE 
                       DATE_FORMAT(date, '%Y-%m') = '$selected_month'";
$expenses_list_result = mysqli_query($conn, $expenses_list_sql);

// Output the table
echo '<table>
<tr>
<th>Expense Name</th>
<th>Amount (₹)</th>
<th>Date</th>
</tr>';

if (mysqli_num_rows($expenses_list_result) > 0) {
    while ($expense = mysqli_fetch_assoc($expenses_list_result)) {
        echo '<tr>
            <td>' . htmlspecialchars($expense['name']) . '</td>
            <td>' . number_format($expense['amount'], 2) . '</td>
            <td>' . htmlspecialchars($expense['date']) . '</td>
        </tr>';
    }
} else {
    echo '<tr>
        <td colspan="3" style="text-align: center;">No expenses found for the selected month.</td>
    </tr>';
}

echo '</table>'; // Close the table

mysqli_close($conn);
?>
