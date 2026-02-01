<?php
session_start();
require 'dbFile/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','msg'=>'Unauthorized']);
    exit;
}

/* ================= DELETE ================= */
if (isset($_POST['delete_hall'])) {
    header('Content-Type: application/json');

    $hall_id = (int)$_POST['delete_hall'];
    $stmt = $conn->prepare("DELETE FROM tblHalls WHERE hall_id=?");
    $stmt->bind_param("i", $hall_id);

    echo json_encode(
        $stmt->execute()
            ? ['status'=>'success']
            : ['status'=>'error','msg'=>'Delete failed']
    );
    exit;
}

/* ================= SAVE (ADD / UPDATE) ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $hall_name = trim($_POST['hall_name'] ?? '');
    $capacity  = (int)($_POST['capacity'] ?? 0);
    $location  = trim($_POST['location'] ?? '');
    $amenities = trim($_POST['amenities'] ?? '');

    if (!$hall_name || !$capacity || !$location) {
        echo json_encode(['status'=>'error','msg'=>'All fields required']);
        exit;
    }

    if (!empty($_POST['hall_id'])) {
        // UPDATE
        $id = (int)$_POST['hall_id'];
        $stmt = $conn->prepare(
            "UPDATE tblHalls
             SET hall_name=?, capacity=?, location=?, amenities=?
             WHERE hall_id=?"
        );
        $stmt->bind_param("sissi", $hall_name, $capacity, $location, $amenities, $id);
    } else {
        // INSERT
        $stmt = $conn->prepare(
            "INSERT INTO tblHalls (hall_name, capacity, location, amenities)
             VALUES (?,?,?,?)"
        );
        $stmt->bind_param("siss", $hall_name, $capacity, $location, $amenities);
    }

    echo json_encode(
        $stmt->execute()
            ? ['status'=>'success']
            : ['status'=>'error','msg'=>$stmt->error]
    );
    exit;
}

/* ================= LOAD FORM ================= */
$hall = null;
if (isset($_GET['hall_id'])) {
    $id = (int)$_GET['hall_id'];
    $hall = $conn->query("SELECT * FROM tblHalls WHERE hall_id=$id")->fetch_assoc();
}
?>

<div class="content-card">
    <h3 class="text-center mb-4">
        <?= $hall ? 'Edit Hall' : 'Add Hall' ?>
    </h3>

    <form id="hallForm">
        <?php if ($hall): ?>
            <input type="hidden" name="hall_id" value="<?= $hall['hall_id'] ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label>Hall Name</label>
            <input type="text" name="hall_name" class="form-control"
                   value="<?= $hall['hall_name'] ?? '' ?>" required>
        </div>

        <div class="mb-3">
            <label>Capacity</label>
            <input type="number" name="capacity" class="form-control"
                   value="<?= $hall['capacity'] ?? '' ?>" required>
        </div>

        <div class="mb-3">
            <label>Location</label>
            <input type="text" name="location" class="form-control"
                   value="<?= $hall['location'] ?? '' ?>" required>
        </div>

        <div class="mb-3">
            <label>Amenities</label>
            <textarea name="amenities" class="form-control"><?= $hall['amenities'] ?? '' ?></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary"
                onclick="loadPage('hall.php')">
                ← Back
            </button>

            <button type="submit" class="btn btn-primary">
                <?= $hall ? 'Update' : 'Add' ?>
            </button>
        </div>
    </form>
</div>
