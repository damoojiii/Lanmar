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
        background-color: #001A3E;
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
        position: relative;
    }

    .message.sent .message-content {
        background-color: #001A3E;
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
        bottom: -18px;
        right: 10px;
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
        <li><a href="my-notification.php" class="nav-link text-white">Notification</a></li>
        <li><a href="chats.php" class="nav-link text-white active">Chat with Lanmar</a></li>
<<<<<<< HEAD
        <li><a href="my-feedback.php" class="nav-link text-white">Feedback</a></li>
=======
        <li><a href="#" class="nav-link text-white">Feedback</a></li>
>>>>>>> 1c551381ce41ccde0d9103a26e4879c5d91f3245
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
            <span>Active now</span>
        </div>

        <!-- Chat Messages -->
        <div class="chat-area" id="chat-area">
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

    $(document).ready(function () {
    const userId = '<?php echo $userId; ?>';
    let isAutoScrollEnabled = true; // Flag to control auto-scroll

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

                messages.forEach((msg, index) => {
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

                        // Display "Today" for today's messages, otherwise show the date
                        const dateLabel = messageDate === today ? 'Today' : lastDate;
                        chatHTML += `
                            <div class="date-stamp">
                                <span>${dateLabel}</span>
                            </div>`;
                    }

                    // Add message content
                    if (msg.role === 'admin') {
                        chatHTML += `
                            <div class="message received">
                                <img src="https://via.placeholder.com/40" alt="Profile Picture">
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
                url: 'send_message1.php',
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