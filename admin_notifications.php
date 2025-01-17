<?php
    date_default_timezone_set('Asia/Manila'); 
    include "role_access.php";
    include("connection.php");
    checkAccess('admin');

    function timeAgo($timestamp) {
        $timeAgo = '';
        $currentTime = new DateTime();
        $notificationTime = new DateTime($timestamp);
        $interval = $currentTime->diff($notificationTime);
        
        if ($interval->y > 0) {
            $timeAgo = $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
        } elseif ($interval->m > 0) {
            $timeAgo = $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
        } elseif ($interval->d > 0) {
            $timeAgo = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
        } elseif ($interval->h > 0) {
            $timeAgo = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
        } elseif ($interval->i > 0) {
            $timeAgo = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
        } else {
            $timeAgo = 'Just now';
        }
        
        return $timeAgo;
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

        #sidebar .font-logo {
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
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
            transition: transform 0.3s ease;
            z-index: 1000; /* Ensure sidebar is above other content */
        }

        header {
            position: none;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
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
        #sidebar .badge-notif, .badge-chat{
            border-radius: 20px;
            width: auto;
            
            background-color: #fff !important;
        }
        #sidebar .badge-chat, #sidebar .badge-notif {
            display: inline-block; 
            width: 15px; 
            height: 5px; 
            border-radius: 5px; 
            text-align: center;
            align-content: center;
            background-color: #fff !important;
            margin-left: 5px;
        }
    
        #sidebar .nav-link:hover .badge-notif, #sidebar .nav-link:hover .badge-chat{
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D) !important;
        }

        #sidebar .badge-notif, #sidebar {
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D) !important;
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
            #main-content{
                margin-top: 60px;
                padding-inline: 10px;
            }
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
    <header id="header" class="bg-light shadow-sm">
        <button id="hamburger" class="btn btn-primary">
            ☰
        </button>
        <span class="text-white ms-3 font-logo-mobile">Lanmar Resort</span>
    </header>

    <!-- Sidebar -->
    <div id="sidebar" class="d-flex flex-column p-3 text-white vh-100">
        <a href="#" class="mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4 font-logo">Lanmar Resort</span>
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
                    <li><a class="nav-link text-white" href="pending_reservation.php">Pending Reservations</a></li>
                    <li><a class="nav-link text-white" href="approved_reservation.php">Approved Reservations</a></li>
                </ul>
            </li>
            <li>
                <a href="admin_notifications.php" class="nav-link active text-white target">Notifications</a>
            </li>
            <li>
                <a href="admin_home_chat.php" class="nav-link text-white chat">Chat with Customer</a>
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
    
    <div id="main-content" class="p-3">
        <div class="notification-page">
            <h2><strong>New Reservation(s)</strong></h2>
            <div class="notification-container">
            <?php 
                $sql = "SELECT n.notification_id, n.booking_id, n.is_read_admin, n.timestamp, b.dateIn, b.dateOut, b.checkin, b.checkout, b.status, 
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
                        n.is_read_admin = 0 AND (b.status = 'Pending' OR (n.status = 5 AND b.status = 'Approved'))
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
                        if($notification['status'] === 'Pending'){
                            $display = 'New';
                        }else{
                            $display = 'Rebooked';
                        }

                        $checkinTime = date('h:i A', strtotime($notification['checkin']));
                        $checkoutTime = date('h:i A', strtotime($notification['checkout']));
                        $timeAgo = timeAgo($notification['timestamp']);

            ?>

                <!-- Notification Card -->
                <div class="notification-card new " data-notification-id="<?php echo $notification['notification_id']; ?>">
                    <span class="badge-new"><?php echo $display; ?></span>
                    <div class="notification-content">
                        <p><strong>From <?php echo  htmlspecialchars($fullName); ?></strong></p>
                        <p>Date & Time: <br><?php echo  $dateDisplay . ' ' . $checkinTime.'-'.$checkoutTime; ?></p>
                    </div>
                    <div class="notification-footer">
                        <div class="time-container">
                            <p class="time"><?php echo  htmlspecialchars($timeAgo); ?></p>
                        </div>
                        <div class="buttons-container">
                            <button class="btn btn-primary btn-sm view-button" data-booking-id="<?php echo $notification['booking_id']; ?>">View</button>
                            <button class="btn btn-secondary btn-sm" onclick="markAsRead(<?php echo $notification['notification_id']; ?>, 'admin')">Mark as Read</button>
                        </div>
                    </div>
                    <span class="dot-indicator"></span>
                </div>
                <!-- Add more cards as needed -->
            <?php } ?>
            </div>

            <hr />

            <!-- Cancellation Form Section -->
            <h2><strong>Cancellation Form(s)</strong></h2>
            <div class="notification-container">
                <!-- Cancellation Card -->
                <?php 
                $sql2 = "SELECT c.cancel_id,c.booking_id, c.is_read, c.timestamp, b.dateIn, b.dateOut, b.checkin, b.checkout,
                        u.user_id, 
                        u.firstname, 
                        u.lastname
                    FROM 
                        cancel_tbl c
                    LEFT JOIN 
                        booking_tbl b ON c.booking_id = b.booking_id
                    LEFT JOIN 
                        users u ON b.user_id = u.user_id
                    WHERE 
                        c.is_read = 0
                    ORDER BY 
                        c.timestamp DESC
                    ";
                    $query2 = $pdo->prepare($sql2);
                    $query2->execute();
                    $notifications2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($notifications2 as $notification) {
                        $fullName = ucwords($notification['firstname']) . ' ' . ucwords($notification['lastname']);
                        $bookingId = $notification['booking_id'];
                        $dateIn = date('F j, Y', strtotime($notification['dateIn']));
                        $dateOut = date('F j, Y', strtotime($notification['dateOut']));

                        $dateDisplay = ($dateIn === $dateOut) ? $dateIn : "$dateIn - $dateOut";

                        $checkinTime = date('h:i A', strtotime($notification['checkin']));
                        $checkoutTime = date('h:i A', strtotime($notification['checkout']));
                        $timeAgo = timeAgo($notification['timestamp']);

            ?>
                <div class="notification-card cancel" data-cancel-id="<?php echo $notification['cancel_id'];?>">
                    <span class="badge-cancel">For Cancellation</span>
                    <div class="notification-content">
                        <p><strong>From <?php echo $fullName; ?></strong></p>
                        <p>Date & Time: <br><?php echo  $dateDisplay . ' ' . $checkinTime.'-'.$checkoutTime; ?></p>
                    </div>
                    <div class="notification-footer">
                        <div class="time-container">
                            <p class="time">5h ago</p>
                        </div>
                        <div class="buttons-container">
                        <button class="btn btn-primary btn-sm view-button" data-booking-id="<?php echo $notification['booking_id']; ?>">View</button>
                            <button class="btn btn-secondary btn-sm" onclick="markAsRead1(<?php echo $notification['cancel_id']; ?>)">Mark as Read</button>
                        </div>
                    </div>
                    <span class="dot-indicator"></span>
                </div>
                <?php } ?>
                <!-- Add more cancellation cards as needed -->
            </div>

            <hr />

            <h2><strong>Read Reservation(s)</strong></h2>
            <div class="notification-container read">
            
            </div>
        </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/all.min.js"></script>
<script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>
<script>
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
    document.addEventListener('DOMContentLoaded', function () {
        fetchNotifications();

        document.querySelector('.notification-container').addEventListener('click', function (event) {
            if (event.target && event.target.classList.contains('view-button')) {
                const bookingId = event.target.dataset.bookingId;
                window.location.href = `pending_reservation.php?booking_id=${bookingId}`;
            }
        });

        document.querySelector('.notification-container.read').addEventListener('click', function (event) {
            if (event.target && event.target.classList.contains('view-button')) {
                const bookingId = event.target.dataset.bookingId;
                const status = event.target.dataset.status;
                if(status == 'Pending'|| status == 'Cancellation1'){
                    window.location.href = `pending_reservation.php?booking_id=${bookingId}`;
                }else if(status == 'Approved' || status == 'Cancellation2'){
                    window.location.href = `approved_reservation.php?booking_id=${bookingId}`;
                }else{
                    window.location.href = `reservation_history.php?booking_id=${bookingId}`;
                }
                
            }
        });
    });

    function markAsRead(notificationId, role) {
        $.ajax({
            type: "POST",
            url: "update_notification.php",
            data: { 
                notification_id: notificationId,
                role: role
            },
            success: function(response) {
                $(`[data-notification-id='${notificationId}']`).remove();
                fetchNotifications();
            },
            error: function() {
                console.error('Failed to mark as read');
            }
        });
    }
    function markAsRead1(cancelId) {
        $.ajax({
            type: "POST",
            url: "update_cancelform.php",
            data: { 
                cancel_id: cancelId
            },
            success: function(response) {
                $(`[data-cancel-id='${cancelId}']`).remove();
                fetchNotifications();
            },
            error: function() {
                console.error('Failed to mark as read');
            }
        });
    }

    function fetchNotifications() {
        $.ajax({
            url: "fetch_admin_notifications.php", // Server-side script to fetch notifications
            method: "GET",
            success: function(data) {
                $('.notification-container.read').html(data); // Corrected selector
            },
            error: function() {
                console.error('Failed to fetch notifications');
            }
        });
    }
    $(document).ready(function() {
        function updateNotificationCount(){
        $.ajax({
                url: 'admin_notification_count.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var notificationCount = data;
                    // Update the notification counter in the sidebar
                    var notificationLink = $('.nav-link.text-white.target');
                    if (notificationCount >= 1) {
                        notificationLink.html('Notification <span class="badge badge-notif bg-secondary"></span>');
                    } else {
                        notificationLink.html('Notification');
                    }
                },
                error: function() {
                    console.log('Error retrieving notification count.');
                }
            });  
        }
        
        function updateChatPopup() {
            $.ajax({
                url: 'admin_chat_count.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var counter = data;
                    // Update the chat counter in the sidebar
                    var notificationLink = $('.nav-link.text-white.chat');
                    
                    if (counter >= 1) {
                        notificationLink.html('Chat with Lanmar <span class="badge badge-chat bg-secondary"></span>');
                    } else {
                        notificationLink.html('Chat with Lanmar');
                    }
                },
                error: function() {
                    console.log('Error retrieving chat count.');
                }
            });
        }
        updateNotificationCount();
        updateChatPopup();
        setInterval(updateNotificationCount, 5000);
        setInterval(updateChatPopup, 5000);
    });
</script>
</body>
</html>
