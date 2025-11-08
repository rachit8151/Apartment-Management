<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Bootstrap CSS for styling -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles for dashboard -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .table-container {
            margin-top: 20px;
        }

        .table-container table {
            width: 100%;
            background-color: #0066ff;
            color: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .table-container th,
        .table-container td {
            padding: 15px;
            text-align: left;
        }

        .table-container td i {
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .table-container td i:hover {
            color: #f39c12;
        }

        h2 {
            font-size: 24px;
            color: white;
        }

        .notification-container {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Notification</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <h2>Announcement</h2>
                        </td>
                        <td>
                            <i class="fa fa-bell" aria-hidden="true" id="noti_announcement"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2>Event</h2>
                        </td>
                        <td>
                            <i class="fa fa-bell" aria-hidden="true" id="noti_event"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2>Hall Booking</h2>
                        </td>
                        <td>
                            <i class="fa fa-bell" aria-hidden="true" id="noti_hallbooking"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript to load notifications -->
    <script type="text/javascript">
        // Function to load notification data dynamically for each section
        function loadDoc(id, elementId) {
            setInterval(function () {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        document.getElementById(elementId).innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", "data.php?id=" + id, true);
                xhttp.send();
            }, 1000);
        }

        // Loading notification data for Announcement, Event, and Hall Booking
        loadDoc(1, "noti_announcement");
        loadDoc(2, "noti_event");
        loadDoc(3, "noti_hallbooking");
    </script>

</body>

</html>
