<?php 
    session_start();
    include("connection.php");
    include "role_access.php";
    checkAccess('admin');
    $userId = $_GET['user_id']; 

    $updateQuery = "UPDATE message_tbl SET is_read_admin = 1 WHERE sender_id = :sender_id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute([':sender_id' => $userId]);


    $stmt = $pdo->prepare("SELECT firstname, lastname, status FROM users WHERE user_id = :userId");
    $stmt->execute([':userId' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $firstname = htmlspecialchars(ucwords($user['firstname']));
        $lastname = htmlspecialchars(ucwords($user['lastname']));
        $status = $user['status'] == 1 ? '<i class="fa-solid fa-circle" style="color: #1ab106;"></i> Active Now' : '<i class="fa-solid fa-circle" style="color: #aea7a7;"></i> Offline';
    } else {
        // Default values if user is not found
        $firstname = 'User';
        $lastname = '';
        $status = 'Unknown';
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

        #sidebar span {
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
            z-index: 1000; /* Ensure sidebar is above other content */
        }

        header {
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

    .chat-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 15px;
    }

    .chat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
        color: #fff;
        border-radius: 8px;
    }

    .chat-header h3 {
        margin: 0;
    }

    .chat-area {
        margin-top: 20px;
        height: 55vh;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 15px;
        overflow-y: auto;
    }

    .message {
        display: flex;
        align-items: flex-end;
        margin-bottom: 15px;
        padding-bottom: 10px;
        position: relative;
    }

    .message.sent {
        justify-content: flex-end;
    }

    .message img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin: 0 10px;
    }

    .message-content {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 15px;
        word-wrap: break-word;
        background-color: #e9ecef; 
        color: #000; 
        position: relative;
    }

    .message.sent .message-content {
        background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
        color: #fff;
    }
    .message.received .message-content {
        background-color: #e9ecef;
        color: #000;
    }

    .message-timestamp {
        font-size: 0.8rem;
        color: #6c757d;
        position: absolute;
        bottom: 0;
        left: 10px;
        transform: translateY(100%); 
        white-space: nowrap; 
    }
    .message-timestamp-sender{
        font-size: 0.8rem;
        color: #6c757d;
        position: absolute;
        bottom: 0;
        right: 10px;
        transform: translateY(100%); 
        white-space: nowrap; 
    }

    .date-stamp {
        text-align: center;
        margin: 15px 0;
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: bold;
        position: relative;
    }

    .date-stamp span {
        background: #f8f9fa;
        padding: 5px 10px;
        border-radius: 15px;
        display: inline-block;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .chat-footer {
        display: flex;
        align-items: center;
        margin-top: 15px;
        gap: 10px;
    }

    .chat-footer textarea {
        resize: none;
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ced4da;
        min-height: 40px;
        max-height: 120px;
    }

    .chat-footer button {
        background-color: #001A3E;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
    }

    @media (max-width: 768px) {
        #header{
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
        }
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
        .message.sent {
            justify-content: flex-end;
        }
        .chat-header h3 {
            font-size: 18px;
        }

        .message-content {
            max-width: 85%;
        }

        .chat-footer textarea {
            font-size: 14px;
        }

        .contact-container {
            height: auto;
            padding: 10px;
        }

        .user-list {
            justify-content: space-evenly;
            margin-left: 0;
        }

        .user {
            margin: 10px;
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
                    <li><a class="nav-link text-white" href="pending_reservation.php">Pending Reservations</a></li>
                    <li><a class="nav-link text-white" href="approved_reservation.php">Approved Reservations</a></li>
                </ul>
            </li>
            <li>
                <a href="admin_notifications.php" class="nav-link text-white">Notifications</a>
            </li>
            <li>
                <a href="admin_home_chat.php" class="nav-link active text-white">Chat with Customer</a>
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
                    <li><a class="dropdown-item" href="account_settings.php">Account Settings</a></li>
                    <li><a class="dropdown-item" href="homepage_settings.php">Homepage Settings</a></li>
                </ul>
            </li>
        </ul>
        <hr>
        <a href="logout.php" class="nav-link text-white">Log out</a>
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
            WHERE u.role = 'user'
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
        </div>
    </div>

    <div id="main-content" class="container mt-1">
        <div class="chat-container">
            <!-- Chat Header -->
            <div class="chat-header">
                <h3><?php echo $firstname . ' ' . $lastname; ?></h3>
                <span><?php echo $status; ?></span>
            </div>

            <!-- Chat Messages -->
            <div class="chat-area" id="chat-area">
                <!-- Example Date Stamp -->
                <div class="date-stamp">
                    <span>Today</span>
                </div>
            </div>

            <!-- Chat Footer -->
            <div class="chat-footer">
                <textarea id="message-input" placeholder="Type a message..."></textarea>
                <button id="send-message">Send</button>
            </div>
        </div>
    </div>

    <script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
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

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        $(document).ready(function () {
            const userId = '<?php echo $userId; ?>';
            let isAutoScrollEnabled = true;
            function fetchMessages() {
                $.ajax({
                    url: 'fetch_messages.php',
                    method: 'GET',
                    data: { user_id: userId },
                    success: function (response) {
                        const messages = JSON.parse(response);
                        let chatHTML = '';
                        let lastDate = null;
                        const today = new Date().toISOString().split('T')[0]; 

                        messages.forEach((msg) => {
                            const [messageDate, messageTime] = msg.timestamp.split(' '); 
                
                            // Convert time to 12-hour format
                            const timeParts = messageTime.split(':');
                            let hours = parseInt(timeParts[0]);
                            const minutes = timeParts[1];
                            const ampm = hours >= 12 ? 'PM' : 'AM';
                            hours = hours % 12 || 12; 
                            const formattedTime = `${hours}:${minutes} ${ampm}`;
                    
                            // Check if the message date is different from the last processed date
                            if (messageDate !== lastDate) {
                                lastDate = messageDate;

                                const dateLabel = messageDate === today ? 'Today' : lastDate;
                                chatHTML += `
                                    <div class="date-stamp">
                                        <span>${dateLabel}</span>
                                    </div>`;
                            }

                            if (msg.role === 'admin') {
                                chatHTML += `
                                    <div class="message sent">
                                        <div class="message-content">
                                            ${msg.msg}
                                            <span class="message-timestamp-sender">Sent by ${capitalizeFirstLetter(msg.firstname)} ○ ${formattedTime}</span>
                                        </div>
                                    </div>`;
                            } else {
                                chatHTML += `
                                    <div class="message received">
                                        <img src="${msg.profile}" alt="Profile Picture">
                                        <div class="message-content">
                                            ${msg.msg}
                                            <span class="message-timestamp">${formattedTime}</span>
                                        </div>
                                    </div>`;
                            }
                        });

                        const chatArea = $('#chat-area');
                        const wasAtBottom = chatArea[0].scrollHeight - chatArea.scrollTop() === chatArea.outerHeight();

                        chatArea.html(chatHTML);

                        if (isAutoScrollEnabled || wasAtBottom) {
                            chatArea.scrollTop(chatArea[0].scrollHeight); // Scroll to bottom
                        }
                    }
                });
            }
            setInterval(fetchMessages, 2000);
            $('#send-message').click(function () {
                const message = $('#message-input').val().trim();

                if (message.length > 0) {
                    $.ajax({
                        url: 'send_message.php',
                        method: 'POST',
                        data: { user_id: userId, message: message },
                        success: function (response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                $('#message-input').val(''); // Clear input
                                fetchMessages(); // Refresh chat
                            } else {
                                alert('Error sending message.');
                            }
                        }
                    });
                }
            });

            // Monitor scroll position to detect manual backreading
            $('#chat-area').on('scroll', function () {
                const chatArea = $(this);
                const isAtBottom = chatArea[0].scrollHeight - chatArea.scrollTop() === chatArea.outerHeight();

                if (isAtBottom) {
                    isAutoScrollEnabled = true; 
                } else {
                    isAutoScrollEnabled = false; 
                }
            });

            fetchMessages();
        });


    </script>
</body>
</html>
