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

    <style>
        @font-face {
            font-family: 'nautigal';
            src: url(font/TheNautigal-Regular.ttf);
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

    .form-section {
        margin-bottom: 20px;
    }
    .form-section h4 {
        margin-bottom: 15px;
        font-weight: bold;
        color: #343a40;
    }
    .btn-group {
        text-align: center;
        margin-top: 20px;
    }

        

    @media (max-width: 576px) {

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
                    <li><a class="dropdown-item" href="account_settings.php">Account Settings</a></li>
                    <li><a class="dropdown-item" href="homepage_settings.php">Homepage Settings</a></li>
                </ul>
            </li>
        </ul>
        <hr>
        <a href="logout.php" class="nav-link text-white">Log out</a>
    </div>
    
    <div id="main-content" class="">
        <div class="">
            <div class="main-container my-5">
                <h2 class="text-center mb-4">Edit Reservation</h2>
                <form action="update_reservation.php" method="POST">
                    <?php 
                    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                        if (isset($_GET['id']) && !empty($_GET['id'])) {
                            $bookingId = $_GET['id'];
                            // Fetch the booking details
                            $sql = " SELECT booking_tbl.booking_id, booking_tbl.dateIn, booking_tbl.dateOut, booking_tbl.checkin, booking_tbl.checkout, booking_tbl.hours, booking_tbl.reservation_id, reservationType_tbl.reservation_type, pax_tbl.adult, pax_tbl.child, pax_tbl.pwd, bill_tbl.total_bill, bill_tbl.balance, bill_tbl.pay_mode, room_tbl.room_Id, room_tbl.room_name, users.firstname, users.lastname, users.contact_number 
                                    FROM booking_tbl 
                                    LEFT JOIN reservationType_tbl ON booking_tbl.reservation_id = reservationType_tbl.id 
                                    LEFT JOIN pax_tbl ON booking_tbl.pax_id = pax_tbl.pax_id 
                                    LEFT JOIN bill_tbl ON booking_tbl.bill_id = bill_tbl.bill_id 
                                    LEFT JOIN room_tbl ON bill_tbl.bill_id = room_tbl.bill_id 
                                    LEFT JOIN users ON booking_tbl.user_Id = users.user_id 
                                    WHERE booking_tbl.booking_id = :bookingId ";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
                            $stmt->execute();
                            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
                            if ($reservation): ?>
                                <input type="hidden" id="booking-id" name="booking_id" value="<?= htmlspecialchars($reservation['booking_id']); ?>">
                                <!-- Customer Information -->
                                <div class="form-section">
                                    <h4>Customer Information</h4>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <label class="form-label">Customer Name</label>
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($reservation['firstname'] . ' ' . $reservation['lastname']); ?>" readonly>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label class="form-label">Contact Number</label>
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($reservation['contact_number']); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- Booking Details -->
                                <div class="form-section">
                                    <h4>Booking Details</h4>
                                    <div class="row">
                                        <div class="col-sm-10 col-md-3">
                                            <label class="form-label">Date In</label>
                                            <input type="text" name="dateIn" class="form-control" id="date-in" value="<?= htmlspecialchars($reservation['dateIn']); ?>" placeholder="Select a date" readonly>
                                        </div>
                                        <div class="col-sm-10 col-md-3">
                                            <label class="form-label">Date Out</label>
                                            <input type="text" name="dateOut" class="form-control" id="date-out" value="<?= htmlspecialchars($reservation['dateOut']); ?>" placeholder="Select a date" readonly>
                                        </div>
                                        <div class="col-sm-10 col-md-2">
                                            <label class="form-label">Check-In Time</label>
                                            <select name="checkin" class="form-control" id="checkin-time">
                                                <option value="<?= htmlspecialchars($reservation['checkin']); ?>" selected><?= date("g:i A", strtotime($reservation['checkin'])); ?></option>
                                                <!-- You can add other options here if necessary -->
                                            </select>
                                        </div>
                                        <div class="col-sm-10 col-md-2">
                                            <label class="form-label">Check-Out Time</label>
                                            <select name="checkout" class="form-control" id="checkout-time">
                                                <option value="<?= htmlspecialchars($reservation['checkout']); ?>" selected><?= date("g:i A", strtotime($reservation['checkout'])); ?></option>
                                                <!-- You can add other options here if necessary -->
                                            </select>
                                        </div>
                                        <div class="col-sm-10 col-md-2">
                                            <label class="form-label">Total Hours</label>
                                            <input type="text" name="numhours" class="form-control" id="numhours" value="<?= htmlspecialchars($reservation['hours']); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- Room Information -->
                                <div class="form-section">
                                <?php 
                                    $sql1 = "
                                        SELECT 
                                            booking_tbl.booking_id,
                                            room_tbl.bill_id, 
                                            room_tbl.room_name
                                        FROM booking_tbl
                                        LEFT JOIN room_tbl ON booking_tbl.bill_id = room_tbl.bill_id
                                        WHERE booking_tbl.booking_id = :id
                                    ";
                                    $stmt1 = $pdo->prepare($sql1);
                                    $stmt1->bindParam(":id", $bookingId, PDO::PARAM_INT);
                                    $stmt1->execute();
                                    $rows = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    $room_data = [];
                                    if (!empty($rows)) {
                                        foreach ($rows as $row) {
                                            $room_data[] = [
                                                'room_name' => $row['room_name'],
                                                'bill_id' => $row['bill_id'] 
                                            ];
                                        }
                                    }

                                    $sql2 = "SELECT * FROM rooms ORDER BY room_name ASC";
                                    $stmt2 = $pdo->prepare($sql2);
                                    $stmt2->execute();
                                    $roomsList = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                    <h4>Room Information</h4>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-3">
                                            <label class="form-label">Add Room</label>
                                            <div class="input-group">
                                                <select class="form-select" id="room-dropdown">
                                                    <option value="" selected hidden>Select a room</option>
                                                    <?php foreach ($roomsList as $room): ?>
                                                        <option value="<?= htmlspecialchars($room['room_id']); ?>"><?= htmlspecialchars($room['room_name']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="button" class="btn btn-success" id="add-room-button">Add</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3" id="selected-rooms">
                                        <?php foreach ($room_data as $room): ?>
                                            <div class="col-sm-12 col-md-3 room-item">
                                                <label class="form-label">Room</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" value="<?= htmlspecialchars($room['room_name']); ?>" readonly>
                                                    <button type="button" class="btn btn-danger remove-room" data-bill-id="<?= htmlspecialchars($room['bill_id']); ?>">Remove</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <!-- Pax Information -->
                                <div class="form-section">
                                    <h4>Pax Information</h4>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-2">
                                            <label class="form-label">Adults</label>
                                            <input type="number" name="adult" class="form-control" value="<?= htmlspecialchars($reservation['adult']); ?>">
                                        </div>
                                        <div class="col-sm-12 col-md-2">
                                            <label class="form-label">Children</label>
                                            <input type="number" name="child" class="form-control" value="<?= htmlspecialchars($reservation['child']); ?>">
                                        </div>
                                        <div class="col-sm-12 col-md-2">
                                            <label class="form-label">PWD</label>
                                            <input type="number" name="pwd" class="form-control" value="<?= htmlspecialchars($reservation['pwd']); ?>">
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                        <label for="reservationType" class="form-label">Type of Reservation:</label>
                                            <select id="reservationType" name="reservationType" class="form-control" required>
                                                <?php 
                                                    $typelist = $pdo->query("SELECT * FROM reservationtype_tbl;");
                                                    $types = $typelist->fetchAll(PDO::FETCH_ASSOC);

                                                    $selectedType = $reservation['reservation_id'] ?? '';

                                                    foreach($types as $type) {
                                                        $typename = $type['reservation_type'];
                                                        $typeId = $type['id'];
                                                    
                                                        $isSelected = ($typeId == $selectedType) ? 'selected' : '';
                                                        
                                                        echo "<option value='$typeId' $isSelected>$typename</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Payment Information -->
                                <div class="form-section">
                                    <h4>Payment Information</h4>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <label class="form-label">Payment Method</label>
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($reservation['pay_mode']); ?>" readonly>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <label class="form-label">Total Bill</label>
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($reservation['total_bill']); ?>" readonly>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <label class="form-label">Balance</label>
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($reservation['balance']); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-danger">Reservation not found.</p>
                            <?php endif; 
                        } else { 
                            echo '<script>window.location="pending_reservation.php";</script>'; 
                            exit(); 
                        } 
                    } ?>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                        <a href="pending_reservation.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/all.min.js"></script>
<script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

    document.getElementById('add-room-button').addEventListener('click', function() {
        const dropdown = document.getElementById('room-dropdown');
        const selectedRoomId = dropdown.value;
        const selectedRoomText = dropdown.options[dropdown.selectedIndex].text;

        if (selectedRoomId) {
            // Create a new room item
            const roomItem = document.createElement('div');
            roomItem.classList.add('col-sm-12', 'col-md-3', 'room-item');

            roomItem.innerHTML = `
                <label class="form-label">Room</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="${selectedRoomText}" readonly>
                    <button type="button" class="btn btn-danger remove-room" data-bill-id="${selectedRoomId}">Remove</button>
                </div>
            `;

            // Add the new room item to the selected rooms container
            document.getElementById('selected-rooms').appendChild(roomItem);

            // Reset the dropdown
            dropdown.value = "";
        } else {
            alert('Please select a room to add.');
        }
    });


    document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove-room')) {
        const billId = e.target.getAttribute('data-bill-id');
        if (confirm('Are you sure you want to remove this room?')) {
            // Make an AJAX request to remove the room using billId
            fetch(`remove-room.php?bill_id=${billId}`, { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        e.target.closest('.col-md-4').remove(); // Remove the room from the UI
                    } else {
                        alert('Failed to remove room.');
                    }
                });
        }
    }
    });

let fp = '';
let fp1 = '';

document.addEventListener('DOMContentLoaded', function() {
  // Get the values from the HTML inputs after DOM is loaded
  const dateIn = document.querySelector('#date-in').value;
  const dateOut = document.querySelector('#date-out').value;
  const checkinTime = document.querySelector('#checkin-time').value;
  const checkoutTime = document.querySelector('#checkout-time').value;
  const bookingId = document.querySelector('#booking-id').value;
  console.log(bookingId);
 
  // Fetch booked time slots from the server
fetch(`fetch-editbooking.php?id=${bookingId}`)
    .then(response => response.json())
    .then(bookings => {
      bookings.forEach(booking => {
        const dateIn = booking.dateIn;
        const dateOut = booking.dateOut;
        const checkin = booking.checkin;
        const checkout = booking.checkout;
        
        // Calculate the cleanup end time by adding 2 hours to the checkout time
        const endTime = new Date(`${dateOut} ${checkout}`);
        const cleanupEndTime = new Date(endTime.getTime() + (cleanupTime * 60 * 60 * 1000) - (1 * 60 * 1000)); // Add 2 hours and subtract 1 minute
    
        // If dateIn and dateOut are the same
        if (dateIn === dateOut) {
            if (!bookedTimeSlots[dateIn]) {
                bookedTimeSlots[dateIn] = [];
            }
    
            bookedTimeSlots[dateIn].push({
                date: dateIn,
                start: checkin,
                end: formatTime24(cleanupEndTime) 
            });
        } 
        // If dateIn and dateOut are different
        else {
            const dateInObj = new Date(dateIn);
            const dateOutObj = new Date(dateOut);
            const intermediateDate = new Date(dateInObj);
    
            // Store booking for the check-in date
            if (!bookedTimeSlots[dateIn]) {
                bookedTimeSlots[dateIn] = [];
            }
            bookedTimeSlots[dateIn].push({
                date: dateIn,
                start: checkin,
                end: '23:30' 
            });
    
            // Block intermediate days fully between dateIn and dateOut
            while (intermediateDate.setDate(intermediateDate.getDate() + 1) < dateOutObj.getTime()) {
                const formattedIntermediateDate = intermediateDate.toISOString().split('T')[0]; 
                
                if (!bookedTimeSlots[formattedIntermediateDate]) {
                    bookedTimeSlots[formattedIntermediateDate] = [];
                }
    
                bookedTimeSlots[formattedIntermediateDate].push({
                    date: formattedIntermediateDate,
                    start: '00:00',
                    end: '23:30' // Block the entire intermediate day
                });
            }
    
            // Store booking for the check-out date, including cleanup time
            if (!bookedTimeSlots[dateOut]) {
                bookedTimeSlots[dateOut] = [];
            }
            bookedTimeSlots[dateOut].push({
                date: dateOut,
                start: '00:00',
                end: formatTime24(cleanupEndTime)
            });
        }
    });
        
        console.log(bookedTimeSlots);
        // Initialize flatpickr after booking data is fetched
        initializeFlatpickr();
        // Disable Dates
        updateDisabledDates();
    })
    .catch(error => console.error('Error fetching bookings:', error));

    // Populate the check-in times
    populateCheckInTimes(dateIn, dateOut, checkinTime);
    populateCheckOutTimes(checkinTime, dateIn, dateOut, checkoutTime);
});



const bookedTimeSlots = {}; // Store booked time slots
const earliestTime = 6; // 6:00 AM
const earliestTime24hour = 0; // 12:00 AM
const latestTime = 23.5; // 11:30 PM
const minimumStay = 12;
const cleanupTime = 2;

// Fetch today's date
const today = new Date();
const currentMonth = today.getMonth();
const currentYear = today.getFullYear();
const formattedToday = formatDate(today);

const checkInTimeSelect = document.querySelector('select[name="checkin"]');
const checkOutTimeSelect = document.querySelector('select[name="checkout"]');

function timeToFloat(timeString) {
  const [hours, minutes] = timeString.split(':').map(Number); // Split and convert to numbers
  return hours + minutes / 60; // Convert to decimal time (e.g., 6:30 -> 6.5)
}

// Format time in 12-hour format
function formatTime12(time) {
  const hours = Math.floor(time);
  const minutes = (time % 1) * 60;
  const suffix = hours >= 12 ? 'PM' : 'AM';
  const adjustedHours = hours > 12 ? hours - 12 : hours === 0 ? 12 : hours;
  return `${adjustedHours}:${minutes.toString().padStart(2, '0')} ${suffix}`;
}

// Format time in 24-hour format
function formatTime24(date) {
  const hours = date.getHours().toString().padStart(2, '0');
  const minutes = date.getMinutes().toString().padStart(2, '0');
  return `${hours}:${minutes}`;
}

// Helper function to get the next day
function getNextDay(date) {
  const currentDate = new Date(date);
  currentDate.setDate(currentDate.getDate() + 1); // Move to the next day

  const year = currentDate.getFullYear();
  const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); 
  const day = currentDate.getDate().toString().padStart(2, '0');

  return `${year}-${month}-${day}`;
}

// Function to check if a time is within booked slots
function isTimeBlocked(date, time) {
  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].some(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);

      const slotStart = slotStartHour * 60 + slotStartMin; 
      const slotEnd = slotEndHour * 60 + slotEndMin;       
      const currentTime = time * 60;                       

      const earliestPossibleCheckIn = slotEnd;

      if ((earliestTime <= slotStart || currentTime >= slotStart) && currentTime <= slotEnd) {
        return true;  // Time is blocked
      }
      if (currentTime < earliestPossibleCheckIn) {
        return true;  // Time violates the minimum stay rule
      }

      return false;
    });
  }
  return false;
}

// Function to check if a checkout time is blocked due to future bookings
function isCheckoutTimeBlocked(date, time) {
  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].some(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);

      const slotStart = slotStartHour * 60 + slotStartMin;  // Start time in minutes
      const slotEnd = slotEndHour * 60 + slotEndMin;        // End time in minutes
      const currentTime = time * 60;                        // Current time in minutes

      const nextBookingStartWithCleanup = slotStart - (cleanupTime * 60); // Subtract cleanup period (120 minutes)

      if ((currentTime >= slotStart && currentTime <= slotEnd) || currentTime >= nextBookingStartWithCleanup) {
        return true;  // Time is blocked
      }

      return false;
    });
  }
  return false;
}

function hasPreviousDaySpillover(date) {
  const prevDate = new Date(date);
  prevDate.setDate(prevDate.getDate() - 1);
  const formattedPrevDate = prevDate.toISOString().split('T')[0];

  if (bookedTimeSlots[formattedPrevDate]) {
    return bookedTimeSlots[formattedPrevDate].some(slot => {
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      return slotEndHour < earliestTime; // Spillover to the next day if the checkout is before 6 AM
    });
  }
  return false;
}

function isTimeAvailable(date, time) {
  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].some(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      
      const slotStart = slotStartHour * 60 + slotStartMin;  
      const slotEnd = slotEndHour * 60 + slotEndMin;        
      const currentTime = time * 60;                        
      // If the current time is within a blocked slot
      if (currentTime >= slotStart && currentTime <= slotEnd) {
        return false;  // Time is blocked
      }
      if(currentTime < slotEnd){
        return false;
      }

      return true; // Time is available
    });
  }
  return true; // If no booking exists for this date, time is available
}
function isTimeAvailableCheckIn(date, time) {
  const currentTime = time * 60;  

  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].every(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);

      const slotStart = slotStartHour * 60 + slotStartMin;  
      const slotEnd = slotEndHour * 60 + slotEndMin;        

      // Ensure times are correctly compared when the slot starts at or after midnight
      if (slotStart === 0 && currentTime < slotEnd) {
        return false;  
      }

      // Block time if it overlaps with a booked time slot
      if ((currentTime >= slotStart && currentTime <= slotEnd) || currentTime < slotEnd) {
        return false;  // Time is blocked
      }

      return true;  // Time is available
    });
  }
  
  return true; // If no booking exists for this date, time is available
}

//Convert time to minutes
function timeToMinutes(time) {
  const [hour, min] = time.split(':').map(Number);
  return hour * 60 + min;
}

function isTimeAvailableForCheckIn(date, time) {
  const minimumStayMinutes = minimumStay * 60;  // Minimum 12-hour stay in minutes

  // Only allow time slots between 6:00 AM and 11:30 PM for check-in
  if (time < earliestTime || time > latestTime) {
    return false;  // Time is out of the allowed range for check-in
  }

  // Check if the time is available
  /*console.log(date);
  console.log(isTimeAvailableCheckIn(date, time));*/
  if (!isTimeAvailableCheckIn(date, time)) {
    return false;
  }

  // Find the first booking slot after the selected check-in time
  if (bookedTimeSlots[date]) {
    const futureSlots = bookedTimeSlots[date].filter(slot => timeToMinutes(slot.start) > time * 60);
    
    if (futureSlots.length > 0) {
      // Get the start time of the first booking slot after the check-in time
      const firstFutureSlotStart = timeToMinutes(futureSlots[0].start);

      // Check if the gap between the selected check-in time and the next booking is less than 12 hours
      if (firstFutureSlotStart - (time * 60) < minimumStayMinutes) {
        return false; // Gap is too small, doesnt allow a minimum stay of 12 hours
      }
    }
  }

  return true;
}

function isTimeAvailableForCheckOut(date, time) {
  // Allow spillover times from previous bookings
  return isTimeAvailable(date, time) || hasPreviousDaySpillover(date);
}

function isDateFullyBookedForCheckIn(dateStr) {

  for (let time = earliestTime; time <= latestTime; time += 0.5) {
    if (isTimeAvailableForCheckIn(dateStr, time)) {
      return false; // Theres at least one available slot, so the date is not fully booked
    }
  }
  return true; // No available times, date is fully booked
}

function isDateFullyBookedForCheckOut(dateStr) {
  const cutoffTimeMinutes = 4 * 60;
  // If the date has bookings, check if the date is fully booked or not
  if (bookedTimeSlots[dateStr]) {
    const slots = bookedTimeSlots[dateStr];

    // Sort the slots by their start time to find the earliest booking
    const sortedSlots = slots.sort((a, b) => timeToMinutes(a.start) - timeToMinutes(b.start));

    const firstSlotStart = timeToMinutes(sortedSlots[0].start);
    // check-out can be allowed until then
    if (firstSlotStart < cutoffTimeMinutes) {
      return true;  // Date is not fully booked, check-out is allowed before the first booking
    }

    // If the first booking starts at or before the cutoff time, block the entire date
    for (let time = earliestTime24hour; time <= latestTime; time += 0.5) {
      if (isTimeAvailableForCheckOut(dateStr, time)) {
        return false;  // Theres at least one available slot for check-out
      }
    }
  }
  
  return true;  // No available times for check-out, or the day is fully booked
}


// Function to populate check-in time options
function populateCheckInTimes(checkInDate, checkOutDate, selectedCheckInTime) {
  const checkInTimeSelect = document.querySelector('#checkin-time');
  checkInTimeSelect.innerHTML = ''; 

  let maxCheckInTime = checkInDate === checkOutDate ? latestTime - minimumStay : latestTime;

  // Function to check if the next day's bookings affect today's check-in time
  function isNextDayBookingAffectingCheckIn() {
    const nextDay = getNextDay(checkInDate); 
    const nextDayBookings = bookedTimeSlots[nextDay];
    
    if (nextDayBookings && nextDayBookings.length > 0) {
      const earliestNextDayCheckIn = nextDayBookings[0].start; // Get the earliest booking time for the next day
      const [nextDayStartHour, nextDayStartMin] = earliestNextDayCheckIn.split(':').map(Number);
      const nextDayStartInHours = nextDayStartHour + (nextDayStartMin / 60);
  
      if (nextDayStartInHours > 11.5 || nextDayStartInHours < earliestTime) {
        return maxCheckInTime;  // Return regular maxCheckInTime without adjustment
      }
  
      // Calculate remaining time today, considering cleanup time and minimum stay
      const remainingTimeToday = (latestTime + 0.5) - (minimumStay - nextDayStartInHours) - cleanupTime;
      
      return remainingTimeToday;
    } else {
      // If no next-day booking exists, return the regular maxCheckInTime
      return maxCheckInTime;
    }
  }
  
  if (checkInDate !== checkOutDate) {
    maxCheckInTime = isNextDayBookingAffectingCheckIn();
  }

  checkInTimeSelect.innerHTML = `<option value="" hidden selected>Select check-in time</option>`;

  // Populate check-in times up to maxCheckInTime
  for (let time = earliestTime; time <= maxCheckInTime; time += 0.5) {
    if (!isTimeBlocked(checkInDate, time)) { // Only add options if time is not blocked
      const optionDate = new Date();
      optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);
      const optionText = optionDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      const optionValue = optionDate.getHours().toString().padStart(2, '0') + ':' + optionDate.getMinutes().toString().padStart(2, '0');
      const option = new Option(optionText, optionValue);
      checkInTimeSelect.add(option);

      // Compare times as floats and set the selected option
      if (parseFloat(time) === parseFloat(selectedCheckInTime)) {
        option.selected = true;
      }
    }
  }

  // If no time is selected, default to the first available time
  checkInTimeSelect.value = selectedCheckInTime || checkInTimeSelect.options[0]?.value || '';
  console.log(checkInTimeSelect.value);
}


function populateCheckOutTimes(checkInTime, checkInDate, checkOutDate, selectedCheckOutTime) {
  const checkOutTimeSelect = document.querySelector('#checkout-time');
  checkOutTimeSelect.innerHTML = ''; 

  const [checkInHours, checkInMinutes] = checkInTime.split(':').map(Number);
  const checkInDateTime = new Date(checkInDate);
  checkInDateTime.setHours(checkInHours, checkInMinutes, 0, 0);

  // Minimum time the user can check out is 12 hours after check-in
  let checkOutMinTime = new Date(checkInDateTime.getTime() + minimumStay * 60 * 60 * 1000);

  // Calculate the difference in days between check-in and check-out
  const checkInDateObj = new Date(checkInDate);
  const checkOutDateObj = new Date(checkOutDate);
  const dayDifference = (checkOutDateObj - checkInDateObj) / (1000 * 60 * 60 * 24);

  const checkOutTimes = [];

  if (checkInDate === checkOutDate) {
    // Same-day checkout, ensure the check-out time is at least 12 hours after check-in
    for (let time = checkOutMinTime.getHours() + (checkOutMinTime.getMinutes() / 60); time <= latestTime; time += 0.5) {
      if (!isCheckoutTimeBlocked(checkOutDate, time)) {
        checkOutTimes.push(time);
      }
    }
  } else if (dayDifference === 1) {
    // Check-out on the next day, allow only times that respect the minimum 12-hour stay
    for (let time = earliestTime24hour; time <= latestTime; time += 0.5) {
      const optionDate = new Date(checkOutDate);
      optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);

      // Only allow times on the second day that are at least 12 hours from check-in
      if (optionDate.getTime() >= checkOutMinTime.getTime() && !isCheckoutTimeBlocked(checkOutDate, time)) {
        checkOutTimes.push(time);
      }
    }
  } else {
    // Check-out after 2 or more days, allow all times on the check-out date
    for (let time = earliestTime24hour; time <= latestTime; time += 0.5) {
      if (!isCheckoutTimeBlocked(checkOutDate, time)) {
        checkOutTimes.push(time);
      }
    }
  }

  // Populate the available check-out times
  checkOutTimes.forEach(time => {
    const optionDate = new Date();
    optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);
    const optionText = optionDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const optionValue = optionDate.getHours().toString().padStart(2, '0') + ':' + optionDate.getMinutes().toString().padStart(2, '0');
    checkOutTimeSelect.add(new Option(optionText, optionValue));
  });

  // Set the selected checkout time value, if it matches any option
  checkOutTimeSelect.value = selectedCheckOutTime || checkOutTimeSelect.options[0]?.value || ''; // Set the selected time or default to first available time
  console.log(checkOutTimeSelect.value);
}

function calculateTotalHours() {
  const checkInDate = document.querySelector("#date-in").value;
  const checkOutDate = document.querySelector("#date-out").value;
  const checkInTime = checkInTimeSelect.value;
  const checkOutTime = checkOutTimeSelect.value;

  if (checkInTime === '' || checkOutTime === '' || checkInDate === '' || checkOutDate === '') {
    document.querySelector('input[name="numhours"]').value = '';
    return;
  }

  // Extract hours and minutes from time values
  const [checkInHours, checkInMinutes] = checkInTime.split(':').map(Number);
  const [checkOutHours, checkOutMinutes] = checkOutTime.split(':').map(Number);

  const checkInDateTime = new Date(checkInDate);
  checkInDateTime.setHours(checkInHours, checkInMinutes, 0, 0); // Set hours and minutes for check-in
  
  console.log(checkInDateTime);

  const checkOutDateTime = new Date(checkOutDate);
  checkOutDateTime.setHours(checkOutHours, checkOutMinutes, 0, 0); // Set hours and minutes for check-out

  // If check-out is before check-in on the same day or if its a multi-day booking
  if (checkOutDateTime <= checkInDateTime) {
    checkOutDateTime.setDate(checkOutDateTime.getDate() + 1);
  }

  // Calculate the total hours between check-in and check-out
  const totalHours = (checkOutDateTime - checkInDateTime) / (1000 * 60 * 60);

  // Set the total hours value in the input field
  document.querySelector('input[name="numhours"]').value = totalHours.toFixed(1);
}


// Initialize flatpickr
function initializeFlatpickr() {
    // Get the existing values from the inputs
    const dateInValue = document.querySelector("#date-in").value;
    const dateOutValue = document.querySelector("#date-out").value;
  
    // Initialize flatpickr for the Date In input
    fp = flatpickr("#date-in", {
      enableTime: false,
      dateFormat: "Y-m-d",
      minDate: new Date(),
      showMonths: 1, 
      defaultDate: dateInValue,  
      onChange: function (selectedDates, dateStr, instance) {
        document.querySelector("#date-in").value = dateStr;
  
        fp1.set('minDate', dateStr); // Set min date for checkout based on check-in date
        fp1.setDate(null); // Reset checkout date
  
        // Update the disabled dates and max checkout range based on check-in date
        updateDisabledDates(dateStr);
      }
    });
    // Initialize flatpickr for the Date Out input
    fp1 = flatpickr("#date-out", {
      enableTime: false,
      dateFormat: "Y-m-d",
      minDate: dateInValue,
      defaultDate: dateOutValue,  
      onChange: function (selectedDates, dateStr, instance) {
        document.querySelector("#date-out").value = dateStr;
        populateCheckInTimes(document.querySelector("#date-in").value, dateStr);
  
        const checkInTime = checkInTimeSelect.value;
        if (checkInTime) {
          populateCheckOutTimes(checkInTime, document.querySelector("#date-in").value, dateStr);
        }
      }
    });

    checkInTimeSelect.addEventListener('change', function () {
        const checkInDate = document.querySelector("#date-in").value;
        const checkOutDate = document.querySelector("#date-out").value;

        // Repopulate check-out times based on the selected check-in time
        if (checkInDate && checkOutDate) {
        populateCheckOutTimes(this.value, checkInDate, checkOutDate);
        calculateTotalHours();
        }
    });

    checkOutTimeSelect.addEventListener('change', function () {
        calculateTotalHours(); // Call calculateTotalHours when check-out time changes
    });
  }

function findFirstFullyBookedDate(selectedCheckInDate) {
    const sortedDates = Object.keys(bookedTimeSlots).sort(); 
    for (let date of sortedDates) {
    if (date > selectedCheckInDate && isDateFullyBookedForCheckOut(date)) {
        return date; // Return the first fully booked date after the check-in date
    }
    }
    return null; // No fully booked date found
}

// Update the disabled dates based on the booking data
function updateDisabledDates(selectedCheckInDate) {
  const disabledDatesForCheckIn = [];
  const disabledDatesForCheckOut = [];

  for (const date in bookedTimeSlots) {
    if (isDateFullyBookedForCheckIn(date)) {
      disabledDatesForCheckIn.push(date);
    }

    if (isDateFullyBookedForCheckOut(date)) {
      disabledDatesForCheckOut.push(date);
    }
  }

  const maxCheckOutDate = findFirstFullyBookedDate(selectedCheckInDate);

  fp.set('disable', disabledDatesForCheckIn);
  fp1.set('maxDate', null);

  if (maxCheckOutDate) {
    fp1.set('maxDate', maxCheckOutDate);
  }

  fp1.set('disable', disabledDatesForCheckOut);
}

// Format date to 'YYYY-MM-DD'
function formatDate(date) {
  const year = date.getFullYear();
  const month = (date.getMonth() + 1).toString().padStart(2, '0');
  const day = date.getDate().toString().padStart(2, '0');
  return `${year}-${month}-${day}`;
}



// Initialize
initializeFlatpickr();


    
</script>
</body>
</html>
