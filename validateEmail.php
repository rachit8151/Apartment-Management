<?php
session_start();
require 'dbFile/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1f2937, #0f172a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Poppins", Arial, sans-serif;
        }

        .auth-card {
            background: #fff;
            width: 100%;
            max-width: 420px;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-title {
            text-align: center;
            font-weight: 600;
            margin-bottom: 25px;
            color: #1f2937;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 12px;
        }

        .input-group-text {
            background: #f1f5f9;
            border-radius: 10px 0 0 10px;
        }

        .btn-primary {
            background: #2563eb;
            border: none;
            border-radius: 10px;
            padding: 10px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: #1e40af;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
        }

        .back-link a {
            text-decoration: none;
            color: #2563eb;
            font-weight: 500;
        }

        .alert {
            font-size: 14px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="auth-card">

    <h3 class="auth-title">
        <i class="fa-solid fa-key me-2"></i>
        Forgot Password
    </h3>

    <form method="POST">

        <!-- Username -->
        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-user"></i>
                </span>
                <input type="text"
                       class="form-control"
                       name="username"
                       placeholder="Enter your username"
                       pattern="^(?=.*[A-Z])(?=.*\d)(?=.*_)[A-Za-z\d_]{5,20}$"
                       title="Username format: Xyz_012"
                       required>
            </div>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-envelope"></i>
                </span>
                <input type="email"
                       class="form-control"
                       name="email"
                       placeholder="Enter your registered email"
                       required>
            </div>
        </div>

        <!-- Submit -->
        <div class="d-grid mt-3">
            <button type="submit" name="btnVerify" class="btn btn-primary">
                <i class="fa-solid fa-paper-plane me-1"></i>
                Send OTP
            </button>
        </div>
    </form>

    <?php
    if (isset($_POST['btnVerify'])) {

        $username = $_POST['username'];
        $email = $_POST['email'];

        $_SESSION['username'] = $username;

        $sql = "SELECT * FROM tblUser WHERE username='$username' AND email='$email'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {

            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            $subject = "Your OTP Code";
            $message = "Your OTP code is: $otp";
            $headers = "From: no-reply@yourdomain.com";

            if (mail($email, $subject, $message, $headers)) {
                header("Location: validateOtp.php");
                exit();
            } else {
                echo '<div class="alert alert-danger mt-3 text-center">
                        Failed to send OTP. Please try again.
                      </div>';
            }

        } else {
            echo '<div class="alert alert-danger mt-3 text-center">
                    Invalid username or email.
                  </div>';
        }
    }

    mysqli_close($conn);
    ?>

    <div class="back-link">
        <a href="login.php">
            <i class="fa-solid fa-arrow-left"></i> Back to Login
        </a>
    </div>

</div>

</body>
</html>
