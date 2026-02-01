<?php
session_start();

if (isset($_POST['Register'])) {

    $otp = rand(100000, 999999);

    $_SESSION['otp'] = $otp;
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['password'] = md5($_POST['password']);
    $_SESSION['user_role'] = $_POST['user_role'];
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['contact'] = $_POST['contact'];
    $_SESSION['aadhar_no'] = $_POST['aadhar_no'];
    $_SESSION['wings'] = $_POST['wings'];
    $_SESSION['flat_no'] = $_POST['flat_no'];

    header("Location: regValidateEmail.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/registration.css">
</head>

<body>

    <div class="auth-wrapper">
        <div class="auth-card wide">

            <h2><i class="fa-solid fa-user-plus me-2"></i>Create Account</h2>
            <p class="subtitle">Register and verify using OTP</p>

            <form method="post">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select name="user_role" class="form-select" required>
                            <option value="">Select role</option>
                            <option value="admin">Admin</option>
                            <option value="committee member">Committee Member</option>
                            <option value="owner">Owner</option>
                            <option value="secretary">Secretary</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact</label>
                        <input type="text" name="contact" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Aadhar No</label>
                        <input type="text" name="aadhar_no" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Wing</label>
                        <select name="wings" class="form-select" required>
                            <option value="">Wing</option>
                            <option>A</option>
                            <option>B</option>
                            <option>C</option>
                            <option>D</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Flat No</label>
                        <input type="text" name="flat_no" class="form-control" required>
                    </div>

                </div>

                <button type="submit" name="Register" class="btn btn-success w-100 mt-4">
                    Register & Verify OTP
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="login.php">Already have an account? Login</a>
            </div>

        </div>
    </div>

</body>

</html>