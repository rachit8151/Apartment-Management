<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validate OTP</title>
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
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
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
            border-radius: 5px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Validate OTP</h2>
    <form method="POST">
        <div class="form-group">
            <label for="otp" class="form-label">Enter OTP:</label>
            <input type="text" name="otp" class="form-control" required maxlength="6" placeholder="Enter OTP">
        </div>

        <div class="form-group">
            <button type="submit" name="btnVerifyOTP" class="btn btn-primary">Validate OTP</button>
        </div>
    </form>

    <?php
    if (isset($_POST['btnVerifyOTP'])) {
        $otp = $_POST['otp'];

        if ($_SESSION['otp'] == $otp) {
            // Redirect to the password change page
            header("Location: validatePassword.php");
            exit();
        } else {
            echo '<div class="message">Invalid OTP. Please try again.</div>';
        }
    }
    ?>
</div>

<!-- Bootstrap 5 JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
