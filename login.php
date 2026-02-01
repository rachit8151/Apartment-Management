<?php
session_start();
require 'dbFile/database.php';

if (!empty($_SESSION['username']) && !empty($_SESSION['password'])) {
    header("location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <div class="login-wrapper">
        <div class="login-card">
            <h3 class="text-center mb-4 login-title">
                <i class="fa-solid fa-right-to-bracket login-icon"></i>
                Login Here
            </h3>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required
                        pattern="^(?=.*[A-Z])(?=.*\d)(?=.*_)[A-Za-z\d_]{5,20}$"
                        title="5-20 chars, must include capital letter, number and underscore">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required pattern=".{6,}"
                        title="Minimum 6 characters">
                </div>

                <button type="submit" name="btnLogin" class="btn btn-success w-100">
                    Login
                </button>

                <div class="text-center mt-3 small-links">
                    <p>Don't have an account?
                        <a href="registration.php">Register</a>
                    </p>
                    <p>
                        <a href="validateEmail.php">Forgot password?</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    // Login attempt control
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt_time'] = time();
    }

    $lockout_duration = 300;

    if ($_SESSION['login_attempts'] >= 3) {
        if (time() - $_SESSION['last_attempt_time'] < $lockout_duration) {
            $time_left = $lockout_duration - (time() - $_SESSION['last_attempt_time']);
            echo "<script>alert('Too many attempts. Try again in " . ceil($time_left / 60) . " minutes');</script>";
            exit();
        } else {
            $_SESSION['login_attempts'] = 0;
        }
    }

    // Login logic
    if (isset($_POST['btnLogin'])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        $sql = "SELECT * FROM tblUser WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_role'] = $row['user_role'];
            $_SESSION['login_attempts'] = 0;

            setcookie('username', $username, time() + (86400 * 30), "/");
            header("location: dashboard.php");
            exit();
        } else {
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            echo "<script>alert('Invalid username or password');</script>";
        }
    }
    ?>
</body>

</html>