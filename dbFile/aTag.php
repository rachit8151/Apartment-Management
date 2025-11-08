<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <a href="profile.php"><i class="fas fa-fw fa-tools"></i> Profile Detail </a>
        <a href="announcement.php"><i class="fas fa-fw fa-tools"></i> Announcement </a>
        <a href="maintenance.php"><i class="fas fa-fw fa-tools"></i> Maintenance </a>
        <a href="event_management.php"><i class="fas fa-fw fa-tools"></i> Event </a>
        <a href="manage_hall.php"><i class="fas fa-fw fa-tools"></i> Hall </a>

        <script>
            function loadForm() {
                const xhr = new XMLHttpRequest();
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        document.getElementById('main-content').innerHTML = xhr.responseText;
                    }
                };
                xhr.open('GET', 'announcement.php', true);
                xhr.send();
            }
            document.getElementById('loadAnn').addEventListener('click', loadForm);
        </script>
        <!--script>
            // Load maintenance management
            document.getElementById('loadMaintenance').addEventListener('click', function () {
                const xhr = new XMLHttpRequest();
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        document.getElementById('main-content').innerHTML = xhr.responseText;
                    }
                };
                xhr.open('GET', 'maintenance.php', true);
                xhr.send();
            });
        </script>
        <script>
            // Load event management
            document.getElementById('loadEvent').addEventListener('click', function () {
                const xhr = new XMLHttpRequest();
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        document.getElementById('main-content').innerHTML = xhr.responseText;
                    }
                };
                xhr.open('GET', 'event_management.php', true);
                xhr.send();
            });
        </script>
        <script>
            // Load hall management
            document.getElementById('loadHall').addEventListener('click', function () {
                const xhr = new XMLHttpRequest();
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        document.getElementById('main-content').innerHTML = xhr.responseText;
                    }
                };
                xhr.open('GET', 'manage_hall.php', true);
                xhr.send();
            });
        </script-->


        Apartment Management System
        $query = "INSERT INTO tblUser (username, email, password, user_role, name, contact, aadhar_no, wings, flat_no) VALUES ('$username', '$email', '$password', '$user_role', '$name', $contact, $aadhar_no, '$wings', $flat_no)";
         $sql = "INSERT INTO tblmaintenance (user_id, year, amount, penalty, description) VALUES ('$user_id', '$year', '$amount', '$penalty', '$description')";
         
        $sql = "insert into tblAnnouncement (type, title, description, date, user_id) values ('$type', '$title', '$description', '$date', $user_id)";
       
        $sql = "INSERT INTO tblEvents (event_date, event_time, description) VALUES ('$event_date', '$event_time', '$description')";
        $sql = "INSERT INTO halls (name, capacity, location, amenities) VALUES ('$name', $capacity, '$location', '$amenities')";
        $insert_sql = "INSERT INTO tblHallBooking (user_id, hall_name, booking_date, booking_time, purpose, payment_amount, payment_status) VALUES ('$user_id', '$hall_name', '$booking_date', '$booking_time', '$purpose', '$payment_amount', '$payment_status')";
        $sql = "INSERT INTO tblComplaints (user_id, complaint_text, complaint_date) VALUES ('$user_id', '$complaint_text', NOW())";
        $insert_expense_sql = "INSERT INTO tblExpenses (user_id, amount, name, date) VALUES ('{$_SESSION['user_id']}', '$expense_amount', '$expense_name', '$expense_date')";
        
        describe how to make a graph ( for apartment system) what in x-bar , what in y-bar
        // Fetch total expenses from maintenance table
        $maintenance_sql = "SELECT SUM(amount) AS total FROM tblMaintenance";
        // Fetch total expenses from hall_booking table
        $hall_booking_sql = "SELECT SUM(payment_amount) AS total FROM tblHallBooking";
        // Calculate total amount
        $total_amount = $total_maintenance + $total_hall_booking;
        // Fetch total additional expenses
        $total_expenses_sql = "SELECT SUM(amount) AS total FROM tblExpenses WHERE user_id = '$user_id'";
        $remaining_amount = $total_amount - $total_expenses;
    </body>
</html>



<?php
session_start();
require 'dbFile/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<script>alert("You must be logged in to book a hall."); window.location.href="login.php";</script>';
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submissions for adding, updating, and deleting hall bookings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'] ?? null;
    $hall_name = mysqli_real_escape_string($conn, $_POST['hall_name'] ?? '');
    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date'] ?? '');
    $booking_time = mysqli_real_escape_string($conn, $_POST['booking_time'] ?? '');
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose'] ?? '');
    $payment_amount = mysqli_real_escape_string($conn, $_POST['payment_amount'] ?? 0);
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status'] ?? 'Pending');

    // Check if the hall is already booked
    $check_sql = "SELECT * FROM tblHallBooking WHERE hall_name = '$hall_name' AND booking_date = '$booking_date' AND booking_time = '$booking_time'";
    if ($booking_id) {
        $check_sql .= " AND booking_id != '$booking_id'";
    }
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        echo '<script>alert("The hall is already booked for the selected date and time.");</script>';
    } else {
        // Proceed with add or update
        if ($booking_id) {
            // Update existing booking
            $update_sql = "UPDATE tblHallBooking SET hall_name='$hall_name', booking_date='$booking_date', booking_time='$booking_time', purpose='$purpose', payment_amount='$payment_amount', "
                    . "payment_status='$payment_status' WHERE booking_id='$booking_id'";
            if (mysqli_query($conn, $update_sql)) {
                echo '<script>alert("Hall booking updated!");</script>';
            } else {
                echo '<script>alert("Error: ' . mysqli_error($conn) . '");</script>';
            }
        } else {
            // Insert new booking
            $insert_sql = "INSERT INTO tblHallBooking (user_id, hall_name, booking_date, booking_time, purpose, payment_amount, payment_status) VALUES"
                    . " ('$user_id', '$hall_name', '$booking_date', '$booking_time', '$purpose', '$payment_amount', '$payment_status')";
            if (mysqli_query($conn, $insert_sql)) {
                echo '<script>alert("Hall booking added!");</script>';
            } else {
                echo '<script>alert("Error: ' . mysqli_error($conn) . '");</script>';
            }
        }
    }

    // Delete Hall Booking
    if (isset($_POST['btnDelete'])) {
        $booking_id = $_POST['booking_id'] ?? null;
        $delete_sql = "DELETE FROM tblHallBooking WHERE booking_id='$booking_id'";

        if (mysqli_query($conn, $delete_sql)) {
            echo '<script>alert("Hall booking deleted!");</script>';
        } else {
            echo '<script>alert("Error: ' . mysqli_error($conn) . '");</script>';
        }
    }
}

// Fetch hall bookings based on user role
$role = $_SESSION['role'] ?? '';
if ($role === 'Secretary' || $role === 'Committee') {
    $sql = "SELECT * FROM tblHallBooking";
} else {
    $sql = "SELECT * FROM tblHallBooking WHERE user_id = '$user_id'";
}
$result = mysqli_query($conn, $sql);
$hall_bookings = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Hall Booking Management</title>
    </head>
    <body>
        <h4 name="formHeading" id="formHeading">Add Hall Booking</h4>
        <form method="post" id="bookingForm">
            <input type="hidden" id="booking_id" name="booking_id">

            <label for="hall_name">Hall Name:</label>
            <input type="text" id="hall_name" name="hall_name" required>

            <label for="booking_date">Booking Date:</label>
            <input type="date" id="booking_date" name="booking_date" required>

            <label for="booking_time">Booking Time:</label>
            <input type="time" id="booking_time" name="booking_time" required>

            <label for="purpose">Purpose:</label>
            <textarea id="purpose" name="purpose" required></textarea>

            <label for="payment_amount">Payment Amount:</label>
            <input type="number" step="0.01" id="payment_amount" name="payment_amount" required>

            <label for="payment_status">Payment Status:</label>
            <select id="payment_status" name="payment_status" required>
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>

            <input type="submit" id="formSubmitBtn" value="Add Booking">
        </form>

        <h4>Current Hall Bookings</h4>
        <?php if (!empty($hall_bookings)): ?>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Hall Name</th>
                    <th>Booking Date</th>
                    <th>Booking Time</th>
                    <th>Purpose</th>
                    <th>Payment Amount</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($hall_bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                        <td><?php echo htmlspecialchars($booking['hall_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                        <td><?php echo htmlspecialchars($booking['booking_time']); ?></td>
                        <td><?php echo htmlspecialchars($booking['purpose']); ?></td>
                        <td><?php echo htmlspecialchars($booking['payment_amount']); ?></td>
                        <td><?php echo htmlspecialchars($booking['payment_status']); ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                                <input type="submit" name="btnDelete" value="Delete">
                            </form>
                            <button onclick="populateUpdateForm('<?php echo htmlspecialchars($booking['booking_id']); ?>', '<?php echo htmlspecialchars($booking['hall_name']); ?>',
                                                    '<?php echo htmlspecialchars($booking['booking_date']); ?>', '<?php echo htmlspecialchars($booking['booking_time']); ?>', '<?php echo htmlspecialchars($booking['purpose']); ?>',
                                                    '<?php echo htmlspecialchars($booking['payment_amount']); ?>', '<?php echo htmlspecialchars($booking['payment_status']); ?>')">Update</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No hall bookings available.</p>
        <?php endif; ?>

        <script>
            function populateUpdateForm(booking_id, hall_name, booking_date, booking_time, purpose, payment_amount, payment_status) {
                document.getElementById('booking_id').value = booking_id;
                document.getElementById('hall_name').value = hall_name;
                document.getElementById('booking_date').value = booking_date;
                document.getElementById('booking_time').value = booking_time;
                document.getElementById('purpose').value = purpose;
                document.getElementById('payment_amount').value = payment_amount;
                document.getElementById('payment_status').value = payment_status;
                document.getElementById('formSubmitBtn').value = "Update Booking";
                document.getElementById('formHeading').innerText = "Update Hall Booking";
            }
        </script>
    </body>
</html>
