<?php
session_start();
require 'dbFile/database.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>

        <!-- Bootstrap CSS -->
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

            .login-form {
                background-color: rgba(255, 255, 255, 0.85);
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                max-width: 400px;
                width: 100%;
            }

            .login-form h3 {
                margin-bottom: 20px;
                color: #333;
            }

            .login-form .form-label {
                font-weight: bold;
                color: #555;
            }

            .login-form .btn {
                background-color: #4CAF50;
                color: #fff;
                font-size: 16px;
                padding: 12px;
                width: 100%;
                border-radius: 4px;
            }

            .login-form .btn:hover {
                background-color: #45a049;
            }

            .login-form a {
                color: #4CAF50;
            }

            .login-form a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>

        <div class="login-form">
            <h3 class="text-center">Login Here</h3>

            <form method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" pattern="^(?=.*[A-Z])(?=.*\d)(?=.*_)[A-Za-z\d_]{5,20}$" title="Username should be 5-20 characters long, contain (Xyz_012)" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" pattern=".{6,}" title="Password should be at least 6 characters long." required>
                </div>

                <button type="submit" name="btnLogin" class="btn">Log In</button>

                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="registration.php">Register</a></p>
                    <p>Forgot password? <a href="validateEmail.php">Click here</a></p>
                </div>
            </form>
        </div>

        <!-- Bootstrap JS and Popper.js -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

        <?php
        if (!empty($_SESSION['username']) && !empty($_SESSION['password'])) {
            header("location: dashboard.php");
        }

        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = time();
        }
        $lockout_duration = 300;
        if ($_SESSION['login_attempts'] >= 3) {
            if (time() - $_SESSION['last_attempt_time'] < $lockout_duration) {
                $time_left = $lockout_duration - (time() - $_SESSION['last_attempt_time']);
                echo '<script>alert("Too many failed login attempts. Please try again in ' . ceil($time_left / 60) . ' minutes.");</script>';
                exit();
            } else {
                $_SESSION['login_attempts'] = 0;
            }
        }

        if (isset($_POST['btnLogin'])) {
            $username = $_POST['username'];
            $password = md5($_POST['password']);

            $sql = "select * from tblUser where username = '$username' and password = '$password'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                $row = mysqli_fetch_assoc($result);
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_role'] = $row['user_role'];
                $_SESSION['login_attempts'] = 0;
                setcookie('username', $username, time() + (86400 * 30), "/");
                header('Location: dashboard.php');
                exit();
            } else {
                $_SESSION['login_attempts']++;
                $_SESSION['last_attempt_time'] = time();
                echo '<script>alert("Invalid username or password");</script>';
            }

            mysqli_free_result($result);
            mysqli_close($conn);
        }
        ?>

    </body>
</html>
