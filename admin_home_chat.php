<?php 
    include "role_access.php";
    include("connection.php");
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
            transition: margin-left 0.3s ease, width 0.3s ease;
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

        .contact-container {
            position: relative;
            width: 100%;
            background: rgb(29, 69, 104);
            overflow-x: auto;
            white-space: nowrap;
            padding: 10px 0;
            box-sizing: border-box;
            z-index: 0;
        }
        .user-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .user-link:hover .user-name {
            text-decoration: underline;
        }
        
        .user-list {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 250px;
        }

        .user {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            margin-right: 20px;
        }

        .user-pic {
            position: relative;
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .user-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .user-name {
            color: #fff;
            font-size: 14px;
            margin-top: 5px;
            text-align: center;
        }

        .new-message {
            position: absolute;
            top: 0;
            right: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 16px;
            height: 16px;
            color: red;
            border-radius: 50%;
        }

    .container {
        max-width: 80%;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .search-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column; 
        position: relative; 
        width: 100%; 
    }

    .search-box {
        width: 600px;
        max-width: 90%;
        border: 1px solid #ccc;
        border-radius: 25px;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative; /* Keep the input box aligned */
        z-index: 1;
    }

    .search-box input {
        flex-grow: 1;
        border: none;
        outline: none;
        font-size: 16px;
        background-color: transparent;
    }

    .search-box button {
        background-color: transparent;
        border: none;
        outline: none;
        cursor: pointer;
        color: #666;
    }
    .search-suggestions {
        position: absolute;
        top: 100%; /* Place directly below the search box */
        width: 600px;
        max-width: 90%;
        background-color: #fff;
        border: 1px solid #ccc;
        border-top: none;
        border-radius: 0 0 25px 25px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 10px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 10;
    }

    .search-suggestion {
        padding: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .search-suggestion img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .search-suggestion:hover {
        background-color: #f5f5f5;
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

    #sidebar .badge-chat{
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
            padding: 0;
        }

        #hamburger {
            display: block; /* Show hamburger button on smaller screens */
        }
        .container {
            max-width: 100%;
        }

        .user-list {
            justify-content: space-evenly;
            margin-left: 0;
        }

        .user {
            margin: 10px;
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
        .contact-container{
            margin-top: 60px;
        }
        #main-content{
            padding-inline: 10px;
        }
        #userModal{
            margin-top: 25px;
            height: 80vh;
        }
    }
    @media (max-width: 576px) {
        #header{
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
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
                <a href="admin_home_chat.php" class="nav-link active text-white chat">Chat with Customer</a>
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

    <div class="contact-container">
        <div class="user-list">
        <?php
        $userlist = $pdo->query("
            SELECT 
                u.user_id, 
                u.firstname, 
                u.lastname, 
                u.role, 
                u.profile, 
                MAX(m.timestamp) AS max_timestamp, 
                MAX(CASE WHEN m.sender_id = u.user_id THEN m.msg END) AS latest_msg, 
                COUNT(CASE WHEN m.is_read_admin = 0 AND m.sender_id = u.user_id THEN 1 END) AS unread_count
            FROM users u
            LEFT JOIN message_tbl m 
                ON u.user_id = m.sender_id OR u.user_id = m.receiver_id
            WHERE u.role = 'user' AND m.msg_id
            GROUP BY u.user_id
            ORDER BY max_timestamp DESC
            LIMIT 10
        ");
    
        $users = $userlist->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user) {
            ?>
            <a href="admin_chats.php?user_id=<?php echo $user['user_id']; ?>" class="user-link">
                <div class="user">
                    <div class="user-pic">
                        <img src="<?php echo $user['profile']; ?>" alt="<?php echo htmlspecialchars(ucwords($user['firstname'])); ?>">
                        <?php if ($user['unread_count'] > 0) : ?>
                            <span class="new-message"><i class="fas fa-circle fa-beat"></i></span>
                        <?php endif; ?>
                    </div>
                    <div class="user-name"><?php echo htmlspecialchars(ucwords($user['firstname'] . " " . $user['lastname'])); ?></div>
                </div>
            </a>
            <?php
        }
        ?>
            <div class="user-link-more" data-bs-toggle="modal" data-bs-target="#userModal">
                <div class="user">
                    <div class="user-pic">
                        <img src="profile/default_photo.jpg" alt="More">
                    </div>
                    <div class="user-name">More Users+</div>
                </div>
            </div>
        </div>
    </div>

    <div id="main-content" class="container mt-1">
        <h2 class="text-center my-4">Finding a specific person?</h2>
        <div class="search-container">
            <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Search for users...">
                <i class="fas fa-search"></i>
            </div>
            <div class="search-suggestions" id="searchSuggestions" style="display: none;">
                <!-- Search suggestions will be populated here -->
            </div>
        </div>
    </div>
    
    <!-- User List Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Contact List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="userList" class="list-group">
                        <!-- User items will be injected here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
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
        document.querySelector('.user-link-more').addEventListener('click', function() {
            fetch('getUserList.php')
                .then(response => response.json())
                .then(users => {
                    const userListContainer = document.getElementById('userList');
                    userListContainer.innerHTML = ''; // Clear existing content
        
                    users.forEach(user => {
                        const userItem = document.createElement('div');
                        userItem.classList.add('list-group-item', 'd-flex', 'align-items-center');
        
                        userItem.innerHTML = `
                            <img src="${user.profile ? user.profile : 'profile/default_photo.jpg'}" alt="${user.firstname} ${user.lastname}" class="rounded-circle me-3" style="width: 50px; height: 50px;">
                            <div>
                                <a href="admin_chats.php?user_id=${user.user_id}" style="text-decoration: none; color: inherit;"><h5 class="mb-0 text-capitalize">${user.firstname} ${user.lastname}</h5>
                                <p class="mb-0 text-muted">${user.latest_msg ? user.latest_msg : 'No messages yet'}</p>
                                <span class="badge bg-primary">${user.unread_count} unread</span></a>
                            </div>
                        `;
                        userListContainer.appendChild(userItem);
                    });
                });
        });

        $(document).ready(function () {
        $('#searchInput').on('input', function () {
            const query = $(this).val().trim();

            if (query.length > 0) {
                $.ajax({
                    url: 'search_users.php',
                    method: 'GET',
                    data: { query: query },
                    success: function (response) {
                        const suggestions = JSON.parse(response);
                        let suggestionsHTML = '';

                        suggestions.forEach(user => {
                            suggestionsHTML += `<div class="search-suggestion" data-id="${user.user_id}">
                                ${user.firstname} ${user.lastname}
                            </div>`;
                        });

                        $('#searchSuggestions').html(suggestionsHTML).show();
                    },
                    error: function () {
                        $('#searchSuggestions').hide();
                    }
                });
            } else {
                $('#searchSuggestions').hide();
            }
        });

        // Hide suggestions on clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.search-container').length) {
                $('#searchSuggestions').hide();
            }
        });

        // Handle suggestion click
        $('#searchSuggestions').on('click', '.search-suggestion', function () {
            const userId = $(this).data('id'); // Get the user ID from the data-id attribute
            window.location.href = `admin_chats.php?user_id=${userId}`; // Redirect with the user ID
        });

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
