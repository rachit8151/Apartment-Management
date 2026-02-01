<?php
session_start();
require 'dbFile/database.php';

if (empty($_SESSION['username'])) {
    echo json_encode(['status'=>'error','msg'=>'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','msg'=>'Invalid request']);
    exit;
}

$hallName = trim($_POST['hall_name'] ?? '');
$action   = $_POST['action'] ?? '';

if ($hallName === '' || !in_array($action, ['hide','show'])) {
    echo json_encode(['status'=>'error','msg'=>'Invalid input']);
    exit;
}

$newVisibility = ($action === 'hide') ? 0 : 1;

$check = $conn->query("SELECT hall_id FROM tblHalls WHERE hall_name='$hallName'");
if ($check->num_rows === 0) {
    echo json_encode(['status'=>'error','msg'=>'Hall not found']);
    exit;
}

$conn->query("UPDATE tblHalls SET visible=$newVisibility WHERE hall_name='$hallName'");

echo json_encode([
    'status' => 'success',
    'msg'    => $action === 'hide'
        ? 'Hall hidden successfully'
        : 'Hall shown successfully'
]);
