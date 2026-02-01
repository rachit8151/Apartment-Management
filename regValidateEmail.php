<?php
session_start();
require 'dbFile/database.php';

$message = "";

if (isset($_POST['verify'])) {

    if ($_POST['otp'] == $_SESSION['otp']) {

        $sql = "INSERT INTO tblUser 
        (username, email, password, user_role, name, contact, aadhar_no, wings, flat_no)
        VALUES (
            '{$_SESSION['username']}',
            '{$_SESSION['email']}',
            '{$_SESSION['password']}',
            '{$_SESSION['user_role']}',
            '{$_SESSION['name']}',
            '{$_SESSION['contact']}',
            '{$_SESSION['aadhar_no']}',
            '{$_SESSION['wings']}',
            '{$_SESSION['flat_no']}'
        )";

        if (mysqli_query($conn, $sql)) {
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit();
        } else {
            $message = "Database error!";
        }

    } else {
        $message = "Invalid OTP!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card mx-auto p-4" style="max-width:400px;">
        <h4 class="text-center mb-3">OTP Verification</h4>

        <!-- SHOW OTP FOR DEMO -->
        <div class="alert alert-warning text-center">
            OTP (for testing): <b><?php echo $_SESSION['otp']; ?></b>
        </div>

        <?php if ($message != "") { ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php } ?>

        <form method="post">
            <input type="text" name="otp" class="form-control mb-3" placeholder="Enter OTP" required>
            <button type="submit" name="verify" class="btn btn-success w-100">
                Verify OTP
            </button>
        </form>
    </div>
</div>

</body>
</html>
