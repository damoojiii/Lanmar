<?php
include "role_access.php";
include("connection.php");
checkAccess('admin');

function executeQuery($sql, $param, $successMessage, $errorMessage) {
    global $conn;
    $query = $conn->prepare($sql);
    $query->bind_param("i", $param);
    $query->execute();
    $message = ($query->affected_rows > 0) ? $successMessage : $errorMessage;
    echo "<script>alert('$message');</script>";
}

// Block user
if (isset($_GET['bloid']) && isset($_GET['status'])) {
    if ($_GET['status'] == 0){
    $rid = intval($_GET['bloid']);
    $sql = "UPDATE users SET status='1' WHERE user_id=?";
    executeQuery($sql, $rid, 'Blocked successfully', 'No user found or already blocked.');
    }
    else{
        $rid = intval($_GET['bloid']);
        $sql = "UPDATE users SET status='0' WHERE user_id=?";
        executeQuery($sql, $rid, 'Unblocked successfully', 'No user found or already unblocked.');
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Delete user account
if (isset($_GET['delid'])) {
    $delid = intval($_GET['delid']);
    $sql = "DELETE FROM users WHERE user_id=?";
    executeQuery($sql, $delid, 'Account deleted successfully', 'No user found or already deleted.');

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
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
    <link rel="stylesheet" href="assets/DataTables/datatables.min.css" />
    
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
            z-index: 199; /* Ensure sidebar is above other content */
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
            margin-top: 25px; /* Add top margin for header */
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
            padding: 10px 15px;
            background-color: rgb(29, 69, 104);
            border: none;
            cursor: pointer;
            color: white;
        }

        .flex-container {
        display: flex;
        gap: 20px;
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

    .table-full-width {
        width: 100%;
    }

    .table-full-width th, .table-full-width td {
        padding: 8px; /* Optional: for spacing */
        text-align: left; /* Optional: for text alignment */
    }
    thead.custom-header, thead.custom-header th {
        background: linear-gradient(25deg,rgb(29, 69, 104),#19315D) !important;
        color: white !important;
    }
    button.delete:hover{
        background: #ef5353;
    }
    button.blocks:hover{
        background: grey;
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
        .modal-body h6 {
            font-size: 16px; /* Slightly larger headers for readability */
        }
        .table thead th {
            font-size: 0.8rem;
            padding: 0.5rem;
        }
        .table tbody td {
            font-size: 0.8rem;
            padding: 0.5rem;
        }
        .logout{
            margin-bottom: 3rem;
        }
    }
        /* Styles for devices with a maximum width of 576px */
@media (max-width: 576px) {

    .flex-container {
        display: flex;
        flex-direction: column; /* Stack items vertically */
    }

    .main-content {
        text-align: center;
        padding: 0;
        max-height: 100%;
    }

    .table-responsive {
        overflow-x: auto; /* Enable horizontal scrolling for tables */
    }

    .table {
        width: 100%;
        font-size: 12px; /* Reduce font size for better readability on small screens */
    }

    .table-row td {
        padding: 0.3rem; /* Adjust padding for compact layout */
    }

    .blocks,
    .delete {
        font-size: 10px;
        padding: 0.2rem 0.5rem; /* Smaller button size */
    }

    .fa-user,
    .fa-user-slash,
    .fa-trash-can {
        margin-right: 0; /* Adjust spacing between icons and text */
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
                <a href="admin_notifications.php" class="nav-link text-white target">Notifications</a>
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
                <a href="account_lists.php" class="nav-link active text-white">Account List</a>
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
    <?php
        $query = "SELECT user_id, CONCAT(firstname, ' ', lastname) AS full_name, email, contact_number , email_verify, status FROM users order by created_at desc";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div id="main-content" class="p-3">
        <div class="flex-container">
            <div class="main-content">
                <h2 class="text-center mb-3 mt-4"><strong>Account List</strong></h2>
            <div class="table-responsive">
                <table class="table table-full-width mt-4" id="example" style="width:100%">
                    <thead class="custom-header">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Verified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($results)): ?>
                        <?php foreach ($results as $row): ?>
                                <tr class="table-row">
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                    <td><?php echo (!empty($row['email_verify'])) ? 'Verified' : 'No';?></td>
                                    <td>
                                        
                                         
                                        <?php if($row['status'] == 0):?>
                                            <button class="blocks" onclick="if (confirm('Are you sure you want to unblock this user?')) { window.location.href='?bloid=<?php echo $row['user_id']; ?>&status=<?php echo $row['status']; ?>'; }">
                                        <?php else: ?>
                                            <button class="blocks" onclick="if (confirm('Are you sure you want to block this user?')) { window.location.href='?bloid=<?php echo $row['user_id']; ?>&status=<?php echo $row['status']; ?>'; }">
                                        <?php endif ?>
                                            <?php if($row['status'] == 0):?>
                                            <i class="fa-regular fa-user"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-user-slash"></i>
                                            <?php endif ?>
                                        </button>
                                        <button class="delete" onclick="if (confirm('Are you sure you want to delete this user?')) { window.location.href='?delid=<?php echo urlencode($row['user_id']); ?>'; }">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                        
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif ?>
                    </tbody>
                </table>
               </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/all.min.js"></script>
<script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>
<script src="assets/DataTables/datatables.min.js"></script>

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
    document.addEventListener('DOMContentLoaded', () => {
    function jsRenderCOL(data, type, row, meta) {
            var dataRender;
            if (data !== "dummy") {
                dataRender = "you as dummy";
            }
            return dataRender;
        }
        
        const tableIndex = new DataTable('#example', {
            columnDefs: [
                {
                    searchable: false,
                    orderable: false
                }
            ],
            order: [],
            paging: true,
            scrollY: '100%'
        });
    
    tableIndex.on('mouseenter', 'td', function () {
        let colIdx = tableIndex.cell(this).index().column;
    
        tableIndex
            .cells()
            .nodes()
            .each((el) => el.classList.remove('highlight'));
    
        tableIndex
            .column(colIdx)
            .nodes()
            .each((el) => el.classList.add('highlight'));
    });
});
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
