<?php
session_start();
require 'dbFile/database.php';
// Get user role
$username = $_SESSION['username'];
$sqlUser = "SELECT user_role FROM tblUser WHERE username = '$username'";
$resultUser = mysqli_query($conn, $sqlUser);
$userData = mysqli_fetch_assoc($resultUser);
$user_role = $userData['user_role'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .containerUser {
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            text-align: center;
        }
        .table td form input[type="submit"] {
            background-color: #e74a3b;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .table td form input[type="submit"]:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="containerUser">
    <?php
    if ($user_role == 'admin') {
        $user_role = 'secretary';
        $sec = "SELECT * FROM tblUser WHERE user_role = '$user_role'";
        $result = mysqli_query($conn, $sec);
        ?>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>Secretary Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Aadhar No</th>
                            <th>Wings</th>
                            <th>Flat No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['contact']; ?></td>
                                <td><?php echo $row['aadhar_no']; ?></td>
                                <td><?php echo $row['wings']; ?></td>
                                <td><?php echo $row['flat_no']; ?></td>
                                <td>
                                    <form method="post" action="remove_user.php">
                                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                        <input type="submit" value="Remove">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    } elseif ($user_role == 'secretary') {
        $user_role = 'committee member';
        $cm = "SELECT * FROM tblUser WHERE user_role = '$user_role'";
        $result = mysqli_query($conn, $cm);
        ?>
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>Committee Member Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Aadhar No</th>
                            <th>Wings</th>
                            <th>Flat No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['contact']; ?></td>
                                <td><?php echo $row['aadhar_no']; ?></td>
                                <td><?php echo $row['wings']; ?></td>
                                <td><?php echo $row['flat_no']; ?></td>
                                <td>
                                    <form method="post" action="userRemove.php">
                                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                        <input type="submit" value="Remove">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    } elseif ($user_role == 'committee member') {
        $user_role = 'owner';
        $ow = "SELECT * FROM tblUser WHERE user_role = '$user_role'";
        $result = mysqli_query($conn, $ow);
        ?>
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5>Owner Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Aadhar No</th>
                            <th>Wings</th>
                            <th>Flat No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['contact']; ?></td>
                                <td><?php echo $row['aadhar_no']; ?></td>
                                <td><?php echo $row['wings']; ?></td>
                                <td><?php echo $row['flat_no']; ?></td>
                                <td>
                                    <form method="post" action="userRemove.php">
                                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                        <input type="submit" value="Remove">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    } else {
        echo "<div class='alert alert-warning'>No Record Available</div>";
    }
    ?>
</div>

<!-- Bootstrap JS & dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
