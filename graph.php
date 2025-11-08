<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Overview</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
        }

        .container {
            margin-top: 40px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .card-body {
            text-align: center;
        }

        .chart-container {
            margin-top: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .legend-container {
            margin-top: 20px;
        }

        .legend-item {
            display: inline-block;
            margin-right: 15px;
            font-size: 16px;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            display: inline-block;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="chart-container">
                <canvas id="expensesChart" style="width:100%; height: 100%"></canvas> <!-- Responsive Chart -->
            </div>
            <div class="legend-container">
                <div class="legend-item">
                    <span class="legend-color" style="background-color: rgba(54, 162, 235, 0.2);"></span> Maintenance
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: rgba(75, 192, 192, 0.2);"></span> Hall Booking
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: rgba(255, 99, 132, 0.2);"></span> Additional Expenses
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script>
    // Data for the chart
    var ctx = document.getElementById('expensesChart').getContext('2d');
    var expensesChart = new Chart(ctx, {
        type: 'pie', // Pie chart type
        data: {
            labels: ['Maintenance', 'Hall Booking', 'Additional Expenses'], // Labels for each section
            datasets: [{
                label: 'Total Expenses (%)',
                data: [
                    <?php echo round($percent_maintenance, 2); ?>,
                    <?php echo round($percent_hall_booking, 2); ?>,
                    <?php echo round($percent_expenses, 2); ?>
                ], // Data for each section
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)', // Blue for Maintenance
                    'rgba(75, 192, 192, 0.2)', // Green for Hall Booking
                    'rgba(255, 99, 132, 0.2)'   // Red for Expenses
                ], // Colors for each section
                borderColor: [
                    'rgba(54, 162, 235, 1)', // Blue border for Maintenance
                    'rgba(75, 192, 192, 1)', // Green border for Hall Booking
                    'rgba(255, 99, 132, 1)'   // Red border for Expenses
                ], // Border colors
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            // Get the total values from PHP
                            var category = context.label; // Get the label (category)
                            var percentage = context.raw.toFixed(2); // Get the percentage

                            // Assign the appropriate total value for each category
                            var totalValue = 0;
                            if (category === 'Maintenance') {
                                totalValue = <?php echo round($total_maintenance, 2); ?>;
                            } else if (category === 'Hall Booking') {
                                totalValue = <?php echo round($total_hall_booking, 2); ?>;
                            } else if (category === 'Additional Expenses') {
                                totalValue = <?php echo round($total_expenses, 2); ?>;
                            }

                            // Return the formatted label with both percentage and total amount
                            return category + ': ' + totalValue.toFixed(2) + ' (' + percentage + '%)';
                        }
                    }
                },
                legend: {
                    position: 'top', // Position of the legend
                }
            }
        }
    });
</script>

<!-- Bootstrap JS & dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
