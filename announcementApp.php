<?php
session_start();
require 'dbFile/database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'msg' => 'Unauthorized']);
    exit;
}

/* ===== SAVE (AJAX) ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');

    $type = $_POST['type'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $date = $_POST['date'] ?? '';
    $user_id = $_SESSION['user_id'];

    if (!$type || !$title || !$description || !$date) {
        echo json_encode(['status' => 'error', 'msg' => 'All fields required']);
        exit;
    }

    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        echo json_encode(['status' => 'error', 'msg' => 'Date cannot be past']);
        exit;
    }

    if (!empty($_POST['announcement_id'])) {
        $id = (int) $_POST['announcement_id'];
        $stmt = $conn->prepare(
            "UPDATE tblAnnouncement
             SET type=?, title=?, description=?, `date`=?
             WHERE announcement_id=?"
        );
        $stmt->bind_param("ssssi", $type, $title, $description, $date, $id);
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO tblAnnouncement (type,title,description,`date`,user_id)
             VALUES (?,?,?,?,?)"
        );
        $stmt->bind_param("ssssi", $type, $title, $description, $date, $user_id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => $stmt->error]);
    }
    exit;
}

/* ===== LOAD FORM ===== */
$announcement = null;
if (isset($_GET['announcement_id'])) {
    $id = (int) $_GET['announcement_id'];
    $announcement = $conn->query(
        "SELECT * FROM tblAnnouncement WHERE announcement_id=$id"
    )->fetch_assoc();
}
?>

<div class="content-card">
    <h4 class="text-center mb-4">
        <?= $announcement ? 'Edit Announcement' : 'Add Announcement' ?>
    </h4>

    <form id="announcementForm">
        <?php if ($announcement): ?>
            <input type="hidden" name="announcement_id" value="<?= $announcement['announcement_id'] ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-select" required>
                <option value="">-- Select --</option>
                <?php
                foreach (['meeting', 'event', 'notice', 'message'] as $t) {
                    $sel = ($announcement['type'] ?? '') === $t ? 'selected' : '';
                    echo "<option value='$t' $sel>" . ucfirst($t) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Title</label>
            <input name="title" class="form-control" value="<?= $announcement['title'] ?? '' ?>" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"
                required><?= $announcement['description'] ?? '' ?></textarea>
        </div>

        <div class="mb-4">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="<?= $announcement['date'] ?? '' ?>" required>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" onclick="loadPage('announcement.php')">← Back</button>
            <button type="submit" class="btn btn-primary">
                <?= $announcement ? 'Update' : 'Add' ?>
            </button>
        </div>
    </form>
</div>