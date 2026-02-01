<?php
session_start();
require 'dbFile/database.php';

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status'=>'error','msg'=>'Unauthorized']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/* ===== ADD COMPLAINT ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_text'])) {

    header('Content-Type: application/json');

    $text = trim($_POST['complaint_text']);

    if ($text === '') {
        echo json_encode(['status'=>'error','msg'=>'Complaint cannot be empty']);
        exit;
    }

    $stmt = $conn->prepare(
        "INSERT INTO tblComplaints (user_id, complaint_text, complaint_date, status)
         VALUES (?, ?, NOW(), 'Pending')"
    );
    $stmt->bind_param("is", $user_id, $text);

    if ($stmt->execute()) {
        echo json_encode(['status'=>'success']);
    } else {
        echo json_encode(['status'=>'error','msg'=>$stmt->error]);
    }
    exit;
}

/* ===== RESOLVE COMPLAINT (ADMIN) ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_id'])) {

    header('Content-Type: application/json');

    $id = (int)$_POST['resolve_id'];

    $stmt = $conn->prepare(
        "UPDATE tblComplaints SET status='Resolved' WHERE complaint_id=?"
    );
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status'=>'success']);
    } else {
        echo json_encode(['status'=>'error','msg'=>$stmt->error]);
    }
    exit;
}
?>

<div class="content-card">
    <h3 class="text-center mb-3">Submit Complaint</h3>

    <form id="complaintForm">
        <div class="mb-3">
            <label>Complaint</label>
            <textarea name="complaint_text"
                      class="form-control"
                      rows="4"
                      required></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button"
                    class="btn btn-secondary"
                    onclick="loadPage('complaint.php')">
                ← Back
            </button>

            <button type="submit" class="btn btn-primary">
                Submit
            </button>
        </div>
    </form>
</div>
