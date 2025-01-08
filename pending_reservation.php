<?php
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    session_start();
    include "role_access.php";
    checkAccess('admin');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
        if (isset($_POST['bookingId'])){
        $bookingId = $_POST['bookingId'];
    
        // Here, connect to the database and update the booking status
        // Example using PDO for a MySQL database
    
        try {
            // Prepare the SQL query to update the booking status
            $stmt = $pdo->prepare("UPDATE booking_tbl SET status = 'Approved' WHERE booking_id = :bookingId");
            $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    
            // Execute the query
            if ($stmt->execute()) {
                //echo "Booking approved successfully!";
            } else {
                echo "Failed to update booking status.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $bookingId = $_POST['bookingIdReject'];
    
        // Here, connect to the database and update the booking status
        // Example using PDO for a MySQL database
    
        try {
            // Prepare the SQL query to update the booking status
            $stmt = $pdo->prepare("UPDATE booking_tbl SET status = 'Rejected' WHERE booking_id = :bookingId");
            $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    
            // Execute the query
            if ($stmt->execute()) {
                //echo "Booking approved successfully!";
            } else {
                echo "Failed to update booking status.";
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
            background: #001A3E;
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
        background-color: #fbe9a1;
        color: #856404;
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


    </style>
</head>
<body>
    <!-- Header -->
    <header id="header">
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
                <a href="admin_home_chat.php" class="nav-link text-white">Chat with Customer</a>
            </li>
            <li>
                <a href="reservation_history.php" class="nav-link text-white">Reservation History</a>
            </li>
            <li>
                <a href="feedback.php" class="nav-link text-white">Guest Feedback</a>
            </li>
            <li>
                <a href="reports.php" class="nav-link text-white">Reports</a>
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
                    <li><a class="nav-link text-white" href="homepage_settings.php">Homepage Settings</a></li>
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
                booking_tbl.booking_id, booking_tbl.dateIn, booking_tbl.dateOut, booking_tbl.checkin, booking_tbl.checkout, booking_tbl.hours, booking_tbl.status,
                reservationType_tbl.reservation_type,
                pax_tbl.adult, pax_tbl.child, pax_tbl.pwd,
                bill_tbl.total_bill, bill_tbl.balance, bill_tbl.pay_mode,
                users.firstname, users.lastname, users.contact_number, users.user_id
            FROM booking_tbl
            LEFT JOIN reservationType_tbl ON booking_tbl.reservation_id = reservationType_tbl.id
            LEFT JOIN pax_tbl ON booking_tbl.pax_id = pax_tbl.pax_id
            LEFT JOIN bill_tbl ON booking_tbl.bill_id = bill_tbl.bill_id
            LEFT JOIN users ON booking_tbl.user_Id = users.user_id
            WHERE booking_tbl.status = 'Pending' ORDER BY booking_id DESC
        ";
        $stmt_solo = $pdo->prepare($sql_solo);
        $stmt_solo->execute();
        $results = $stmt_solo->fetchAll(PDO::FETCH_ASSOC);
     ?>
    
    <div id="main-content" class="">
        <div class="">
            <div class="main-container my-5">
                <h2 class="mb-4"><strong>Pending Reservations</strong></h2>
                <div class="table-responsive">
                    <table class="table table-hover" id="example" style="width:100%">
                        <thead class="custom-header">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th class="d-none d-sm-table-cell">Contact No.</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th class="d-none d-md-table-cell">Total No. of Pax</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($results)): ?>
                            <?php foreach ($results as $row): ?>
                                <tr class="table-row" data-bs-toggle="modal" data-bs-target="#reservationModal" data-booking-id="<?php echo htmlspecialchars($row['booking_id']); ?>"
                                data-user-id="<?php echo htmlspecialchars($row['user_id']); ?>">

                                    <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
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
                                    <td class="d-none d-md-table-cell"><?php $totalPax = $row['adult'] + $row['child'] + $row['pwd'];
                                    echo htmlspecialchars($totalPax); ?></td>
                                    <?php 
                                    switch ($row['status']) {
                                    case "Approved":
                                        $class = "approved";
                                        break;
                                    case "Pending":
                                        $class = "pending";
                                        break;
                                    case "Cancel":
                                        $class = "cancel";
                                        break;
                                    case "Completed":
                                        $class = "completed";
                                        break;
                                    }
                                    ?>
                                    <td><span class="status-badge <?php echo htmlspecialchars($class); ?> "><?php echo htmlspecialchars($row['status']); ?></span></td>
                            <?php endforeach; ?>
                            <?php elseif(empty($results)):?>
                                <td colspan="7" style="text-align: center;">No Approved Reservations</td>
                                </tr>
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
        <div class="mb-4">
          <h6 class="fw-bold">Reservation ID:</h6>
          <p id="reservation-id"> #<span id="modalBookingId"></span> </p>
        </div>

        <!-- Personal Information Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Personal Information</h6>
          <div class="row g-2">
            <div class="col-12 col-md-4">
              <p><strong>Name:</strong> <span id="modalName"></span></p>
            </div>
            <div class="col-12 col-md-4">
              <p><strong>Contact No.:</strong> <span id="modalContact"></span></p>
            </div>
            <div class="col-12 col-md-4">
              <p><strong>Gender:</strong> Female</p>
            </div>
          </div>
        </div>

        <!-- Booking Details Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Booking Details</h6>
          <div class="row g-2 mb-2">
            <div class="col-12 col-md-5">
              <p><strong>Date:</strong> <span id="modalDateRange"></span></p>
            </div>
            <div class="col-12 col-md-4">
              <p><strong>Time:</strong> <span id="modalTimeRange"></span></p>
            </div>
            <div class="col-12 col-md-3">
              <p><strong>Total Hours:</strong> <span id="modalHours"></span></p>
            </div>
          </div>
          <div class="row g-2 mb-2">
            <div class="col-4 col-md-3">
              <p><strong>Adults:</strong> <span id="modalAdults"></span></p>
            </div>
            <div class="col-4 col-md-3">
              <p><strong>Children:</strong> <span id="modalChild"></span></p>
            </div>
            <div class="col-4 col-md-3">
              <p><strong>PWD:</strong> <span id="modalPwd"></span></p>
            </div>
            <div class="col-12 col-md-3">
              <p><strong>Total Pax:</strong> <span id="modalTotalPax"></span></p>
            </div>
          </div>
          <div class="row g-2 mb-2">
            <div><p><strong>Reservation Type:</strong> <span id="modalRoomType"></p></div>
          </div>
          <div class="row g-2">
            <div><p><strong>Rooms:</strong> <span id="modalRooms" class="row g-2"></p></div>
          </div>
        </div>

        <!-- Booking Details Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Special Requests</h6>
          <div class="row g-2">
            <div class="col-12 col-md-4">
              <p><strong>Additionals:</strong> <span id="modalAdds"></p>
            </div>
          </div>
        </div>

        <!-- Payment Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Payment</h6>
          <div class="row g-2">
            <div class="col-12 col-md-4">
              <p><strong>Payment Method:</strong> <span id="modalPaymode"></span></p>
            </div>
            <div class="col-6 col-md-4">
              <p><strong>Total Price:</strong> <span id="modalTotalBill"></span></p>
            </div>
            <div class="col-6 col-md-4">
              <p><strong>Balance Remaining:</strong> <span id="modalBalance"></span></p>
            </div>
            <div class="col-12 col-md-4">
              <p><strong>Reference Number:</strong> <span id="modalrefNum"></span></p>
            </div>
            <div id="modalProof"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-end">
        
            <button id="chatsbutton" type="button" class="btn" style="width:50px; background-color: #19315D; border-color: #19315D;">
                <i class="fa-solid fa-message" style="color: #ffffff;"></i>
            </button>

            <button type="button" class="btn" id="editButton" style="width:50px; background-color: #19315D; border-color: #19315D;">
                <i class="fa-solid fa-pen" style="color: #ffffff;"></i>
            </button>

            <!-- Check Button -->
            <button type="button" class="btn btnApproved" style="width:50px; background-color: #1daa2d; border-color: #1daa2d;">
                <i class="fa-solid fa-check" style="color: #ffffff;"></i>
            </button>

            <!-- Cancel Button -->
            <button type="button" class="btn btnReject" style="width:50px; background-color: #ee1717; border-color: #ee1717;">
                <i class="fa-solid fa-xmark" style="color: #ffffff;"></i>
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
                orderable: false,
                targets: 0
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
    let bookingIds;
    let userID;
    document.querySelector('tbody').addEventListener('click', function (event) {
      // Ensure the clicked element is a table row
      const row = event.target.closest('.table-row');
      if (row) {
        const userId = row.dataset.userId;
        userID = userId;
          const bookingId = row.dataset.bookingId; // Get the booking ID
        bookingIds = bookingId;

          //window.location.href = `my-reservation-fetch.php?booking_id=${bookingId}`;
          
          // Fetch the booking details from the server
          fetch(`my-reservation-fetch.php?booking_id=${bookingId}`)
              .then(response => response.json())
              .then(data => {
                  if (data.error) {
                      console.error('Booking not found');
                      return;
                  }

                  // Populate the modal with the fetched data
                  document.getElementById('modalBookingId').textContent = data.bookingId;
                  document.getElementById('modalName').textContent = data.name;
                  document.getElementById('modalContact').textContent = data.contact;
                  document.getElementById('modalDateRange').textContent = data.dateRange;
                  document.getElementById('modalTimeRange').textContent = data.timeRange;
                  document.getElementById('modalHours').textContent = data.hours;
                  document.getElementById('modalAdults').textContent = data.adult;
                  document.getElementById('modalChild').textContent = data.child;
                  document.getElementById('modalPwd').textContent = data.pwds;
                  document.getElementById('modalTotalPax').textContent = data.totalPax;
                  document.getElementById('modalRoomType').textContent = data.type;
                  // Optionally, loop over the rooms and display them in the modal (if needed)
                  const roomsContainer = document.getElementById('modalRooms');
                  roomsContainer.innerHTML = ''; // Clear existing rooms
                  let ronum = 1;
                  data.roomName.forEach(room => {
                      const roomElement = document.createElement('div');
                      roomElement.classList.add('room-detail','col-3','col-md-3');
                      roomElement.innerHTML = `
                          <strong>Room ${ronum}:</strong> ${room.roomName}<br>
                      `;
                      roomsContainer.appendChild(roomElement);
                      ronum++;
                  });
                  document.getElementById('modalAdds').textContent = data.additional;
                  document.getElementById('modalPaymode').textContent = data.paymode;
                  document.getElementById('modalTotalBill').textContent = data.totalBill;
                  document.getElementById('modalBalance').textContent = data.balance;
                  const modalBody = document.getElementById('modalProof');
                    modalBody.innerHTML = `
                    <a href="${data.imageProof}" target="_blank">View image</a>
                    `

                  

                  // Show the modal
                  $('#reservationModal').modal('show');
              })
              .catch(error => console.error('Error fetching data:', error));
      }
  });
  function editBooking() {
        if (bookingIds) { // Make sure bookingId is set
            // Navigate to the cancellation page with the bookingId
            window.location.href = `edit_reservation.php?id=${bookingIds}`;
        } else {
            console.log("No bookingId found!");
        }
    }
    document.getElementById('editButton').addEventListener('click', editBooking);
    document.getElementById("chatsbutton").onclick = function() {
     // Replace with your dynamic user_id value
    const newUrl = `admin_chats.php?user_id=${userID}`;
    window.location.href = newUrl; // Redirects to the new URL
};
const approvedButton = document.querySelector('.btnApproved');
const rejectedButton = document.querySelector('.btnReject');
  const bookingIdElement = document.getElementById('modalBookingId');

  if (approvedButton && bookingIdElement) {
    // Function to handle the approval action
    approvedButton.addEventListener('click', function () {
        event.preventDefault();
      const bookingId = bookingIdElement.textContent.trim();
      
      const isConfirmed = confirm("Are you sure you want to approve this booking?");

      // Create the form element
      if(isConfirmed){
      const form = document.createElement('form');
      form.method = 'POST';  // Use POST method
      form.action = '';  // Submitting to the same page

      // Create a hidden input field for the bookingId
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'bookingId';
      input.value = bookingId;
      // Append the input field to the form
      form.appendChild(input);
      // Append the form to the body
      document.body.appendChild(form);
      // Submit the form
      form.submit();
    } else {
        // If the user cancels (clicks "Cancel"), do nothing
        console.log('Booking approval canceled');
      }
    });
  } else {
    console.error('Error: The modal elements are not found.');
  }

  if (rejectedButton && bookingIdElement) {
  // Function to handle the rejection action
  rejectedButton.addEventListener('click', function (event) {
    event.preventDefault();
    const bookingId = bookingIdElement.textContent.trim();

    const isConfirmed = confirm("Are you sure you want to reject this booking?");

    // Create the form element if confirmed
    if (isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';  // Use POST method
      form.action = '';  // Submitting to the same page

      // Create a hidden input field for the bookingId
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'bookingIdReject';
      input.value = bookingId;

      // Append the input field to the form
      form.appendChild(input);

      // Append the form to the body
      document.body.appendChild(form);

      // Submit the form
      form.submit();
    } else {
      // If the user cancels (clicks "Cancel"), do nothing
      console.log('Booking rejection canceled');
    }
  });
} else {
  console.error('Error: The modal elements are not found.');
}
});


</script>
</body>
</html>
