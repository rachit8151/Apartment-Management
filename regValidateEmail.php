<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Validation</title>

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
        }

        .validation-form {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .validation-form h2 {
            margin-bottom: 30px;
            color: #333;
            text-align: center;
        }

        .form-label {
            font-weight: bold;
            color: #555;
        }

        .form-control {
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #4CAF50;
            border: none;
            color: #fff;
            font-size: 16px;
            padding: 12px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        .text-center p {
            font-size: 16px;
            color: #555;
        }

        .text-center a {
            color: #4CAF50;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="validation-form">
        <h2>Email Validation</h2>

        <form method="POST">
            <div class="mb-3">
                <label for="otp" class="form-label">Enter OTP</label>
                <input type="text" class="form-control" name="otp" required maxlength="6" placeholder="Enter the OTP sent to your email">
            </div>

            <div class="mb-3">
                <input type="submit" name="verify" class="btn btn-primary" value="Verify">
            </div>
        </form>

        <?php
        session_start();
        if (isset($_POST['verify'])) {
            $otp = $_POST['otp'];
            if ($_SESSION['otp'] == $otp) {
                require 'dbFile/database.php';

                $email = $_SESSION['email'];
                $username = $_SESSION['username'];
                $user_role = $_SESSION['user_role'];
                $password = $_SESSION['password'];
                $name = $_SESSION['name'];
                $contact = $_SESSION['contact'];
                $aadhar_no = $_SESSION['aadhar_no'];
                $wings = $_SESSION['wings'];
                $flat_no = $_SESSION['flat_no'];

                $query = "INSERT INTO tblUser (username, email, password, user_role, name, contact, aadhar_no, wings, flat_no) "
                         . "VALUES ('$username', '$email', '$password', '$user_role', '$name', $contact, $aadhar_no, '$wings', $flat_no)";

                $result = mysqli_query($conn, $query);
                if ($result) {
                    echo "<p class='text-center text-success'>New record created successfully!</p>";
                    session_unset();
                    session_destroy();
                    header("Location: login.php");
                } else {
                    echo "<p class='text-center text-danger'>Error: " . mysqli_error($conn) . "</p>";
                }

                mysqli_close($conn);
            } else {
                echo "<p class='text-center text-danger'>Invalid OTP. Please try again.</p>";
            }
        }
        ?>

        <div class="text-center mt-3">
            <p>If you didn't receive the OTP, <a href="resendOtp.php">click here</a> to resend.</p>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    
</body>
</html>
