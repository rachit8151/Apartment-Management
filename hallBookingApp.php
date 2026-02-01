<?php
session_start();
require 'dbFile/database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status'=>'error','msg'=>'Unauthorized']);
    exit;
}

/* ===== APPROVE / REJECT ===== */
if (isset($_POST['update_status'])) {
    header('Content-Type: application/json');

    $id = (int)$_POST['booking_id'];
    $status = $_POST['status'];

    if (!in_array($status,['Approved','Rejected'])) {
        echo json_encode(['status'=>'error','msg'=>'Invalid status']);
        exit;
    }

    $stmt = $conn->prepare(
        "UPDATE tblHallBooking SET booking_status=? WHERE booking_id=?"
    );
    $stmt->bind_param("si",$status,$id);

    echo $stmt->execute()
        ? json_encode(['status'=>'success'])
        : json_encode(['status'=>'error','msg'=>$stmt->error]);
    exit;
}

/* ===== DELETE ===== */
if (isset($_POST['delete_booking'])) {
    header('Content-Type: application/json');
    $id=(int)$_POST['delete_booking'];

    echo mysqli_query($conn,"DELETE FROM tblHallBooking WHERE booking_id=$id")
        ? json_encode(['status'=>'success'])
        : json_encode(['status'=>'error','msg'=>'Delete failed']);
    exit;
}

/* ===== SAVE (ADD / UPDATE) ===== */
if ($_SERVER['REQUEST_METHOD']==='POST') {
    header('Content-Type: application/json');

    $hall=$_POST['hall_name'];
    $date=$_POST['booking_date'];
    $start=$_POST['booking_time'];
    $end=$_POST['end_time'];
    $purpose=$_POST['purpose'];
    $uid=$_SESSION['user_id'];

    if (!empty($_POST['booking_id'])) {
        $id=(int)$_POST['booking_id'];
        $stmt=$conn->prepare(
            "UPDATE tblHallBooking
             SET hall_name=?, booking_date=?, booking_time=?, end_time=?, purpose=?
             WHERE booking_id=?"
        );
        $stmt->bind_param("sssssi",$hall,$date,$start,$end,$purpose,$id);
    } else {
        $status='Pending';
        $stmt=$conn->prepare(
            "INSERT INTO tblHallBooking
            (user_id,hall_name,booking_date,booking_time,end_time,purpose,booking_status)
            VALUES (?,?,?,?,?,?,?)"
        );
        $stmt->bind_param("issssss",$uid,$hall,$date,$start,$end,$purpose,$status);
    }

    echo $stmt->execute()
        ? json_encode(['status'=>'success'])
        : json_encode(['status'=>'error','msg'=>$stmt->error]);
    exit;
}

/* ===== LOAD FORM ===== */
$hallbook=null;
if (isset($_GET['booking_id'])) {
    $id=(int)$_GET['booking_id'];
    $hallbook=mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT * FROM tblHallBooking WHERE booking_id=$id")
    );
}
?>

<div class="content-card">
<h3 class="text-center"><?= $hallbook?'Edit':'Add' ?> Hall Booking</h3>

<form id="hallBookingForm">
<?php if($hallbook): ?>
<input type="hidden" name="booking_id" value="<?= $hallbook['booking_id'] ?>">
<?php endif; ?>

<input class="form-control mb-2" name="hall_name" placeholder="Hall Name"
       value="<?= $hallbook['hall_name'] ?? '' ?>" required>

<input type="date" class="form-control mb-2" name="booking_date"
       value="<?= $hallbook['booking_date'] ?? '' ?>" required>

<input type="time" class="form-control mb-2" name="booking_time"
       value="<?= $hallbook['booking_time'] ?? '' ?>" required>

<input type="time" class="form-control mb-2" name="end_time"
       value="<?= $hallbook['end_time'] ?? '' ?>" required>

<textarea class="form-control mb-3" name="purpose"
          required><?= $hallbook['purpose'] ?? '' ?></textarea>

<button class="btn btn-primary w-100">Save</button>
</form>
</div>
