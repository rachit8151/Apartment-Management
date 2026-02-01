<?php 
session_start();
require 'dbFile/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/password.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">

        <h2 class="mb-1">
            <i class="fa-solid fa-key me-2 text-success"></i>
            Change Password
        </h2>
        <p class="subtitle">Update your account security</p>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Old Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password"
                           class="form-control"
                           name="oldpassword"
                           required
                           placeholder="Enter old password">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fa-solid fa-lock-open"></i>
                    </span>
                    <input type="password"
                           class="form-control"
                           name="newpassword"
                           required
                           placeholder="Enter new password">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Confirm New Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    <input type="password"
                           class="form-control"
                           name="conpassword"
                           required
                           placeholder="Confirm new password">
                </div>
            </div>

            <button type="submit" name="btnReset" class="btn btn-success w-100">
                <i class="fa-solid fa-rotate-right me-1"></i>
                Update Password
            </button>
        </form>

        <?php
        if (isset($_POST['btnReset'])) {
            $oldPassword = md5($_POST['oldpassword']);
            $newPassword = $_POST['newpassword'];
            $confirmPassword = $_POST['conpassword'];

            $check = mysqli_query($conn, "SELECT password FROM tblUser WHERE password='$oldPassword'");

            if (mysqli_num_rows($check) > 0) {
                if ($newPassword !== $confirmPassword) {
                    echo '<div class="alert alert-danger mt-3">Passwords do not match</div>';
                } else {
                    $newPassword = md5($newPassword);
                    $username = $_SESSION['username'];
                    mysqli_query($conn, "UPDATE tblUser SET password='$newPassword' WHERE username='$username'");
                    header("location: dashboard.php");
                    exit();
                }
            } else {
                echo '<div class="alert alert-danger mt-3">Old password is incorrect</div>';
            }
        }
        ?>

    </div>
</div>

</body>
</html>
