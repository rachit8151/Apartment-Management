<div class="card mt-4">
    <div class="card-body">
        <canvas id="expensesChart"></canvas>
    </div>
</div>

<script>
const ctx = document.getElementById('expensesChart');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Maintenance', 'Hall Booking', 'Expenses'],
        datasets: [{
            data: [
                <?= round($percent_maintenance,2) ?>,
                <?= round($percent_hall_booking,2) ?>,
                <?= round($percent_expenses,2) ?>
            ],
            backgroundColor: ['#36a2eb','#4bc0c0','#ff6384']
        }]
    }
});
</script>
