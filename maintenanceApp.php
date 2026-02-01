<?php
session_start();
require 'dbFile/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','msg'=>'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');

    $year = (int)($_POST['year'] ?? 0);
    $penalty = (int)($_POST['penalty'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    if (!$year || !$description) {
        echo json_encode(['status'=>'error','msg'=>'All fields required']);
        exit;
    }

    /* UPDATE */
    if (!empty($_POST['maintenance_id'])) {

        $id = (int)$_POST['maintenance_id'];

        $stmt = $conn->prepare(
            "UPDATE tblMaintenance
             SET year=?, penalty=?, description=?
             WHERE maintenance_id=?"
        );
        $stmt->bind_param("iisi", $year, $penalty, $description, $id);

    } else {

        /* INSERT */
        $amount = 8000;

        $stmt = $conn->prepare(
            "INSERT INTO tblMaintenance (year, amount, penalty, description)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("iiis", $year, $amount, $penalty, $description);
    }

    if ($stmt->execute()) {
        echo json_encode(['status'=>'success']);
    } else {
        echo json_encode(['status'=>'error','msg'=>$stmt->error]);
    }
    exit;
}

/* ===== LOAD FORM ===== */
$maintenance = null;
if (isset($_GET['maintenance_id'])) {
    $id = (int)$_GET['maintenance_id'];
    $maintenance = $conn->query(
        "SELECT * FROM tblMaintenance WHERE maintenance_id=$id"
    )->fetch_assoc();
}
?>

<div class="content-card">
    <h3 class="text-center mb-4">
        <?= $maintenance ? 'Edit Maintenance' : 'Add Maintenance' ?>
    </h3>

    <form id="maintenanceForm">

        <?php if ($maintenance): ?>
            <input type="hidden" name="maintenance_id"
                   value="<?= $maintenance['maintenance_id'] ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label>Year</label>
            <input type="number" name="year"
                   class="form-control"
                   value="<?= $maintenance['year'] ?? date('Y') ?>" required>
        </div>

        <div class="mb-3">
            <label>Amount</label>
            <input class="form-control" value="8000" readonly>
        </div>

        <div class="mb-3">
            <label>Penalty</label>
            <input type="number" name="penalty"
                   class="form-control"
                   value="<?= $maintenance['penalty'] ?? 0 ?>" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"
                      required><?= $maintenance['description'] ?? '' ?></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button"
                    class="btn btn-secondary"
                    onclick="loadPage('maintenance.php')">
                ← Back
            </button>

            <button type="submit" class="btn btn-primary">
                <?= $maintenance ? 'Update' : 'Add' ?>
            </button>
        </div>
    </form>
</div>
