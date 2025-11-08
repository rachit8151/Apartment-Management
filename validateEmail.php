<?php 
session_start();
require 'dbFile/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <title>Validate OTP</title>
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
            position: relative;
        }

        /* Create a blue tint overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.85);
            z-index: 0; /* Place behind the content */
        }

        /* Style the form container */
        .container {
            background-color: rgba(255, 255, 255, 0.9); /* White background with slight opacity */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
            position: relative;
            z-index: 1;
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

        .alert {
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Forgot Password</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" pattern="^(?=.*[A-Z])(?=.*\d)(?=.*_)[A-Za-z\d_]{5,20}$" 
                   title="Username should be 5-20 characters long, containing (Xyz_012)" required placeholder="Enter your username">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email address">
        </div>

        <div class="mb-3">
            <input type="submit" name="btnVerify" class="btn btn-primary" value="Send OTP">
        </div>
    </form>

    <?php
    if (isset($_POST['btnVerify'])) {
        $username = $_POST['username'];
        $_SESSION['username'] = $username;
        $email = $_POST['email'];
        $sql = "SELECT * FROM tblUser WHERE username = '$username' AND email = '$email'";

        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $otp = rand(100000, 999999); // Generate OTP
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            // Send OTP via email
            $subject = "Your OTP Code";
            $message = "Your OTP code is $otp";
            $headers = "From: no-reply@yourdomain.com";

            if (mail($email, $subject, $message, $headers)) {
                header('Location: validateOtp.php');
                exit();
            } else {
                echo '<div class="alert alert-danger">Failed to send OTP. Please try again.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Invalid username or email. Please check and try again.</div>';
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
