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
        <h2>New Notification(s) from Lanmar</h2>
        <div class="notification-container">
            <!-- Notification Card -->
            <div class="notification-card new">
                <span class="badge-new">New</span>
                <div class="notification-content">
                    <p>Your Reservation # 1234 has been approved.</p>
                </div>
                <div class="notification-footer">
                    <div class="time-container">
                        <p class="time">3h ago</p>
                    </div>
                    <div class="buttons-container">
                        <button class="btn btn-primary btn-sm">View</button>
                        <button class="btn btn-secondary btn-sm">Mark as Read</button>
                    </div>
                </div>
                <span class="dot-indicator"></span>
            </div>
            <!-- Add more cards as needed -->
            <!-- Cancellation Card -->
            <div class="notification-card cancel">
                <span class="badge-cancel">Cancelled</span>
                <div class="notification-content">
                    <p>Your Reservation # 1234 has been cancelled.</p>
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
        </div>

        <hr />

        <h2>Read Notification(s)</h2>
        <div class="notification-container">
            <!-- Notification Card -->
            <div class="notification-card read">
                <div class="notification-content">
                    <p>Your Reservation # 1324 has been approved.</p>
                </div>
                <div class="notification-footer">
                    <p class="time">1d ago</p>
                    <button class="btn btn-primary btn-sm">View</button>
                </div>
            </div>
            <!-- Add more cards as needed -->
             
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

</script>
</body>
</html>