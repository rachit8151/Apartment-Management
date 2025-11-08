<?php
session_start();
require 'dbFile/database.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle additional expense submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
    $expense_name = mysqli_real_escape_string($conn, $_POST['expense_name']);
    $expense_amount = floatval($_POST['additional_expense']);
    $expense_date = mysqli_real_escape_string($conn, $_POST['date']);

    // Insert additional expense into the database
    $insert_expense_sql = "INSERT INTO tblExpenses (user_id, amount, name, date) 
                            VALUES ('{$_SESSION['user_id']}', '$expense_amount', '$expense_name', '$expense_date')";

    if (mysqli_query($conn, $insert_expense_sql)) {
        // Success: redirect back to the expenses page
        header("Location: expense.php");
        exit();
    } else {
        // Handle error (optional)
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Additional Expense</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .container {
            max-width: 600px;
            margin-top: 30px;
        }
        .card {
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h3 {
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h3>Add Additional Expense</h3>

        <!-- Expense Form -->
        <form method="post" action="expenseApp.php">
            <div class="form-group">
                <label for="expense_name">Expense Name:</label>
                <input type="text" name="expense_name" id="expense_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="additional_expense">Additional Expense Amount (₹):</label>
                <input type="number" name="additional_expense" id="additional_expense" class="form-control" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" class="form-control" required>
            </div>

            <div class="form-group">
                <button type="submit" name="add_expense" class="btn btn-primary">Add Expense</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS & dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
