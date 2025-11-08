<?php
require 'dbFile/database.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
           
        }
        .containerVu {
            max-width: 90%;
            margin: 0 auto;
        }

        .table {
            margin-top: 20px;
        }

        .table th,
        .table td {
            text-align: center;
        }

        .form-group {
            max-width: 300px;
            margin-bottom: 20px;
        }

        .search-result {
            margin-top: 20px;
        }

        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .form-control {
            border-radius: 25px;
            padding: 10px;
        }

        .btn-primary {
            border-radius: 20px;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }
    </style>

    <script>
        function getdata(user_id) {
            var a = new XMLHttpRequest();
            a.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    document.getElementById("user_id").innerHTML = this.responseText;
                }
            };
            a.open("get", "viewUser.php?user_id=" + user_id, true);
            a.send();
        }
    </script>
</head>

<body>

    <div class="containerVu">
        <div class="card">
            <div class="card-header">
                <h3>User Details</h3>
            </div>
            <div class="card-body">
                <form class="form-inline">
                    <div class="form-group">
                        <label for="txtid" class="mr-2">Search by Username:</label>
                        <input type="text" class="form-control" id="txtid" onkeyup="getdata(this.value)" placeholder="Enter username">
                    </div>
                </form>

                <div id="user_id" class="search-result">
                    <?php
                    $sql = "SELECT * FROM tblUser";
                    $r = mysqli_query($conn, $sql);
                    echo "<table class='table table-bordered table-striped'>";
                    echo "<thead class='thead-dark'>";
                    echo "<tr>";
                    echo "<th>Username</th>";
                    echo "<th>Email</th>";
                    echo "<th>User Role</th>";
                    echo "<th>Name</th>";
                    echo "<th>Contact</th>";
                    echo "<th>Wings</th>";
                    echo "<th>Flat No</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($q = mysqli_fetch_assoc($r)) {
                        echo "<tr>";
                        echo "<td>" . $q['username'] . "</td>";
                        echo "<td>" . $q['email'] . "</td>";
                        echo "<td>" . $q['user_role'] . "</td>";
                        echo "<td>" . $q['name'] . "</td>";
                        echo "<td>" . $q['contact'] . "</td>";
                        echo "<td>" . $q['wings'] . "</td>";
                        echo "<td>" . $q['flat_no'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
