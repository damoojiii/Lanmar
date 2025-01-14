<?php
    session_start();
    include("connection.php");
    include "role_access.php";
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
    <link rel="stylesheet" href="assets/DataTables/datatables.min.css" />
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

        #sidebar span {
            font-family: 'nautigal';
            font-size: 30px !important;
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
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background-color: #001A3E;
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

    .settings-form button, 
        .save-btn {
            border-radius: 10px !important;  
            padding: 13px 30px;
            background-color: #03045e;
            border: none;
            cursor: pointer;
            color: white;
        }

        thead.custom-header, thead.custom-header th {
            background-color: #19315D !important;
            color: white !important;
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

        @media (max-width: 576px) {
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
        }

        @media (max-width: 768px){
            #header{
                background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
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
        }
        @media (max-width: 576px) {
            #header{
                background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
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
                    <li><a class="nav-link active text-white" href="approved_reservation.php">Approved Reservations</a></li>
                </ul>
            </li>
            <li>
                <a href="admin_notifications.php" class="nav-link text-white">Notifications</a>
            </li>
            <li>
                <a href="admin_home_chat.php" class="nav-link text-white">Chat with Customer</a>
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
    
    <!-- tabel fetch-->
    <?php
        $sql_solo = "
            SELECT 
                cancel_tbl.cancel_id, cancel_tbl.booking_id, cancel_tbl.cancellation_reason,
                booking_tbl.booking_id, booking_tbl.dateIn, booking_tbl.dateOut, booking_tbl.checkin, booking_tbl.checkout, booking_tbl.hours,
                users.firstname, users.lastname, users.contact_number, users.user_id
            FROM cancel_tbl
            LEFT JOIN booking_tbl ON cancel_tbl.booking_id = booking_tbl.booking_id
            LEFT JOIN users ON booking_tbl.user_id = users.user_id
            WHERE booking_tbl.status IN ('Cancellation1','Cancellation2') ORDER BY timestamp DESC
        ";
        $stmt = $pdo->prepare($sql_solo);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
     ?>
    
    <div id="main-content" class="">
        <div class="">
            <div class="main-container my-5">
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
                                <tr class="table-row" data-bs-toggle="modal" data-bs-target="#reservationModal" data-booking-id="<?php echo htmlspecialchars($row['cancel_id']); ?>"
                                data-user-id="<?php echo htmlspecialchars($row['user_id']); ?>">

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
      <div class="modal-header">
        <h5 class="modal-title" id="reservationModalLabel">Reservation Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Reservation ID -->
        <div class="mb-4" >
          <h6 class="fw-bold">Reservation ID:</h6>
          <p id="reservation-id" class="py-1" style="background-color: #d6d6d6;"> #<span id="modalCId"></span> </p>
        </div>

        <!-- Personal Information Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Personal Information</h6>
          <div class="row g-2" style="background-color: #d6d6d6;">
            <div class="col-12 col-md-4">
              <p><strong>Name:</strong> <span id="modalName"></span></p>
            </div>
            <div class="col-12 col-md-4">
              <p><strong>Contact No.:</strong> <span id="modalContact"></span></p>
            </div>
          </div>
        </div>

        <!-- Booking Details Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Booking Details</h6>
          <div class="row g-2 mb-2" style="background-color: #d6d6d6;">
            <div class="col-12 col-md-5">
              <p><strong>Date:</strong> <span id="modalDateRange"></span></p>
            </div>
            <div class="col-12 col-md-4">
              <p><strong>Time:</strong> <span id="modalTimeRange"></span></p>
            </div>
          </div>
          <div class="row g-2 mb-2" style="background-color: #d6d6d6;">
            <div class="col-12 col-md-3">
              <p><strong>Reason:</strong> <span id="modalReason"></span></p>
            </div>
          </div>
        </div>


      <div class="modal-footer d-flex justify-content-end">
            <!-- Check Button -->
            <button type="button" class="btn btnCompleted" style="width:auto; background-color: #1daa2d; border-color: #1daa2d; color: #ffffff;">
                <i class="fa-solid fa-check" style="color: #ffffff;"></i> Complete
            </button>

            <!-- Cancel Button -->
            <button type="button" class="btn btnCancel" style="width:auto; background-color: #ee1717; border-color: #ee1717; color: #ffffff;">
                <i class="fa-solid fa-xmark" style="color: #ffffff;"></i> Cancel
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
});

document.addEventListener('DOMContentLoaded', () => {
    // Event delegation to handle row click events
    document.querySelector('tbody').addEventListener('click', function (event) {
        // Ensure the clicked element is a table row
        const row = event.target.closest('.table-row');
        if (row) {
            const bookingId = row.dataset.bookingId;

            //window.location.href = `cancellationfetch.php?cancel_id=${bookingId}`;
            
            // Fetch the booking details from the server
            fetch(`cancellationfetch.php?cancel_id=${bookingId}`)
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
});

</script>
</body>
</html>
