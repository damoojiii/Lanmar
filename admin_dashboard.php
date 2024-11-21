<?php
// Include the database connection
include("connection.php");

// Fetch report data from the database
$query = "SELECT MONTH(booking_date) as month, COUNT(*) as bookings, SUM(total_amount) as revenue 
          FROM bookings 
          WHERE YEAR(booking_date) = YEAR(CURDATE()) 
          GROUP BY MONTH(booking_date)";
$result = $conn->query($query);

$months = [];
$bookings = [];
$revenue = [];

while ($row = $result->fetch_assoc()) {
    $months[] = date("F", mktime(0, 0, 0, $row['month'], 10));
    $bookings[] = $row['bookings'];
    $revenue[] = $row['revenue'];
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Reports</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Booking and Revenue Reports</h2>
        <div class="row">
            <div class="col-md-6">
                <canvas id="bookingsChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Bookings Chart
        var ctxBookings = document.getElementById('bookingsChart').getContext('2d');
        var bookingsChart = new Chart(ctxBookings, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Number of Bookings',
                    data: <?php echo json_encode($bookings); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Bookings'
                    }
                }
            }
        });

        // Revenue Chart
        var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        var revenueChart = new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?php echo json_encode($revenue); ?>,
                    backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return '$' + value;
                            }
                        }
                    }
                },
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Revenue'
                    }
                }
            }
        });
    </script>
</body>
</html>
