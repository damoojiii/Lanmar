<?php
    include "role_access.php";
    include("connection.php");
    checkAccess('admin');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['bookingId'])){
        $bookingId = $_POST['bookingId'];
    
        try {
            // Prepare the SQL query to update the booking status
            $stmt = $pdo->prepare("UPDATE booking_tbl SET status = 'Cancelled' WHERE booking_id = :bookingId");
            $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    
            // Execute the query
            if ($stmt->execute()) {
                //echo "Booking Completed successfully!";
            } else {
                echo "<script> alert('Failed to update booking status.') </script>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }else {
        $bookingId = $_POST['bookingIdback'];    
        try {
            // Prepare the SQL query to update the booking status
            $stmt = $pdo->prepare("UPDATE booking_tbl SET status = 'Pending' WHERE booking_id = :bookingId");
            $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    
            // Execute the query
            if ($stmt->execute()) {
                $notification_sql = "INSERT INTO notification_tbl (booking_id, status, is_read_user, is_read_admin, timestamp) 
                        VALUES (:booking_id, 3, 0, 2, NOW())";
                $stmt_notification = $pdo->prepare($notification_sql);
                $stmt_notification->execute([
                ':booking_id' => $bookingId
                ]);
                //echo "Booking approved successfully!";
            } else {
                echo "<script> alert('Failed to update booking status.') </script>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
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
            transition: margin-left 0.3s ease, width 0.3s ease;/* Smooth transition for header */
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
        thead.custom-header, thead.custom-header th {
            background: linear-gradient(25deg,rgb(29, 69, 104),#19315D) !important;
            color: white !important;
        }
        .active>.page-link, .page-link.active {
            background-color: #004080;
            border-color: #004080;
        }
        .table-row {
        cursor: pointer;
        transition: background-color 0.2s;
        }

        .table-row:hover {
        background-color: #f1f1f1;
        }

        .status-badge {
        padding: 0.4em 0.8em;
        font-size: 0.9rem;
        border-radius: 12px;
        background-color: #B4E380;
        color: #1A5319;
        font-weight: bold;
        }
        .modal-body h6 {
        color: #19315D;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 5px;
        margin-bottom: 10px;
        }

        .modal-body p {
        font-size: 14px; /* Slightly smaller text for mobile */
        margin: 0;
        }
        .modal-mobile, .modal-mobile-remove{
            background-color: #d6d6d6;
            padding-block: 5px;
        }
        .modal-mobile-add{
            background-color: transparent;
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
            .modal-mobile, .modal-mobile-remove{
                padding-block: 2px;
            }
            .modal-mobile-remove{
                background-color: transparent;
            }
            .modal-mobile-add{
                background-color: #d6d6d6;
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
                    <li><a class="nav-link active text-white" href="approved_reservation.php">Approved Reservations</a></li>
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
    
    <!-- tabel fetch-->
    <?php
        $sql_solo = "
            SELECT 
                cancel_tbl.cancel_id, cancel_tbl.booking_id, cancel_tbl.cancellation_reason,
                booking_tbl.dateIn, booking_tbl.dateOut, booking_tbl.checkin, booking_tbl.checkout, booking_tbl.hours,
                users.firstname, users.lastname, users.contact_number, users.user_id
            FROM cancel_tbl
            LEFT JOIN booking_tbl ON cancel_tbl.booking_id = booking_tbl.booking_id
            LEFT JOIN users ON booking_tbl.user_id = users.user_id
            WHERE booking_tbl.status = 'Cancellation2' ORDER BY timestamp DESC
        ";
        $stmt = $pdo->prepare($sql_solo);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
     ?>
    
    <div id="main-content" class="">
        <div class="">
            <div class="main-container my-1">
                <h2 class="mb-4"><strong>Cancellation Forms</strong></h2>
                <div class="table-responsive">
                    <table class="table table-hover" id="example" style="width:100%">
                        <thead class="custom-header">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th class="d-none d-sm-table-cell">Contact No.</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($results)): ?>
                            <?php foreach ($results as $row): ?>
                                <tr class="table-row" data-bs-toggle="modal" data-bs-target="#reservationModal" data-cancel-id="<?php echo htmlspecialchars($row['cancel_id']); ?>"
                                data-booking-id="<?php echo htmlspecialchars($row['booking_id']); ?>">

                                    <td><?php echo htmlspecialchars($row['cancel_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['firstname'] . " " . $row['lastname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                    <td><?php if ($row["dateIn"] != $row["dateOut"] ) {
                                    echo date("F j, Y" , strtotime($row["dateIn"])) . " to " . date("F j, Y" , strtotime($row["dateOut"]));
                                    } else {
                                        echo date("F j, Y" , strtotime($row["dateIn"]));
                                    } ?></td>
                                    <td><?php 
                                    echo date("g:i A" , strtotime($row["checkin"])) . " to " . date("g:i A" , strtotime($row["checkout"]));
                                    ?></td>
                                    </td>
                            <?php endforeach; ?>
                        <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  
<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-bold" id="reservationModalLabel">Cancellation Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <h6 class="fw-bold text-secondary">Cancellation ID:</h6>
          <p id="reservation-id" class="px-3 rounded">#<span id="modalCId"></span></p>
        </div>

        <!-- Personal Information Section -->
        <div class="mb-4">
          <h6 class="fw-bold text-secondary">Personal Information</h6>
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <p><strong>Name:</strong> <span id="modalName"></span></p>
            </div>
            <div class="col-12 col-md-6">
              <p><strong>Contact No.:</strong> <span id="modalContact"></span></p>
            </div>
          </div>
        </div>

        <!-- Booking Details Section -->
        <div class="mb-4">
          <h6 class="fw-bold text-secondary">Booking Details</h6>
          <div class="row g-3 mb-2">
            <div class="col-12 col-md-6">
              <p><strong>Date:</strong> <span id="modalDateRange"></span></p>
            </div>
            <div class="col-12 col-md-6">
              <p><strong>Time:</strong> <span id="modalTimeRange"></span></p>
            </div>
          </div>
        </div>

        <!-- Reason Section with Emphasis -->
        <div class="mb-4">
          <h6 class="fw-bold text-secondary">Reason for Cancellation</h6>
          <div class="bg-light p-3 rounded border border-secondary">
            <p id="modalReason" class="mb-0 text-dark"></p>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-end">
        <!-- Approve Button -->
        <button type="button" class="btn btnCancelled btn-success me-2">
          <i class="fa-solid fa-check me-1"></i> Approve Cancellation
        </button>

        <!-- Reject Button -->
        <button type="button" class="btn btnPendingBack btn-danger">
          <i class="fa-solid fa-xmark me-1"></i> Reject
        </button>
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
document.addEventListener('DOMContentLoaded', () => {
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
    let bookingId;
    // Event delegation to handle row click events
    document.querySelector('tbody').addEventListener('click', function (event) {
        // Ensure the clicked element is a table row
        const row = event.target.closest('.table-row');
        if (row) {
            const cancelId = row.dataset.cancelId;
            bookingId = row.dataset.bookingId;

            //window.location.href = `cancellationfetch.php?cancel_id=${bookingId}`;
            
            // Fetch the booking details from the server
            fetch(`cancellationfetch.php?cancel_id=${cancelId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Booking not found');
                        return;
                    }

                    // Populate the modal with the fetched data
                    document.getElementById('modalCId').textContent = data.cancelId;
                    document.getElementById('modalName').textContent = data.name;
                    document.getElementById('modalContact').textContent = data.contact;
                    document.getElementById('modalDateRange').textContent = data.dateRange;
                    document.getElementById('modalTimeRange').textContent = data.timeRange;
                    document.getElementById('modalReason').textContent = data.reason;

                    // Show the modal
                    $('#reservationModal').modal('show');
                })
                .catch(error => console.error('Error fetching data:', error));
        }
    });


    const approvedButton = document.querySelector('.btnCancelled');
    const rejectedButton = document.querySelector('.btnPendingBack');

    if (approvedButton && rejectedButton) {
    // Function to handle the approval action
    approvedButton.addEventListener('click', function () {
        if (!bookingId) {
                console.error('Booking ID is not available');
                return;
            }

        const isConfirmed = confirm("Are you sure you want to cancelled this booking?");
        // Create the form element
        if(isConfirmed){
        const form = document.createElement('form');
        form.method = 'POST';  
        form.action = '';  

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'bookingId';
        input.value = bookingId;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();


        } else {
            console.log('Booking approval canceled');
        }
        });

        rejectedButton.addEventListener('click', function (event) {
            if (!bookingId) {
                console.error('Booking ID is not available');
                return;
            }

            const isConfirmed = confirm("Void this cancellation. Are you sure?");
            // Create the form element if confirmed
            if (isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';  
            form.action = '';  

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'bookingIdback';
            input.value = bookingId;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
            } else {
            console.log('Booking rejection canceled');
            }
        });
    } else {
        console.error('Error: The modal or buttons are not found.');
    }
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
