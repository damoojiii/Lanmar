<?php 
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }   
    session_start();
    include "role_access.php";
    checkAccess('user');
    $userId = $_SESSION['user_id']; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanmar Resort</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/fontawesome.min.css">
    <?php 
    include "sidebar-design.php";
    include "calendar-design.php";
    ?>
    <style>
        .container{
            display: flex;
            width: 100%;
            padding: 0;
            gap: 5rem;
        }
        .inputs label {
            font-weight: bold; /* Emphasize labels */
            margin-bottom: 0.5rem; /* Add space below labels */
            display: block; /* Ensure labels are above inputs */
        }

        .inputs .form-control {
            width: 100%; /* Make inputs take full width */
            flex-grow: unset; /* Remove auto-grow */
        }

        /* .inputs .mb-3 {
            margin-bottom: 10px;
        }

        .form-label.me-3 {
            margin-bottom: 0;
        } */

        .legend{
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 20px;
        }
        .legend h4 {
            margin-bottom: 10px;
        }

        .legend ul {
            list-style: none;
            padding: 0;
        }

        .legend li {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .legend .box {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .box.available {
            background-color: white;
            border: 1px solid black;
        }

        .box.booked {
            background-color: #00214b;
        }

        .box.invalid {
            background-color: lightgray;
        }

        .box.not-available {
            background-color: darkgray;
        }

        .box.booking-process {
            background-color: #ffc107;
        }

        .continue-btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .continue-btn {
            background-color: #00214b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .continue-btn:hover {
            background-color: #004080;
        }
        /*.progress{
            width: 100%;
            height: 80px;
            background: #D9D9D9;
        }*/
        .progress-container {
            width: 100%;
            margin: 5px 0;
            height: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background:lightgray;
        }

        .progress-bar {
            width: 100%;
            margin-left: 300px;
            display: flex;
            flex-direction: row;
            gap: 3.5rem;
            position: relative;
        }

        .progress-bar::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 28%;
            width: 45%;
            height: 5px;
            background-color: white;
            z-index: -1;
            transform: translateY(-50%);
        }

        .step {
            text-align: center;
            position: relative;
        }

        .step .circle {
            width: 30px;
            height: 30px;
            background-color: white;
            border: 2px solid white;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
        }

        .step.completed .circle {
            background-color: #00214b; /* Blue background for completed steps */
            border-color: #00214b; /* Blue border */
            color: white; 
        }

        .step.completed ~ .step .circle {
            background-color: lightgrey;
            border-color: white; 
            color: white; 
        }

        .step::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 5px;
            background-color: #00214b; /* Blue color for progress line */
            transform: translateY(-50%);
            z-index: -1;
        }

        .step:last-child::after {
            content: none;
        }

        .step:not(.completed)::after {
            background-color: white;
        }
    </style>
    
</head>

<body>

<!-- Sidebar -->
<div id="sidebar" class="d-flex flex-column p-3 text-white position-fixed vh-100">
    <a href="#" class="mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">Lanmar Resort</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index1.php" class="nav-link text-white active">Book Here</a>
        </li>
        <li><a href="my-reservation.php" class="nav-link text-white">My Reservations</a></li>
        <li><a href="my-notification.php" class="nav-link text-white">Notification</a></li>
        <li><a href="chats.php" class="nav-link text-white">Chat with Lanmar</a></li>
        <li><a href="my-feedback.php" class="nav-link text-white">Feedback</a></li>
        <li><a href="settings_user.php" class="nav-link text-white">Settings</a></li>
    </ul>
    <hr>
    <a href="logout.php" class="nav-link text-white">Log out</a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <button id="hamburger" class="navbar-toggler" type="button"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
        </div>
    </div>
</nav>

<div class="progress-container">
    <div class="progress-bar">
        <div class="step completed">
            <div class="circle">1</div>
            <span>Check in & Check out</span>
        </div>
        <div class="step">
            <div class="circle">2</div>
            <span>Rooms & Rates</span>
        </div>
        <div class="step">
            <div class="circle">3</div>
            <span>Guest Information</span>
        </div>
        <div class="step">
            <div class="circle">4</div>
            <span>Payment & Receipt</span>
        </div>
    </div>
</div>
<!-- Main content -->
<div id="main-content" class="mt-4 pt-3">
    <form action="booking-process1.php" method="GET">
        <div class="row inputs mb-3 mx-2" style="max-width: 95%;">
            <!-- Check-in Date -->
            <div class="col-md-2 mb-3">
                <label for="date-in" class="form-label">Check-in Date:</label>
                <input id="date-in" class="form-control" type="text" placeholder="Select a date" name="dateIn" readonly required>
            </div>
            <!-- Check-out Date -->
            <div class="col-md-2 mb-3">
                <label for="date-out" class="form-label">Check-out Date:</label>
                <input id="date-out" class="form-control" type="text" placeholder="Select check-out date" name="dateOut" readonly required>
            </div>
            <!-- Check-In Time -->
            <div class="col-md-2 mb-3">
                <label for="checkin-time" class="form-label">Check-In Time:</label>
                <select id="checkin-time" name="checkin" class="form-control" required>
                    <option value="" hidden selected>Select time</option>
                </select>
            </div>
            <!-- Check-Out Time -->
            <div class="col-md-2 mb-3">
                <label for="checkout-time" class="form-label">Check-Out Time:</label>
                <select id="checkout-time" name="checkout" class="form-control" required>
                    <option value="" hidden selected>Select check-in time first</option>
                </select>
            </div>
            <!-- Total No. of Hours -->
            <div class="col-md-2 mb-3">
                <label class="form-label">Total No. of Hours:</label>
                <input type="text" name="numhours" class="form-control" readonly required>
            </div>
        </div>


        <div class="container">
            <div class="calendar">
                <div class="header">
                    <div class="month"></div>
                    <div class="btns">
                        <div class="btn today-btn">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="btn prev-btn">
                            <i class="fas fa-chevron-left"></i>
                        </div>
                        <div class="btn next-btn">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
                <div class="weekdays">
                    <div class="day">Sun</div>
                    <div class="day">Mon</div>
                    <div class="day">Tue</div>
                    <div class="day">Wed</div>
                    <div class="day">Thu</div>
                    <div class="day">Fri</div>
                    <div class="day">Sat</div>
                </div>
                <div class="days">
                    <!-- lets add days using js -->
                </div>
            </div>
            <div class="legend">
                <h4>Legend</h4>
                <ul>
                    <li><span class="box available"></span> Available</li>
                    <li><span class="box booked"></span> Selected Day</li>
                    <li><span class="box invalid"></span> Invalid Date</li>
                    <li><span class="box not-available"></span> Not Available</li>
                    <li><span class="box booking-process"></span> On Booking Process</li>
                </ul>

                <div class="continue-btn-container">
                    <button class="continue-btn" name="continue" type="submit">Continue</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/all.min.js"></script>
<script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="script.js"></script>
</body>
</html>


<script>
    document.getElementById('hamburger').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
    
    const navbar = document.querySelector('.navbar');
    navbar.classList.toggle('shifted');
    
    const mainContent = document.getElementById('main-content');
    mainContent.classList.toggle('shifted');
});
</script>

