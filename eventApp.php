<?php
session_start();
require 'dbFile/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','msg'=>'Unauthorized']);
    exit;
}

/* ========== DELETE EVENT ========== */
if (isset($_POST['delete_event'])) {
    header('Content-Type: application/json');

    $id = (int)$_POST['delete_event'];
    $stmt = $conn->prepare("DELETE FROM tblEvents WHERE event_id=?");
    $stmt->bind_param("i", $id);

    echo json_encode(
        $stmt->execute()
            ? ['status'=>'success']
            : ['status'=>'error','msg'=>'Delete failed']
    );
    exit;
}

/* ========== ADD / UPDATE EVENT ========== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $date = $_POST['event_date'] ?? '';
    $time = $_POST['event_time'] ?? '';
    $desc = trim($_POST['description'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (!$date || !$time || !$desc) {
        echo json_encode(['status'=>'error','msg'=>'All fields required']);
        exit;
    }

    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        echo json_encode(['status'=>'error','msg'=>'Past date not allowed']);
        exit;
    }

    if (!empty($_POST['event_id'])) {
        // UPDATE
        $id = (int)$_POST['event_id'];
        $stmt = $conn->prepare(
            "UPDATE tblEvents
             SET event_date=?, event_time=?, description=?
             WHERE event_id=?"
        );
        $stmt->bind_param("sssi", $date, $time, $desc, $id);
    } else {
        // INSERT
        $stmt = $conn->prepare(
            "INSERT INTO tblEvents (event_date, event_time, description, user_id)
             VALUES (?,?,?,?)"
        );
        $stmt->bind_param("sssi", $date, $time, $desc, $user_id);
    }

    echo json_encode(
        $stmt->execute()
            ? ['status'=>'success']
            : ['status'=>'error','msg'=>$stmt->error]
    );
    exit;
}

/* ========== LOAD FORM ========== */
$event = null;
if (isset($_GET['event_id'])) {
    $id = (int)$_GET['event_id'];
    $event = $conn->query(
        "SELECT * FROM tblEvents WHERE event_id=$id"
    )->fetch_assoc();
}
?>

<div class="content-card">
    <h3 class="text-center mb-4">
        <?= $event ? 'Edit Event' : 'Add Event' ?>
    </h3>

    <form id="eventForm">
        <?php if ($event): ?>
            <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="event_date" class="form-control"
                   value="<?= $event['event_date'] ?? '' ?>" required>
        </div>

        <div class="mb-3">
            <label>Time</label>
            <input type="time" name="event_time" class="form-control"
                   value="<?= $event['event_time'] ?? '' ?>" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"
                      required><?= $event['description'] ?? '' ?></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary"
                onclick="loadPage('event.php')">
                ← Back
            </button>

            <button type="submit" class="btn btn-primary">
                <?= $event ? 'Update' : 'Add' ?>
            </button>
        </div>
    </form>
</div>
