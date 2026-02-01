<?php
session_start();
require 'dbFile/database.php';

if (empty($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','msg'=>'Unauthorized']);
    exit;
}

/* ===== AJAX SAVE ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');

    $name   = trim($_POST['expense_name'] ?? '');
    $amount = floatval($_POST['additional_expense'] ?? 0);
    $date   = $_POST['date'] ?? '';
    $userId = $_SESSION['user_id'];

    if ($name === '' || $amount <= 0 || $date === '') {
        echo json_encode(['status'=>'error','msg'=>'All fields required']);
        exit;
    }

    $stmt = $conn->prepare(
        "INSERT INTO tblExpenses (user_id, name, amount, date)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("isds", $userId, $name, $amount, $date);

    if ($stmt->execute()) {
        echo json_encode(['status'=>'success']);
    } else {
        echo json_encode(['status'=>'error','msg'=>'DB error']);
    }
    exit;
}
?>

<!-- ===== FORM UI ===== -->
<div class="content-card">
    <h3 class="text-center mb-4">Add Expense</h3>

    <form id="expenseForm">

        <div class="mb-3">
            <label>Expense Name</label>
            <input type="text" name="expense_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Amount (₹)</label>
            <input type="number" name="additional_expense" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary"
                    onclick="loadPage('expense.php')">
                ← Back
            </button>

            <button type="submit" class="btn btn-primary">
                Save Expense
            </button>
        </div>
    </form>
</div>
