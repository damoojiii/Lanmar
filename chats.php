<?php 
    session_start();
    include("connection.php");
    include "role_access.php";
    checkAccess('user');
    $userId = $_SESSION['user_id']; 

    $updateQuery = "
    UPDATE message_tbl m
    JOIN users u ON m.sender_id = u.user_id
    SET m.is_read_user = 1
    WHERE u.role = 'admin' AND m.receiver_id = :userId
    ";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->execute(['userId' => $userId]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanmar Resort</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        height: 65vh;
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
        background-color: rgb(29, 69, 104);
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
        background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
    }
    #sidebar .badge-chat{
        background: linear-gradient(45deg,rgb(29, 69, 104),#19315D) !important;
    }

    @media (max-width: 768px) {
        #main-content{
            padding: 0;
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
    
        
    }
    @media (max-width: 430px){

    }
</style>


<body>
<!-- Sidebar -->
<div id="sidebar" class="d-flex flex-column p-3 text-white position-fixed vh-100">
    <a href="#" class="mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <span class="fs-4 logo">Lanmar Resort</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index1.php" class="nav-link text-white">Book Here</a>
        </li>
        <li><a href="my-reservation.php" class="nav-link text-white ">My Reservations</a></li>
        <li><a href="my-notification.php" class="nav-link text-white target">Notification </a></li>
        <li><a href="chats.php" class="nav-link text-white chat active">Chat with Lanmar</a></li>
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
    <div class="chat-container">
        <!-- Chat Header -->
        <div class="chat-header">
            <h3>Lanmar Resort</h3>
        </div>

        <!-- Chat Messages -->
        <div class="chat-area" id="chat-area">
            <div class="d-flex justify-content-center">
                <button id="load-more">Loading..</button>
            </div>
            
        </div>

        <!-- Chat Footer -->
        <div class="chat-footer">
            <textarea id="message-input" placeholder="Type a message..."></textarea>
            <button id="send-message">Send</button>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/all.min.js"></script>
<script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>

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

    $(document).ready(function() {
        function updateNotificationCount() {
            $.ajax({
                url: 'notification_count.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var notificationCount = data;
                    // Update the notification counter in the sidebar
                    var notificationLink = $('.nav-link.text-white.target');
                    if (notificationCount >= 1) {
                        notificationLink.html('Notification <span class="badge badge-notif bg-secondary"></span>');
                    }
                },
                error: function() {
                    console.log('Error retrieving notification count.');
                }
            });
        }
        function updateChatPopup() {
            $.ajax({
                url: 'chat_count.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var counter = data;
                    // Update the notification counter in the sidebar
                    var notificationLink = $('.nav-link.text-white.chat');
                    if (counter >= 1) {
                        notificationLink.html('Chat with Lanmar <span class="badge badge-chat bg-secondary"></span>');
                    }
                },
                error: function() {
                    console.log('Error retrieving notification count.');
                }
            });
        }
        updateNotificationCount();
        updateChatPopup();
        setInterval(updateNotificationCount, 5000);
        setInterval(updateChatPopup, 5000);
    });

    $(document).ready(function () {
    const userId = '<?php echo $userId; ?>';
    let isAutoScrollEnabled = true;
    let offset = 0;
    const limit = 20; 
    const chatArea = $('#chat-area');

    function fetchMessages(initialLoad = true) {
        $.ajax({
            url: 'fetch_messages.php',
            method: 'GET',
            data: { user_id: userId, offset: offset },
            success: function (response) {
                const messages = JSON.parse(response);
                let chatHTML = '';
                let lastDate = null;
                const today = new Date().toISOString().split('T')[0];
                let hasMoreMessages = false; 

                if (messages.length > limit) {
                    hasMoreMessages = true;
                }

                messages.forEach((msg) => {
                    const [messageDate, messageTime] = msg.timestamp.split(' ');

                    const timeParts = messageTime.split(':');
                    let hours = parseInt(timeParts[0]);
                    const minutes = timeParts[1];
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12 || 12;
                    const formattedTime = `${hours}:${minutes} ${ampm}`;

                    if (messageDate !== lastDate) {
                        lastDate = messageDate;
                        const dateLabel = messageDate === today ? 'Today' : lastDate;
                        chatHTML += `<div class="date-stamp"><span>${dateLabel}</span></div>`;
                    }

                    if (msg.role === 'admin') {
                        chatHTML += `
                            <div class="message received">
                                <img src="uploads/lanmar-pfp.jpg" alt="Profile Picture">
                                <div class="message-content">
                                    ${msg.msg}
                                    <span class="message-timestamp">${formattedTime}</span>
                                </div>
                            </div>`;
                    } else {
                        chatHTML += `
                            <div class="message sent">
                                <div class="message-content">
                                    ${msg.msg}
                                    <span class="message-timestamp">${formattedTime}</span>
                                </div>
                            </div>`;
                    }
                });

                if (!chatArea[0].classList.contains("active")) {
                    scrollToBottom();
                }

                const wasAtBottom = chatArea[0].scrollHeight - chatArea.scrollTop() === chatArea.outerHeight();

                if (initialLoad) {
                    chatArea.html(chatHTML);
                } else {
                    chatArea.prepend(chatHTML);
                }

                if (hasMoreMessages) {
                    $('#load-more').show();
                } else {
                    $('#load-more').hide();
                }
            }
        });
    }


    setInterval(fetchMessages, 2000);

    $('#send-message').click(function () {
        const message = $('#message-input').val().trim();

        if (message.length > 0) {
            $.ajax({
                url: 'send_message1.php',
                method: 'POST',
                data: { user_id: userId, message: message },
                success: function (response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        $('#message-input').val('');
                        scrollToBottom();
                    } else {
                        alert('Error sending message: ' + (result.error || 'Unknown error'));
                    }
                },
                error: function (xhr, status, error) {
                    alert('AJAX error: ' + error);
                }
            });
        }
    });

    $('#load-more').on('click', function () {
        offset += limit; 
        fetchMessages();
    });

    $('#chat-area').on('scroll', function () {
        const isAtBottom = (chatArea[0].scrollHeight - chatArea.scrollTop()) === chatArea.outerHeight();
        isAutoScrollEnabled = isAtBottom;
    });

    function scrollToBottom() {
        chatArea.scrollTop(chatArea[0].scrollHeight);
    }
});




</script>
</body>
</html>