<?php 
    date_default_timezone_set('Asia/Manila'); 
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/fontawesome.min.css">
    <?php include "sidebar-design.php"; ?>
</head>
<style>
    .container {
        max-width: 80%;
    }

    body {
        background-color: #f8f9fa;
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
        border-left: 5px solid rgb(29, 69, 104);
    }

    .notification-card.read {
        border-left: 5px solid #6c757d;
    }

    .notification-card.cancel {
        border-left: 5px solid #dc3545;
    }

    .badge-new,
    .badge-cancelled {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 12px;
        background-color: rgb(29, 69, 104); /* New */
        color: white;
        padding: 3px 6px;
        border-radius: 4px;
        z-index: 1; /* Ensure the badge is above the card */
    }

    .badge-cancelled {
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

    .btn-primary{
        background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
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
        #main-content{
            padding: 0;
        }
        .container {
            max-width: 100%;
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


<body>
<!-- Sidebar -->
<div id="sidebar" class="d-flex flex-column p-3 text-white position-fixed vh-100">
    <a href="#" class="mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">Lanmar Resort</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index1.php" class="nav-link text-white">Book Here</a>
        </li>
        <li><a href="my-reservation.php" class="nav-link text-white">My Reservations</a></li>
        <li><a href="my-notification.php" class="nav-link text-white active">Notification</a></li>
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

<div id="main-content" class="container mt-2">
    <div class="notification-page">
        <h2><strong>New Notification(s) from Lanmar</strong></h2>
        <div class="notification-container">
        <?php 
            $statuses = [
                1 => ['badge' => 'New', 'message' => 'Your Reservation #%s has been approved.', 'class' => 'new'],
                2 => ['badge' => 'Rejected', 'message' => 'Your Reservation #%s has been rejected.', 'class' => 'cancel'],
                3 => ['badge' => 'Cancelled', 'message' => 'Your Reservation #%s has been cancelled.', 'class' => 'cancel'],
                4 => ['badge' => 'Cancellation Rejected', 'message' => 'Your Cancellation for Reservation #%s has been rejected.', 'class' => 'cancel']
            ];

            foreach ($statuses as $status => $details) {
                $sql = "SELECT n.notification_id, n.status, n.is_read_user, n.timestamp, b.booking_id, b.user_id
                        FROM notification_tbl n
                        JOIN booking_tbl b ON n.booking_id = b.booking_id
                        JOIN users u ON b.user_id = u.user_id
                        WHERE n.is_read_user = 0 
                        AND n.status = :status 
                        AND b.user_id = :userId
                        ORDER BY n.timestamp DESC
                        LIMIT 1";
                $query = $pdo->prepare($sql);
                $query->execute(['status' => $status, 'userId' => $userId]);
                $notifications = $query->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($notifications as $notification) {
                    $bookingId = $notification['booking_id'];
                    $notificationId = $notification['notification_id'];
                    $timeAgo = timeAgo($notification['timestamp']);
                    $message = sprintf($details['message'], $bookingId);
        ?>
        <!-- Notification Card -->
        <div class="notification-card <?php echo $details['class']; ?>" data-notification-id="<?php echo $notificationId; ?>">
            <span class="badge-<?php echo strtolower($details['badge']); ?>"><?php echo $details['badge']; ?></span>
            <div class="notification-content">
                <p><?php echo $message; ?></p>
            </div>
            <div class="notification-footer">
                <div class="time-container">
                    <p class="time"><?php echo $timeAgo; ?></p>
                </div>
                <div class="buttons-container">
                    <button class="btn btn-primary btn-sm view-button" data-booking-id="<?php echo htmlspecialchars($bookingId); ?>">View</button>
                    <button class="btn btn-secondary btn-sm" onclick="markAsRead(<?php echo $notificationId; ?>, 'admin')">Mark as Read</button>
                </div>
            </div>
            <span class="dot-indicator"></span>
        </div>
        <?php
                }
            }
        ?>
    </div>

    <hr />

    <h2><strong>Read Notification(s)</strong></h2>
    <div class="notification-container read">
    
    </div>
</div>



<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.getElementById('hamburger').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
    
    const navbar = document.querySelector('.navbar');
    navbar.classList.toggle('shifted');
    
    const mainContent = document.getElementById('main-content');
    mainContent.classList.toggle('shifted');
    });

    document.addEventListener('DOMContentLoaded', function () {
        fetchNotifications();

        document.querySelector('.notification-container').addEventListener('click', function (event) {
            if (event.target && event.target.classList.contains('view-button')) {
                const bookingId = event.target.dataset.bookingId;
                window.location.href = `my-reservation.php?booking_id=${bookingId}`;
            }
        });

        document.querySelector('.notification-container.read').addEventListener('click', function (event) {
            if (event.target && event.target.classList.contains('view-button')) {
                const bookingId = event.target.dataset.bookingId;
                window.location.href = `my-reservation.php?booking_id=${bookingId}`;                
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

    function fetchNotifications() {
        $.ajax({
            url: "fetch_notifications.php", // Server-side script to fetch notifications
            method: "GET",
            success: function(data) {
                $('.notification-container.read').html(data); // Corrected selector
            },
            error: function() {
                console.error('Failed to fetch notifications');
            }
        });
    }



</script>
</body>
</html>