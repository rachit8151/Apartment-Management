<?php 
session_start();
require 'dbFile/database.php';

// Check if user is logged in and get user data
if (!isset($_SESSION['user_id'])) {
    die('User ID parameter is missing.');
}
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tblUser WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}
$q = mysqli_fetch_assoc($result);
if (!$q) {
    die('Record not found.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile</title>
    <!-- Link to Bootstrap CSS for better design -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 16px;
            line-height: 1.5;
            background-color: #f8f9fa;
        }
        h2 {
            font-size: 28px;
            color: #495057;
        }
        table {
            font-size: 16px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Manage Profile</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td><label for="username">Username</label></td>
                    <td><input type="text" class="form-control" id="username" name="username" pattern="^(?=.*[A-Z])(?=.*\d)(?=.*_)[A-Za-z\d_]{5,20}$" 
                               title="Username should be 5-20 characters long, contain (Xyz_012)" 
                               value="<?php echo htmlspecialchars($q['username']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="email">Email</label></td>
                    <td><input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($q['email']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="user_role">Select Role</label></td>
                    <td>
                        <span class="form-control"><?php echo htmlspecialchars($q['user_role']); ?></span>
                        <input type="hidden" name="user_role" value="<?php echo htmlspecialchars($q['user_role']); ?>">
                    </td>
                </tr>
                <tr>
                    <td><label for="name">Name</label></td>
                    <td><input type="text" class="form-control" id="name" name="name" pattern="[A-Za-z ]{1,20}$"
                               title="Name should contain only letters" value="<?php echo htmlspecialchars($q['name']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="contact">Contact</label></td>
                    <td><input type="text" class="form-control" id="contact" name="contact" pattern="\d{10}" 
                               title="Contact should contain exactly 10 digits and no other characters." 
                               value="<?php echo htmlspecialchars($q['contact']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="aadhar_no">Aadhar Number</label></td>
                    <td>
                        <span class="form-control"><?php echo htmlspecialchars($q['aadhar_no']); ?></span>
                        <input type="hidden" name="aadhar_no" value="<?php echo htmlspecialchars($q['aadhar_no']); ?>">
                    </td>
                </tr>
                <tr>
                    <td><label for="wings">Wings</label></td>
                    <td>
                        <select class="form-control" id="wings" name="wings" required>
                            <option value="">--Select--</option>
                            <option value="A" <?php echo $q['wings'] == 'A' ? 'selected' : ''; ?>>A</option>
                            <option value="B" <?php echo $q['wings'] == 'B' ? 'selected' : ''; ?>>B</option>
                            <option value="C" <?php echo $q['wings'] == 'C' ? 'selected' : ''; ?>>C</option>
                            <option value="D" <?php echo $q['wings'] == 'D' ? 'selected' : ''; ?>>D</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="flat_no">Flat Number</label></td>
                    <td><input type="text" class="form-control" id="flat_no" name="flat_no" pattern="\d{1,4}" 
                               title="Flat number should contain only numbers between 1 to 4 digits long." 
                               value="<?php echo htmlspecialchars($q['flat_no']); ?>" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" name="btnupdate" class="btn btn-primary btn-block">Update Profile</button>
                    </td>
                </tr>
            </table>
        </div>
    </form>

    <?php
    if (isset($_POST['btnupdate'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $user_role = $_POST['user_role'];
        $name = $_POST['name'];
        $contact = $_POST['contact'];
        $aadhar_no = $_POST['aadhar_no'];
        $wings = $_POST['wings'];
        $flat_no = $_POST['flat_no'];

        $sql = "UPDATE tblUser SET username='$username', email='$email', name='$name', "
                . "contact='$contact', wings='$wings', flat_no='$flat_no' WHERE user_id = '$user_id'";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo '<div class="alert alert-success mt-3" role="alert">Profile updated successfully</div>';
            exit();
        } else {
            echo '<div class="alert alert-danger mt-3" role="alert">Failed to update: ' . mysqli_error($conn) . '</div>';
        }
    }
    ?>

</div>

<!-- Link to Bootstrap JS and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
