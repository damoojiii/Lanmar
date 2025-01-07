<?php
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
// Start the session at the very beginning of the file
    session_start();
    include "role_access.php";
    checkAccess('admin');
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

        #sidebar span {
            font-family: 'nautigal';
            font-size: 30px !important;
        }

        #sidebar {
            width: 250px;
            position: fixed;
            top: 0; 
            height: 100vh;
            overflow-y: auto; 
            background: #001A3E;
            transition: transform 0.3s ease;
            z-index: 1000; /* Ensure sidebar is above other content */
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background-color: #001A3E;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 15px;
            transition: margin-left 0.3s ease; /* Smooth transition for header */
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
            margin-top: 40px; /* Add top margin for header */
            padding: 20px; /* Padding for content */
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

        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                transform: translateX(-100%); /* Hide sidebar off-screen */
            }

            #sidebar.show {
                transform: translateX(0); /* Show sidebar */
            }

            #main-content {
                margin-left: 0; /* Remove margin for smaller screens */
            }

            #hamburger {
                display: block; /* Show hamburger button on smaller screens */
            }
        }

        .flex-container {
            display: flex;
            gap: 20px;
        }
        .settings-form-container {
            margin-bottom: 20px;
        }
        .alert {
            padding: 10px;
            margin: 10px 0;
        }
        .alert-success {
            color: green;
        }
        .alert-danger {
            color: red;
        }
        .button-container {
            display: flex;
            justify-content: end;
        }
        button {
            border-radius: 50px;
            padding: 13px 30px;
            background-color: #03045e;
            border: none;
            cursor: pointer;
            color: white;
        }

        .flex-container {
        display: flex;
        gap: 20px;
    }

    .sidebar-settings {
        display: flex;
        flex-direction: column;
        width: 230px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 35px 15px 15px 15px;
        align-items: center;
        justify-content: center;
    }

    .settings-links {
        width: 100%
    }

    .settings-links ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .settings-links li {
        margin-bottom: 10px;
        text-align: center;
    }

    .settings-links a {
        text-decoration: none;
        color: #333;
        padding: 10px 15px;
        border-radius: 2px;
        transition: 0.3s;
    }

    .settings-links a:hover {
        background-color: #ddd;
    }

    .settings-links .links {
        margin-bottom: 30px;
    }

    .main-content {
        flex: 1;
        padding: 25px;
        background-color: #ffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-group input {
        margin-bottom: 10px;
    }

    .settings-form .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 17px;
    }

    .settings-form .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 0px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        float: right;
        margin-left: 16%;
        margin-bottom: 15px;
    }

    .four-box-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin: 5px 0 0 0;
    }

    .links {
        border-bottom: 1px solid #ccc;
    }

    .links:last-child {
        border-bottom: none;
    }

    .links i {
        font-size: 12px;
    }

    .links li a {
        display: flex;
        align-items: center;
        gap: 20px;
        font-size: 15px;
        font-weight: 600;
        padding: 15px 20px;
        transition: all 0.3s;
        justify-content: space-between;
    }

    .links .active a {
        background-color: #1c2531;
        color: white;
        border-radius: 10px 10px 10px 10px;
    }

    .button-container {
        display: flex;
        justify-content: end;
    }

    .settings-form button, 
        .save-btn {
            border-radius: 10px !important;  
            padding: 13px 30px;
            background-color: #03045e;
            border: none;
            cursor: pointer;
            color: white;
        }
    
        .notification-page {
            padding: 20px;
        }

        .notification-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
            margin-top: 15px;
        }

        .notification-card {
            position: relative;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            width: calc(33.333% - 20px);
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .notification-card:hover {
            transform: translateY(-5px);
        }

        .notification-card.new {
            border-left: 5px solid #007bff;
        }

        .notification-card.read {
            border-left: 5px solid #6c757d;
        }

        .notification-card.cancel {
            border-left: 5px solid #dc3545;
        }

        .badge-new,
        .badge-cancel {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 12px;
            background-color: #007bff; /* New */
            color: white;
            padding: 3px 6px;
            border-radius: 4px;
            z-index: 1; /* Ensure the badge is above the card */
        }

        .badge-cancel {
            background-color: #dc3545; /* Cancelled */
        }

        .notification-content {
            margin-top: 20px; /* Add margin to avoid overlap with the badge */
        }

        .notification-content p {
            margin: 0;
            font-size: 14px;
        }

        .notification-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .time-container {
            flex: 1;
            text-align: left;
        }

        .buttons-container {
            display: flex;
            gap: 5px;
        }


        .notification-footer .time {
            font-size: 12px;
            color: #888;
            margin: 0;
        }

        .btn {
            font-size: 12px;
            padding: 5px 10px;
        }

        .dot-indicator {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 10px;
            height: 10px;
            background-color: #dc3545;
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .notification-card {
                width: calc(50% - 20px);
            }
        }

        @media (max-width: 576px) {
            .notification-card {
                width: 100%;
            }
        }

    </style>
</head>
<body>
    <!-- Header -->
    <header id="header">
        <button id="hamburger" class="btn btn-primary" onclick="toggleSidebar()">
            ☰
        </button>
        <span class="text-white ms-3">Navbar</span>
    </header>

    <!-- Sidebar -->
    <div id="sidebar" class="d-flex flex-column p-3 text-white vh-100">
        <a href="#" class="mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4">Lanmar Resort</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="admin_dashboard.php" class="nav-link text-white">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white d-flex justify-content-between align-items-center p-2 drop" href="#manageReservations" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="manageReservations">
                    Manage Reservations
                    <span class="caret-icon">
                        <i class="fa-solid fa-caret-down"></i>
                    </span>
                </a>
                <ul class="collapse list-unstyled ms-3" id="manageReservations">
                    <li><a class="nav-link text-white" href="pending_reservation.php"><i class="fa-solid fa-circle navcircle"></i> Pending Reservations</a></li>
                    <li><a class="nav-link text-white" href="approved_reservation.php"><i class="fa-solid fa-circle navcircle"></i> Approved Reservations</a></li>
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
                <a href="reports.php" class="nav-link text-white">Reports</a>
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
                    <li><a class="dropdown-item" href="account_settings.php">Account Settings</a></li>
                    <li><a class="dropdown-item" href="homepage_settings.php">Homepage Settings</a></li>
                    <li><a class="dropdown-item" href="privacy_settings.php">Privacy Settings</a></li>
                    <li><a class="dropdown-item" href="room_settings.php">Room Settings</a></li>
                </ul>
            </li>
        </ul>
        <hr>
        <a href="logout.php" class="nav-link text-white">Log out</a>
    </div>
    
    <div id="main-content" class="p-3">
        <div class="notification-page">
            <h2>New Reservation(s)</h2>
            <div class="notification-container">
            <?php 
                $sql = "SELECT n.notification_id, n.status, n.is_read_admin, n.timestamp, b.dateIn, b.dateOut, b.checkin, b.checkout, 
                        u.user_id, 
                        u.firstname, 
                        u.lastname
                    FROM 
                        notification_tbl n
                    JOIN 
                        booking_tbl b ON n.booking_id = b.booking_id
                    JOIN 
                        users u ON b.user_id = u.user_id
                    WHERE 
                        n.is_read_admin = 0
                    ORDER BY 
                        n.timestamp DESC
                    ";
                    $query = $pdo->prepare($sql);
                    $query->execute();
                    $notifications = $query->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($notifications as $notification) {
                        $fullName = ucwords($notification['firstname']) . ' ' . ucwords($notification['lastname']);
                        $dateIn = date('F j, Y', strtotime($notification['dateIn']));
                        $dateOut = date('F j, Y', strtotime($notification['dateOut']));

                        $dateDisplay = ($dateIn === $dateOut) ? $dateIn : "$dateIn - $dateOut";

                        $checkinTime = date('h:i A', strtotime($notification['checkin']));
                        $checkoutTime = date('h:i A', strtotime($notification['checkout']));
                        $timestamp = $notification['timestamp'];
                        // You can calculate the 'time ago' here or use a library like moment.js for dynamic updates in the frontend.
                        $timeAgo = '3h ago';
            ?>

                <!-- Notification Card -->
                <div class="notification-card new">
                    <span class="badge-new">New</span>
                    <div class="notification-content">
                        <p><strong>From <?php echo  htmlspecialchars($fullName); ?></strong></p>
                        <p>Date & Time: <br><?php echo  $dateDisplay . ' ' . $checkinTime.'-'.$checkoutTime; ?></p>
                    </div>
                    <div class="notification-footer">
                        <div class="time-container">
                            <p class="time"><?php echo  htmlspecialchars($timeAgo); ?></p>
                        </div>
                        <div class="buttons-container">
                            <button class="btn btn-primary btn-sm">View</button>
                            <button class="btn btn-secondary btn-sm">Mark as Read</button>
                        </div>
                    </div>
                    <span class="dot-indicator"></span>
                </div>
                <!-- Add more cards as needed -->
            <?php } ?>
            </div>

            <hr />

            <!-- Cancellation Form Section -->
            <h2>Cancellation Forms</h2>
            <div class="notification-container">
                <!-- Cancellation Card -->
                <div class="notification-card cancel">
                    <span class="badge-cancel">For Cancellation</span>
                    <div class="notification-content">
                        <p><strong>Submitted by John Doe</strong></p>
                        <p>Reservation # 1234 is requested to be cancel.</p>
                    </div>
                    <div class="notification-footer">
                        <div class="time-container">
                            <p class="time">5h ago</p>
                        </div>
                        <div class="buttons-container">
                            <button class="btn btn-primary btn-sm">View</button>
                            <button class="btn btn-secondary btn-sm">Mark as Read</button>
                        </div>
                    </div>
                    <span class="dot-indicator"></span>
                </div>
                <!-- Add more cancellation cards as needed -->
            </div>

            <hr />

            <h2>Read Reservation(s)</h2>
            <div class="notification-container">
            <?php 
                $sql1 = "SELECT n.notification_id, n.status, n.is_read_admin, n.timestamp, b.dateIn, b.dateOut, b.checkin, b.checkout, 
                        u.user_id, 
                        u.firstname, 
                        u.lastname
                    FROM 
                        notification_tbl n
                    JOIN 
                        booking_tbl b ON n.booking_id = b.booking_id
                    JOIN 
                        users u ON b.user_id = u.user_id
                    WHERE 
                        n.is_read_admin = 1
                    ORDER BY 
                        n.timestamp DESC
                    ";
                    $query1 = $pdo->prepare($sql1);
                    $query1->execute();
                    $notifications1 = $query1->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($notifications1 as $notification) {
                        $fullName = ucwords($notification['firstname']) . ' ' . ucwords($notification['lastname']);
                        $dateIn = date('F j, Y', strtotime($notification['dateIn']));
                        $dateOut = date('F j, Y', strtotime($notification['dateOut']));

                        $dateDisplay = ($dateIn === $dateOut) ? $dateIn : "$dateIn - $dateOut";

                        $checkinTime = date('h:i A', strtotime($notification['checkin']));
                        $checkoutTime = date('h:i A', strtotime($notification['checkout']));
                        $timestamp = $notification['timestamp'];
                        // You can calculate the 'time ago' here or use a library like moment.js for dynamic updates in the frontend.
                        $timeAgo = '1d ago';
            ?>
                <!-- Notification Card -->
                <div class="notification-card read">
                    <div class="notification-content">
                        <p><strong>From <?php echo  htmlspecialchars($fullName); ?></strong></p>
                        <p>Date & Time: <br><?php echo  $dateDisplay . ' ' . $checkinTime.'-'.$checkoutTime; ?></p>
                    </div>
                    <div class="notification-footer">
                        <p class="time"><?php echo  htmlspecialchars($timeAgo); ?></p>
                        <button class="btn btn-primary btn-sm">View</button>
                    </div>
                </div>
            <?php } ?>
                <!-- Add more cards as needed -->
            </div>
        </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/all.min.js"></script>
<script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const header = document.getElementById('header');

        sidebar.classList.toggle('show');

        if (sidebar.classList.contains('show')) {
            mainContent.style.marginLeft = '250px'; // Adjust the margin when sidebar is shown
            header.style.marginLeft = '250px'; // Move the header when sidebar is shown
        } else {
            mainContent.style.marginLeft = '0'; // Reset margin when sidebar is hidden
            header.style.marginLeft = '0'; // Reset header margin when sidebar is hidden
        }
    }

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
