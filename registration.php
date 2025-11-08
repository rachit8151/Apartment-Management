<?php
session_start();
if (isset($_POST['Register'])) {
    $email = $_POST['email'];
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    $username = $_POST['username'];
    $user_role = $_POST['user_role'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $aadhar_no = $_POST['aadhar_no'];
    $wings = $_POST['wings'];
    $flat_no = $_POST['flat_no'];

    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;
    $_SESSION['user_role'] = $user_role;
    $_SESSION['password'] = $password;
    $_SESSION['name'] = $name;
    $_SESSION['contact'] = $contact;
    $_SESSION['aadhar_no'] = $aadhar_no;
    $_SESSION['wings'] = $wings;
    $_SESSION['flat_no'] = $flat_no;

    $subject = "Your OTP Code";
    $message = "Your OTP code is $otp";
    $headers = "From: rachit1575@gmail.com";

    if (mail($email, $subject, $message, $headers)) {
        header('Location: regValidateEmail.php');
    } else {
        echo "Failed to send OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration</title>

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

            .registration-form {
                background-color: rgba(255, 255, 255, 0.85);
                padding: 40px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                max-width: 600px;
                width: 100%;
            }

            .registration-form h2 {
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

            .form-select {
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

            .text-center a {
                color: #4CAF50;
            }

            .text-center a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>

        <div class="registration-form">
            <h2>Registration Form</h2>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="mb-3">
                    <label for="temp" class="form-label">Temp</label>
                    <input type="hidden" class="form-control" id="temp" name="temp" value="some_value">
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" pattern="^(?=.*[A-Z])(?=.*\d)(?=.*_)[A-Za-z\d_]{5,20}$" title="Username should be 5-20 characters long, contain (Xyz_012)" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" pattern=".{6,}" title="Password should be at least 6 characters long." required>
                </div>

                <div class="mb-3">
                    <label for="user_role" class="form-label">Select Role</label>
                    <select id="user_role" name="user_role" class="form-select" required>
                        <option value="">--Select--</option>
                        <option value="admin">Admin</option>
                        <option value="committee member">Committee Member</option>
                        <option value="owner">Owner</option>
                        <option value="secretary">Secretary</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" pattern="[A-Za-z ]{1,20}$" title="Name should contain only letters" required>
                </div>

                <div class="mb-3">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="text" class="form-control" id="contact" name="contact" pattern="\d{10}" title="Contact should contain exactly 10 digits and no other characters." required>
                </div>

                <div class="mb-3">
                    <label for="aadhar_no" class="form-label">Aadhar Number</label>
                    <input type="text" class="form-control" id="aadhar_no" name="aadhar_no" pattern="\d{12}" title="Aadhar Number should contain exactly 12 digits and no other characters." required>
                </div>

                <div class="mb-3">
                    <label for="wings" class="form-label">Wings</label>
                    <select id="wings" name="wings" class="form-select" required>
                        <option value="">--Select--</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="flat_no" class="form-label">Flat Number</label>
                    <input type="text" class="form-control" id="flat_no" name="flat_no" pattern="\d{1,4}" title="Flat number should contain only numbers between 1 to 4 digits long." required>
                </div>

                <button type="submit" name="Register" class="btn btn-primary">Register</button>
            </form>

            <div class="text-center mt-3">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>

        <!-- Bootstrap JS and Popper.js -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>



    </body>
</html>
