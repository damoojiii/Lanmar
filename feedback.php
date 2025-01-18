<?php
    include "role_access.php";
    include("connection.php");
    checkAccess('admin');

    // Query featured feedbacks
    $featuredQuery = "SELECT f.feedback_id, f.comment, f.rating, f.is_featured, f.created_at, 
    u.firstname, u.lastname 
    FROM feedback_tbl f
    JOIN users u ON f.user_id = u.user_id
    WHERE f.is_featured = 1
    ORDER BY f.created_at DESC";
    $featuredStmt = $pdo->query($featuredQuery);
    $featuredFeedbacks = $featuredStmt->fetchAll(PDO::FETCH_ASSOC);

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $feedbacksPerPage = 9;
    $offset = ($page - 1) * $feedbacksPerPage;

    // Query non-featured feedbacks
    $nonFeaturedQuery = "
    SELECT f.feedback_id, f.comment, f.rating, f.is_featured, f.created_at, 
           u.firstname, u.lastname 
    FROM feedback_tbl f
    JOIN users u ON f.user_id = u.user_id
    WHERE f.is_featured = 0
    ORDER BY f.created_at DESC
    LIMIT $offset, $feedbacksPerPage"; // Added LIMIT for pagination

    $nonFeaturedStmt = $pdo->query($nonFeaturedQuery);
    $nonFeaturedFeedbacks = $nonFeaturedStmt->fetchAll(PDO::FETCH_ASSOC);

    // Query to get the total number of non-featured feedbacks for pagination
    $totalFeedbacksQuery = "SELECT COUNT(*) AS total_feedbacks FROM feedback_tbl WHERE is_featured = 0";
    $totalFeedbacksStmt = $pdo->query($totalFeedbacksQuery);
    $totalFeedbacks = $totalFeedbacksStmt->fetch(PDO::FETCH_ASSOC)['total_feedbacks'];

    // Calculate the total number of pages
    $totalPages = ceil($totalFeedbacks / $feedbacksPerPage);
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

        body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        }

        #sidebar .font-logo {
            font-family: 'nautigal';
            font-size: 50px !important;
        }

        #sidebar {
            width: 250px;
            position: fixed;
            top: 0; 
            height: 100vh;
            overflow-y: auto; 
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
            transition: transform 0.3s ease;
            z-index: 199; /* Ensure sidebar is above other content */
        }
        .font-logo-mobile{
            font-family: 'nautigal';
            font-size: 30px;
        }

        header {
            position: none;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            z-index: 199;
            display: flex;
            align-items: center;
            padding: 0 15px;
            transition: margin-left 0.3s ease, width 0.3s ease; /* Smooth transition for header */
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

    .main-content {
        flex: 1;
        padding: 25px;
        background-color: #ffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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


    .feedback-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: start;
        word-wrap: break-word; 
        word-break: break-word; 
    }

    .feedback-card {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        width: calc(33.333% - 20px); /* 3 cards per row */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .feedback-card h4 {
        margin: 0 0 10px;
        font-size: 18px;
    }

    .rating {
        color: #ffc107;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .feedback-card .feedback-text {
        font-size: 14px;
        color: #555;
        margin-bottom: 15px;
    }

    .button-container {
        display: flex;
        justify-content: flex-end;
        margin-top: auto; /* Push the button to the bottom */
    }
    .button-container .btn-primary{
        background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
    }

    .feedback-card button {
        margin: 0;
    }

    .feedback-line {
        margin: 40px 0;
    }
    .pagination {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }

    .pagination a {
        text-decoration: none;
        padding: 5px 10px;
        background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
        color: white;
        border-radius: 5px;
    }

    .pagination a:hover {
        background-color: #007bff;
    }

    .pagination .prev, .pagination .next {
        font-weight: bold;
    }

    .pagination span {
        align-self: center;
    }

    /* Responsiveness for mobile devices */
    @media (max-width: 1024px) {
        .feedback-card {
            width: calc(50% - 20px); /* 2 cards per row on tablets */
        }
    }
    @media (max-width: 768px){
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
        .feedback-card {
            width: 100%; 
        }
        .logout{
            margin-bottom: 3rem;
        }
    }
    </style>
</head>
<body>
    <!-- Header -->
    <header id="header" class="bg-light shadow-sm">
        <button id="hamburger" class="btn btn-primary" onclick="toggleSidebar()">
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
                <a href="admin_notifications.php" class="nav-link text-white target">Notifications</a>
            </li>
            <li>
                <a href="admin_home_chat.php" class="nav-link text-white chat">Chat with Customer</a>
            </li>
            <li>
                <a href="reservation_history.php" class="nav-link text-white">Reservation History</a>
            </li>
            <li>
                <a href="feedback.php" class="nav-link active text-white">Guest Feedback</a>
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
        <div class="feedback-page">
            <!-- Featured Feedbacks Section -->
            <h2><strong>Selected Featured Feedbacks</strong></h2>
            <div class="feedback-container">
                <?php if (!empty($featuredFeedbacks)): ?>
                    <?php foreach ($featuredFeedbacks as $feedback): ?>
                        <div class="feedback-card selected">
                            <h4><?= htmlspecialchars(ucwords($feedback['firstname'] . ' ' . $feedback['lastname'])); ?></h4>
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa-solid fa-star" style="color: <?= $i <= $feedback['rating'] ? '#FFD43B' : '#CCC'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p><strong><?= ['Not Good', 'Bad', 'Okay', 'Very Good', 'Amazing'][$feedback['rating'] - 1]; ?></strong></p>
                            <p class="feedback-text"><?= htmlspecialchars($feedback['comment']); ?></p>
                            <div class="button-container">
                                <button class="btn btn-secondary btn-sm" onclick="updateFeature(<?= $feedback['feedback_id']; ?>, 0)">Remove</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No featured feedbacks added yet.</p>
                <?php endif; ?>
            </div>

            <hr class="feedback-line"/>

            <!-- Non-Featured Feedbacks Section -->
            
            <h2><strong>Feedbacks</strong></h2>
            <div class="feedback-container">
                <?php if (!empty($nonFeaturedFeedbacks)): ?>
                    <?php foreach ($nonFeaturedFeedbacks as $feedback): ?>
                        <div class="feedback-card">
                            <h4><?= htmlspecialchars(ucwords($feedback['firstname'] . ' ' . $feedback['lastname'])); ?></h4>
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa-solid fa-star" style="color: <?= $i <= $feedback['rating'] ? '#FFD43B' : '#CCC'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p><strong><?= ['Not Good', 'Bad', 'Okay', 'Very Good', 'Amazing'][$feedback['rating'] - 1]; ?></strong></p>
                            <p class="feedback-text"><?= htmlspecialchars($feedback['comment']); ?></p>
                            <div class="button-container">
                                <button class="btn btn-primary btn-sm" onclick="updateFeature(<?= $feedback['feedback_id']; ?>, 1)">Add</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No feedbacks found.</p>
                <?php endif; ?>
            </div>

            <!-- Pagination Controls -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1; ?>" class="prev">Previous</a>
                <?php endif; ?>

                <span>Page <?= $page; ?> of <?= $totalPages; ?></span>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1; ?>" class="next">Next</a>
                <?php endif; ?>
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
    
    function updateFeature(feedbackId, isFeatured) {
        fetch('get-featured-count.php')
            .then(response => response.json())
            .then(data => {
                if (data.count >= 3 && isFeatured === 1) {
                    alert('You can only feature up to three feedbacks at a time.');
                } else {
                    fetch('update-feature.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ feedback_id: feedbackId, is_featured: isFeatured })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Feedback updated successfully!');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('Failed to update feedback. Please try again.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            })
            .catch(error => console.error('Error fetching featured count:', error));
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
