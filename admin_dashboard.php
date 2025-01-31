<?php
    include "role_access.php";
    include("connection.php");
    
    checkAccess('admin');

    $id = $_SESSION['user_id'];

    try {
        $name_sql = $pdo->prepare("SELECT firstname FROM users WHERE user_id = '$id'");
        $name_sql->execute();
        $name_data = $name_sql->fetch(PDO::FETCH_ASSOC);
        $name = $name_data['firstname'];

        $pending_stmt = $pdo->prepare("SELECT COUNT(*) AS pending_reservations 
        FROM booking_tbl 
        WHERE status = 'Pending' 
        AND MONTH(created_at) = MONTH(CURRENT_DATE) 
        AND YEAR(created_at) = YEAR(CURRENT_DATE)");
        $pending_stmt->execute();
        $pending_data = $pending_stmt->fetch(PDO::FETCH_ASSOC);
        $pending_reservations = $pending_data['pending_reservations'];

        $pending_stmt_prev = $pdo->prepare("
            SELECT COUNT(*) AS pending_reservations_prev 
            FROM booking_tbl 
            WHERE DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH), '%Y-%m')
        ");
        $pending_stmt_prev->execute();
        $pending_data_prev = $pending_stmt_prev->fetch(PDO::FETCH_ASSOC);
        $pending_reservations_prev = $pending_data_prev['pending_reservations_prev'];

        if ($pending_reservations_prev != 0) {
            $pending_percentage_change = (($pending_reservations - $pending_reservations_prev) / $pending_reservations_prev) * 100;
        } else {
            $pending_percentage_change = 0;
        }

        $incoming_stmt = $pdo->prepare("SELECT COUNT(*) AS incoming_books FROM booking_tbl WHERE WEEK(dateIn) = WEEK(CURDATE()) AND YEAR(dateIn) = YEAR(CURDATE()) AND status = 'Approved'");
        $incoming_stmt->execute();
        $incoming_data = $incoming_stmt->fetch(PDO::FETCH_ASSOC);
        $incoming_books = $incoming_data['incoming_books'];
        
        $incoming_stmt_prev = $pdo->prepare("
            SELECT COUNT(*) AS incoming_books_prev 
            FROM booking_tbl 
            WHERE YEARWEEK(dateIn, 1) = YEARWEEK(DATE_SUB(CURDATE(), INTERVAL 1 WEEK), 1) 
            AND status = 'Approved'
        ");
        $incoming_stmt_prev->execute();
        $incoming_data_prev = $incoming_stmt_prev->fetch(PDO::FETCH_ASSOC);
        $incoming_books_prev = $incoming_data_prev['incoming_books_prev'];

        if ($incoming_books_prev != 0) {
            $incoming_percentage_change = (($incoming_books - $incoming_books_prev) / $incoming_books_prev) * 100;
        } else {
            $incoming_percentage_change = 0;
        }

        $earnings_stmt = $pdo->prepare("
            SELECT SUM(bill_tbl.total_bill) AS weekly_earnings 
            FROM booking_tbl 
            LEFT JOIN bill_tbl 
            ON booking_tbl.bill_id = bill_tbl.bill_id 
            WHERE WEEK(booking_tbl.dateIn) = WEEK(CURDATE()) 
            AND YEAR(booking_tbl.dateIn) = YEAR(CURDATE()) 
            AND booking_tbl.status = 'Completed'
        ");
        $earnings_stmt->execute();
        $earnings_data = $earnings_stmt->fetch(PDO::FETCH_ASSOC);
        $weekly_earnings = $earnings_data['weekly_earnings'];

        $earnings_stmt_prev = $pdo->prepare("SELECT SUM(bill_tbl.total_bill) AS weekly_earnings_prev FROM booking_tbl LEFT JOIN bill_tbl ON booking_tbl.bill_id = bill_tbl.bill_id WHERE WEEK(booking_tbl.dateIn) = WEEK(CURDATE() - INTERVAL 1 WEEK) AND YEAR(booking_tbl.dateIn) = YEAR(CURDATE() - INTERVAL 1 WEEK) AND booking_tbl.status = 'Completed'");
        $earnings_stmt_prev->execute();
        $earnings_data_prev = $earnings_stmt_prev->fetch(PDO::FETCH_ASSOC);
        $weekly_earnings_prev = $earnings_data_prev['weekly_earnings_prev'];

        if ($weekly_earnings_prev != 0) {
            $weekly_earnings_percentage_change = (($weekly_earnings - $weekly_earnings_prev) / $weekly_earnings_prev) * 100;
        } else {
            $weekly_earnings_percentage_change = 0;
        }

        $cancellation_stmt = $pdo->prepare("SELECT COUNT(*) AS upcoming_cancellation FROM cancel_tbl WHERE MONTH(timestamp) = MONTH(CURRENT_DATE) AND YEAR(timestamp) = YEAR(CURRENT_DATE)");
        $cancellation_stmt->execute();
        $cancellation_data = $cancellation_stmt->fetch(PDO::FETCH_ASSOC);
        $cancellation_reservations = $cancellation_data['upcoming_cancellation'];

        $cancellation_stmt_prev = $pdo->prepare("SELECT COUNT(*) AS upcoming_cancellation_prev FROM cancel_tbl WHERE MONTH(timestamp) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND YEAR(timestamp) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)");
        $cancellation_stmt_prev->execute();
        $cancellation_data_prev = $cancellation_stmt_prev->fetch(PDO::FETCH_ASSOC);
        $cancellation_reservations_prev = $cancellation_data_prev['upcoming_cancellation_prev'];

        if ($cancellation_reservations_prev != 0) {
            $cancellation_percentage_change = (($cancellation_reservations - $cancellation_reservations_prev) / $cancellation_reservations_prev) * 100;
        } else {
            $cancellation_percentage_change = 0;
        }

        $sql = "SELECT YEAR(dateIn) AS year, MONTH(dateIn) AS month, SUM(p.adult + p.child + p.pwd) AS totalPax, b.status
        FROM booking_tbl b
        JOIN pax_tbl p ON b.pax_id = p.pax_id
        WHERE b.status NOT IN ('Pending', 'Cancelled', 'Rejected')
        GROUP BY year, month
        ORDER BY year, month";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $bookingData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql3 = "SELECT DISTINCT YEAR(created_at) AS year FROM booking_tbl ORDER BY year DESC";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute();
        $years = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        $sql4 = "SELECT DISTINCT YEAR(created_at) AS year FROM feedback_tbl ORDER BY year DESC";
        $stmt4 = $pdo->prepare($sql4);
        $stmt4->execute();
        $years1 = $stmt4->fetchAll(PDO::FETCH_ASSOC);


    
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanmar Resort</title>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/fontawesome.min.css">
    
    <style>
        @font-face {
            font-family: 'nautigal';
            src: url(font/TheNautigal-Regular.ttf);
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }
        *, p{
            margin: 0;
        }
        body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        }

        #sidebar span{
            font-family: 'nautigal';
            font-size: 50px !important;
        }

        .font-logo-mobile{
            font-family: 'nautigal';
            font-size: 30px;
        }

        #sidebar {
            width: 250px;
            position: fixed;
            top: 0; 
            height: 100vh;
            overflow-y: auto; 
            transition: transform 0.3s ease;
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
            z-index: 199;
        }

        header {
            position: none;
            top: 0;
            left: 0;
            right: 0; 
            width: calc(100% - 250px);
            height: 50px;
            transition: margin-left 0.3s ease;
            align-items: center;
            display: flex;  /* Smooth transition for header */
        }

        #hamburger {
            border: none;
            background: none;
            cursor: pointer;
            margin-left: 15px; /* Space from the left edge */
            display: none; /* Initially hide the hamburger button */
        }

        #main-content {
            transition: margin-left 0.3s ease;
            margin-left: 250px; 
            max-width: 80%;
        }

        hr {
            background-color: #ffff;
            height: 1.5px;
        }

        #sidebar .nav-link {
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            margin-bottom: 2px;
        }

        #sidebar .collapse {
            transition: height 0.3s ease-out, opacity 0.3s ease-out;
        }
        #sidebar .collapse.show {
            height: auto !important;
            opacity: 1;
        }
        #sidebar .collapse:not(.show) {
            height: 0;
            opacity: 0;
            overflow: hidden;
        }
        #sidebar .drop{
            height: 50px;
        }
        .caret-icon .fa-caret-down {
            display: inline-block;
            font-size: 20px;
        }
        .navcircle{
            font-size: 7px;
            text-align: justify;
        }

        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            background-color: #fff !important;
            color: #000 !important;
        }

        .dropdown-menu {
            width: 100%;
            background-color: #001A3E;
        }

        .dropdown-item {
            color: #fff !important;
            margin-bottom: 10px;
        }

        .dropdown-item:hover{
            background-color: #fff !important;
            color: #000 !important;
        }

        .header {
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
            color: white;
            padding: 20px;
        }

        .stats-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%; /* Ensures full height within the flex parent */
        }
        .stats-card h5{
            font-size: 1.2rem;
        }
        .chart-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            height: 100%;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chart-body {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .container-fluid{
            margin-inline: 10px ;
        }
        #paxChart{
            height: 100% !important;
            width: 100% !important;
        }
    @media (max-width: 768px) {
        #sidebar {
            position: fixed;
            transform: translateX(-100%);
            z-index: 199;
        }

        #sidebar.show {
            transform: translateX(0); /* Show sidebar */
        }

        #header.shifted{
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        #main-content {
            margin-inline: 10px; 
            padding: 0;
            max-width: 90%;
        }

        #hamburger {
            display: block; /* Show hamburger button on smaller screens */
        }
        .container {
            max-width: 100%;
        }
        .container-fluid{
            margin-inline: 5px;
            padding: 0;
        }

        #header{
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
            padding: 15px;
            margin: 0;
            width: 100%;
            position: fixed;
        }
        #header span{
            display: block;
        }
        #header.shifted .font-logo-mobile{
            display: none;
        }
        .header{ 
            width: 100%;
            margin-inline: auto;
        }
        .stats{
            display: block !important;
        }
        .stats-card {
            padding: 15px;
        }

        .chart-container {
            padding: 15px;
        }


    }
    </style>
</head>
<body>
    <!-- Header -->
    <header id="header" class="bg-light shadow-sm">
        <button id="hamburger" class="btn btn-primary">
            ☰
        </button>
        <span class="text-white ms-3 font-logo-mobile">Lanmar Resort</span>
    </header>

    <!-- Sidebar -->
    <div id="sidebar" class="d-flex flex-column p-3 text-white vh-100">
        <a href="#" class="mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="font-logo">Lanmar Resort</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="admin_dashboard.php" class="nav-link active text-white">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white d-flex justify-content-between align-items-center p-2 drop" href="#manageReservations" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="manageReservations">
                    Manage Reservations
                    <span class="caret-icon">
                        <i class="fa-solid fa-caret-down"></i>
                    </span>
                </a>
                <ul class="collapse list-unstyled ms-3" id="manageReservations">
                    <li><a class="nav-link text-white" href="pending_reservation.php">Pending Reservations</a></li>
                    <li><a class="nav-link text-white" href="approved_reservation.php">Approved Reservations</a></li>
                </ul>
            </li>
            <li>
                <a href="admin_notifications.php" class="nav-link text-white">Notifications</a>
            </li>
            <li>
                <a href="admin_home_chat.php" class="nav-link text-white">Chat with Customer</a>
            </li>
            <li>
                <a href="reservation_history.php" class="nav-link text-white">Reservation History</a>
            </li>
            <li>
                <a href="feedback.php" class="nav-link text-white">Guest Feedback</a>
            </li>
            <li>
                <a href="cancellationformtbl.php" class="nav-link text-white">Cancellations</a>
            </li>
            <li>
                <a href="account_lists.php" class="nav-link text-white">Account List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white d-flex justify-content-between align-items-center drop" href="#settingsCollapse" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="settingsCollapse">
                    Settings
                    <span class="caret-icon">
                        <i class="fa-solid fa-caret-down"></i>
                    </span>
                </a>
                <ul class="collapse list-unstyled ms-3" id="settingsCollapse">
                    <li><a class="nav-link text-white" href="account_settings.php">Account Settings</a></li>
                    <li><a class="nav-link text-white" href="homepage_settings.php">Content Manager</a></li>
                </ul>
            </li>
        </ul>
        <hr>
        <div class="logout">
            <a href="logout.php" class="nav-link text-white">Log out</a>
        </div>
    </div>

    <div id="main-content" class="container mt-1">
        <div class="container-fluid">
        <!-- Header Section -->
        <div class="row">
            <div class="col-12 header">
                <h2>Welcome, <?php echo ucwords($name);?>!</h2>
                <p>You have 24 new messages and 5 new notifications.</p>
            </div>
        </div>

        <!-- Statistics Cards Section -->
        <div class="row mt-4">
            <div class="stats col-md-3 col-sm-12 col-12 mb-4 d-flex align-items-stretch">
                <div class="stats-card">
                    <h5>Pending Reservations</h5>
                    <h2><?php echo number_format($pending_reservations); ?></h2>
                    <p><?php echo ($pending_percentage_change >= 0 ? '+' : '') . number_format($pending_percentage_change, 2) . '% compared to last month';  ?></p>
                </div>
            </div>
            <div class="stats col-md-3 col-sm-12 col-12 mb-4 d-flex align-items-stretch">
                <div class="stats-card">
                    <h5>Incoming Confirmed Books this Week</h5>
                    <h3><?php echo number_format($incoming_books); ?></h3>
                    <p><?php echo ($incoming_percentage_change >= 0 ? '+' : '-') . number_format(abs($incoming_percentage_change), 2) . '% compared to last month'; ?></p>
                </div>
            </div>
            <div class="stats col-md-3 col-sm-12 col-12 mb-4 d-flex align-items-stretch">
                <div class="stats-card">
                    <h5>Weekly Earnings</h5>
                    <h3>₱ <?php echo number_format($weekly_earnings); ?></h3>
                    <p><?php echo ($weekly_earnings_percentage_change >= 0 ? '+' : '-') . number_format(abs($weekly_earnings_percentage_change), 2) . '% compared to last week'; ?></p>
                </div>
            </div>
            <div class="stats col-md-3 col-sm-12 col-12 mb-4 d-flex align-items-stretch">
                <div class="stats-card">
                    <h5>Upcoming Cancellations</h5>
                    <h2><?php echo number_format($cancellation_reservations);  ?></h2>
                    <p><?php echo ($cancellation_percentage_change >= 0 ? '+' : '-') . number_format(abs($cancellation_percentage_change), 2) . '% compared to last month'; ?></p>
                </div>
            </div>
        </div>

        <!-- Charts and Widgets Section -->
        <div class="row mt-4">
            <div class="col-md-6 col-12 mb-4">
                <div class="chart-container d-flex flex-column">
                    <div class="chart-header mb-3">
                        <h5 id="chart-title" class="mb-0">Monthly Earnings</h5>
                        <select id="earnings-filter" class="form-select form-select-sm w-25">
                            <option value="yearly">Yearly</option>
                            <option value="monthly" selected>Monthly</option>
                        </select>
                    </div>
                    <div class="chart-body flex-grow-1">
                        <canvas id="Earnings"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12 mb-4">
                <div class="chart-container d-flex flex-column">
                    <div class="chart-header mb-3">
                        <h5 class="mb-0">Pax Chart</h5>
                        <div class="d-flex justify-content-end">
                            <select id="yearSelect" class="form-select form-select-sm"></select>
                        </div>
                    </div>
                    <div class="chart-body flex-grow-1">
                        <canvas id="paxChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4 col-12 mb-4">
                <div class="chart-container d-flex flex-column">
                    <div class="d-flex justify-content-end">
                        <select id="yearSelectorRoom" class="form-select mb-4">
                            <?php 
                            foreach ($years as $year) {
                                echo "<option value='{$year['year']}'>{$year['year']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="chart-body flex-grow-1">
                        <canvas id="revenueChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12 mb-4">
                <div class="chart-container d-flex flex-column">
                    <div class="chart-header mb-3">
                        <h5 class="mb-0">Feedback Rating</h5>
                        <div class="d-flex justify-content-end">
                            <select id="yearSelectorFeedback" class="form-control">
                                <?php 
                                foreach ($years1 as $year) {
                                    echo "<option value='{$year['year']}'>{$year['year']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="chart-body flex-grow-1">
                        <canvas id="feedbackPieChart" width="200px" height="100px"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12 mb-4">
                <div class="chart-container d-flex flex-column">
                    <div class="d-flex justify-content-end">
                        <select id="yearSelectorBook" class="form-select mb-4">
                            <?php 
                            foreach ($years as $year) {
                                echo "<option value='{$year['year']}'>{$year['year']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="chart-body flex-grow-1">
                        <canvas id="bookingsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>
    <?php echo '<script>const bookingData = ' . json_encode($bookingData) . ';</script>'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/bootstrap/js/all.min.js"></script>
    <script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>
    <script>
        setInterval(function() {
            fetch('keep_session.php')
                .then(response => response.text())
                .catch(error => console.error('Error keeping session alive:', error));
        }, 300000);

        document.addEventListener('DOMContentLoaded', function () {
            let chart;

            function createChart(labels, overallData, daytimeData, overnightData) {
                const ctx = document.getElementById('Earnings').getContext('2d');
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Overall Earnings',
                                data: overallData,
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                                fill: false
                            },
                            {
                                label: 'Daytime Earnings',
                                data: daytimeData,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                fill: false
                            },
                            {
                                label: 'Overnight Earnings',
                                data: overnightData,
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            function fetchDataAndUpdateChart(filter) {
                fetch(`fetch_earnings.php?type=${filter}`)
                    .then(response => response.json())
                    .then(data => {
                        const labels = data.map(entry => filter === 'yearly' ? entry.year : entry.month);
                        const overallData = data.map(entry => entry.overallEarnings);
                        const daytimeData = data.map(entry => entry.daytimeEarnings);
                        const overnightData = data.map(entry => entry.overnightEarnings);

                        if (!chart) {
                            // Create the chart if it doesn't exist
                            createChart(labels, overallData, daytimeData, overnightData);
                        } else {
                            // Update existing chart
                            chart.data.labels = labels;
                            chart.data.datasets[0].data = overallData;
                            chart.data.datasets[1].data = daytimeData;
                            chart.data.datasets[2].data = overnightData;
                            chart.update();
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }
            document.getElementById('earnings-filter').addEventListener('change', updateChart);

            function updateChart() {
                const filter = document.getElementById('earnings-filter').value;
                const chartTitle = document.getElementById('chart-title');
                chartTitle.textContent = filter === 'monthly' ? 'Monthly Earnings' : 'Yearly Earnings';
                fetchDataAndUpdateChart(filter);
            }

            // Initialize with default filter
            fetchDataAndUpdateChart('monthly');
        });

        const ctx = document.getElementById('paxChart').getContext('2d');
        let paxChart;

        // Initialize the dropdown with available years
        const yearSelect = document.getElementById('yearSelect');
        const years = [...new Set(bookingData.map(booking => booking.year))];
        years.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.text = year;
            yearSelect.add(option);
        });

        function createPaxChart(year) {
            const chartData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Pax',
                    data: Array(12).fill(0),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };

            // Populate the data array with pax counts for the selected year
            const yearBookings = bookingData.filter(booking => booking.year === year);
            yearBookings.forEach(booking => {
                const month = booking.month - 1; // Months are zero-based
                chartData.datasets[0].data[month] += booking.totalPax;
            });

            if (paxChart) {
                paxChart.destroy();
            }

            // Create or update the chart instance
            paxChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // Initial render with the first year
        createPaxChart(years[0]);

        // Event listener for year change
        yearSelect.addEventListener('change', () => {
            const selectedYear = yearSelect.value;
            createPaxChart(selectedYear);
        });

        document.getElementById('yearSelectorRoom').addEventListener('change', function() {
            var selectedYear = this.value;
            fetchRevenueData(selectedYear);  // Use the correct function name here
        });

        // Function to fetch revenue data based on the selected year
        function fetchRevenueData(year) {
            var rtx = document.getElementById('revenueChart').getContext('2d');
            var gradient = rtx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(54, 162, 235, 0.8)');
            gradient.addColorStop(1, 'rgba(54, 162, 235, 0.3)');

            // Perform an AJAX request to fetch data for the selected year
            fetch('get_room_revenue.php?year=' + year)
                .then(response => response.json())
                .then(data => {
                    // Check if data is valid before creating the chart
                    if (data.room_names && data.total_revenues) {
                        // Destroy the previous chart if it exists
                        if (window.chart) {
                            window.chart.destroy();
                        }

                        // Create the new chart with the fetched data
                        window.chart = new Chart(rtx, {
                            type: 'bar',
                            data: {
                                labels: data.room_names, // Room names
                                datasets: [{
                                    label: 'Room Revenue',
                                    data: data.total_revenues, 
                                    backgroundColor: gradient,
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 2,
                                    borderRadius: 8,
                                    hoverBackgroundColor: 'rgba(54, 162, 235, 0.7)',
                                    hoverBorderColor: 'rgba(54, 162, 235, 1)',
                                    hoverBorderWidth: 3
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Total Revenue (PHP)',
                                            font: {
                                                family: 'Arial, sans-serif',
                                                size: 14,
                                                weight: 'bold'
                                            },
                                            color: '#333'
                                        },
                                        ticks: {
                                            color: '#333',
                                            font: {
                                                family: 'Arial, sans-serif',
                                                size: 12
                                            }
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Room Name',
                                            font: {
                                                family: 'Arial, sans-serif',
                                                size: 14,
                                                weight: 'bold'
                                            },
                                            color: '#333'
                                        },
                                        ticks: {
                                            color: '#333',
                                            font: {
                                                family: 'Arial, sans-serif',
                                                size: 12
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        titleColor: '#fff',
                                        bodyColor: '#fff',
                                        bodyFont: {
                                            size: 14
                                        },
                                        displayColors: false
                                    },
                                    legend: {
                                        labels: {
                                            font: {
                                                family: 'Arial, sans-serif',
                                                size: 14
                                            },
                                            color: '#333'
                                        }
                                    }
                                },
                                animation: {
                                    duration: 1000,
                                    easing: 'easeOutBounce'
                                }
                            }
                        });
                    } else {
                        console.error('Invalid data received:', data);
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        }
        var defaultYearBook = document.getElementById('yearSelectorRoom').value;
        fetchRevenueData(defaultYearBook);

        document.getElementById('yearSelectorFeedback').addEventListener('change', function() {
            var selectedYear = this.value;
            fetchRatingsData(selectedYear);
        });

        function fetchRatingsData(year) {
            var ctx = document.getElementById('feedbackPieChart').getContext('2d');
            
            fetch('get_ratings.php?year=' + year)
                .then(response => response.json())
                .then(data => {
                    if (window.feedbackChart) {
                        window.feedbackChart.destroy();
                    }

                    window.feedbackChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: data.ratings, 
                            datasets: [{
                                label: 'Feedback Ratings',
                                data: data.rating_counts, 
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.6)',  // Red
                                    'rgba(54, 162, 235, 0.6)',  // Blue
                                    'rgba(255, 206, 86, 0.6)',  // Yellow
                                    'rgba(75, 192, 192, 0.6)',  // Green
                                    'rgba(153, 102, 255, 0.6)', // Purple
                                    'rgba(255, 159, 64, 0.6)'   // Orange
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',  // Red
                                    'rgba(54, 162, 235, 1)',  // Blue
                                    'rgba(255, 206, 86, 1)',  // Yellow
                                    'rgba(75, 192, 192, 1)',  // Green
                                    'rgba(153, 102, 255, 1)', // Purple
                                    'rgba(255, 159, 64, 1)'   // Orange
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    bodyFont: {
                                        size: 14
                                    }
                                },
                                legend: {
                                    labels: {
                                        font: {
                                            family: 'Arial, sans-serif',
                                            size: 14
                                        },
                                        color: '#333'
                                    }
                                }
                            },
                            animation: {
                                duration: 1000,  
                                easing: 'easeOutBounce'  
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        var defaultYearFeedback = document.getElementById('yearSelectorFeedback').value;
        fetchRatingsData(defaultYearFeedback);


        var atx = document.getElementById('bookingsChart').getContext('2d');

        // Function to fetch the data based on the selected year
        function fetchBookingsData(year) {
            $.ajax({
                url: 'fetch-dash-book.php', 
                method: 'GET',
                data: { year: year },
                success: function(response) {
                    var data = JSON.parse(response);
                    var bookings = data.bookings_count;

                    updateChartBook(bookings);
                }
            });
        }

        // Function to update the chart
        function updateChartBook(bookings) {
            var gradientLine = atx.createLinearGradient(0, 0, 0, 400);
            gradientLine.addColorStop(0, 'rgba(75, 192, 192, 1)');
            gradientLine.addColorStop(1, 'rgba(75, 192, 192, 0.3)');

            book.data.datasets[0].data = bookings;
            book.data.datasets[0].borderColor = gradientLine;

            // Update the chart
            book.update();
        }

        var defaultYear = $('#yearSelectorBook').val();
        fetchBookingsData(defaultYear); 

        $('#yearSelectorBook').change(function() {
            var selectedYear = $(this).val();
            fetchBookingsData(selectedYear);  
        });
 

        // Create the chart
        var book = new Chart(atx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Bookings Count',
                    data: [],  
                    borderColor: [],
                    borderWidth: 3, 
                    fill: false,  // Don't fill the area under the line
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)', 
                    pointRadius: 6,  // Size of the points on the line
                    pointHoverRadius: 8,  // Hover size of the points
                    tension: 0.4,  // Smoothing for the line
                    hoverBackgroundColor: 'rgba(75, 192, 192, 0.8)', 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, 
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Bookings',
                            font: {
                                family: 'Arial, sans-serif', 
                                size: 14,
                                weight: 'bold',
                            },
                            color: '#333',  
                        },
                        ticks: {
                            color: '#333',  
                            font: {
                                family: 'Arial, sans-serif',
                                size: 12,
                            },
                        },
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month',
                            font: {
                                family: 'Arial, sans-serif',
                                size: 14,
                                weight: 'bold',
                            },
                            color: '#333',
                        },
                        ticks: {
                            color: '#333',
                            font: {
                                family: 'Arial, sans-serif',
                                size: 12,
                            },
                        },
                    },
                },
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',  // Darker background for tooltips
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        bodyFont: {
                            size: 14,
                        },
                        displayColors: false,  // Hide the color box in the tooltip
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.raw + ' bookings'; 
                            },
                        },
                    },
                    legend: {
                        display: false,  
                    },
                },
                animation: {
                    duration: 1000,  // Smooth animation
                    easing: 'easeOutElastic',  // Elegant easing effect
                },
            }
        });




        document.getElementById('hamburger').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('show');
        
        const navbar = document.getElementById('header');
        navbar.classList.toggle('shifted');
        
        const mainContent = document.getElementById('main-content');
        mainContent.classList.toggle('shifted');
        });

        document.querySelectorAll('.collapse').forEach(collapse => {
            collapse.addEventListener('show.bs.collapse', () => {
                collapse.style.height = collapse.scrollHeight + 'px';
            });
            collapse.addEventListener('hidden.bs.collapse', () => {
                collapse.style.height = '0px';
            });
        });


    </script>
</body>
</html>
