<?php 
session_start();
require 'dbFile/database.php';

if (isset($_POST['btnForget'])) {
    $password = $_POST['password'];
    $conpassword = $_POST['conpassword'];

    if ($password != $conpassword) {
        echo '<div class="message error">Passwords do not match</div>';
    } else {
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $password = md5($password);
        $sql = "UPDATE tblUser SET password = '$password' WHERE username = '$username' OR email = '$email'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo '<script>alert("Password is successfully updated")</script>';
            header("Location: login.php");
            exit();
        } else {
            echo '<script>alert("Failed to update password. Please check the username and role.");</script>';
        }
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgba(255, 255, 255, 0.85);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Slight transparency */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ccc;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background-color: #5cb85c;
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #4cae4c;
        }

        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
        }

        .message.error {
            color: red;
            background-color: #f8d7da;
        }

        .message.success {
            color: green;
            background-color: #d4edda;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Reset Your Password</h2>
    <form method="POST">
        <div class="form-group">
            <label for="password">New Password:</label>
            <input type="password" name="password" class="form-control" required maxlength="18" placeholder="Enter new password">
        </div>

        <div class="form-group">
            <label for="conpassword">Confirm New Password:</label>
            <input type="password" name="conpassword" class="form-control" required maxlength="18" placeholder="Confirm your new password">
        </div>

        <div class="form-group">
            <button type="submit" name="btnForget" class="btn btn-primary">Reset Password</button>
        </div>
    </form>

    <!-- Error or Success message -->
    <?php if(isset($message)) { echo $message; } ?>
</div>

<!-- Bootstrap 5 JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
