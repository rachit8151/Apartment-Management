<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Validate OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
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
            font-size: 15px;
            text-align: center;
            letter-spacing: 3px;
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

        .alert {
            font-size: 14px;
            border-radius: 8px;
        }

        .info-text {
            font-size: 14px;
            color: #6b7280;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="auth-card">

    <h3 class="auth-title">
        <i class="fa-solid fa-shield-halved me-2"></i>
        OTP Verification
    </h3>

    <p class="info-text">
        Enter the 6-digit OTP sent to your registered email
    </p>

    <form method="POST">

        <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-key"></i>
                </span>
                <input type="text"
                       name="otp"
                       class="form-control"
                       placeholder="••••••"
                       maxlength="6"
                       required>
            </div>
        </div>

        <div class="d-grid mt-3">
            <button type="submit" name="btnVerifyOTP" class="btn btn-primary">
                <i class="fa-solid fa-check me-1"></i>
                Verify OTP
            </button>
        </div>
    </form>

    <?php
    if (isset($_POST['btnVerifyOTP'])) {

        $otp = trim($_POST['otp']);

        if (isset($_SESSION['otp']) && $_SESSION['otp'] == $otp) {
            header("Location: validatePassword.php");
            exit();
        } else {
            echo '<div class="alert alert-danger mt-3 text-center">
                    Invalid OTP. Please try again.
                  </div>';
        }
    }
    ?>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
