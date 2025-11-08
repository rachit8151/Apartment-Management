<?php 
session_start();
require 'dbFile/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('img/ApartmentBG.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .form-label {
            font-weight: bold;
            color: #555;
        }

        .form-control {
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 15px;
            font-size: 16px;
            text-align: center;
        }

        .message p {
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Change Your Password</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="oldpassword" class="form-label">Old Password</label>
            <input type="password" class="form-control" name="oldpassword" required maxlength="18" placeholder="Enter old password">
        </div>

        <div class="mb-3">
            <label for="newpassword" class="form-label">New Password</label>
            <input type="password" class="form-control" name="newpassword" required maxlength="18" placeholder="Enter new password">
        </div>

        <div class="mb-3">
            <label for="conpassword" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" name="conpassword" required maxlength="18" placeholder="Confirm new password">
        </div>

        <div class="mb-3">
            <input type="submit" name="btnReset" class="btn btn-primary" value="Reset Password">
        </div>
    </form>

    <?php
    if (isset($_POST['btnReset'])) {
        $oldPassword = $_POST['oldpassword'];
        $oldPassword = md5($oldPassword);

        $newPassword = $_POST['newpassword'];
        $confirmPassword = $_POST['conpassword'];

        $sql = "SELECT password FROM tblUser WHERE password = '$oldPassword'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Old password is correct
            if ($newPassword != $confirmPassword) {
                echo '<div class="message text-danger">New password and confirm password do not match.</div>';
            } else {
                $newPassword = md5($newPassword);
                $username = $_SESSION['username'];
                $sql = "UPDATE tblUser SET password = '$newPassword' WHERE username = '$username'";

                $result = mysqli_query($conn, $sql);
                if ($result) {
                    echo '<div class="message text-success">Password successfully updated.</div>';
                    header("location: dashboard.php");
                    exit();
                } else {
                    echo '<div class="message text-danger">Failed to update password. Please try again.</div>';
                }
            }
        } else {
            echo '<div class="message text-danger">Invalid old password. Please try again.</div>';
        }
    }

    mysqli_close($conn);
    ?>

</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
