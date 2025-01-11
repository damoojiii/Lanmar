<?php
// Start the session at the very beginning of the file
session_start();
include "role_access.php";
checkAccess('admin');

include("connection.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>homepage settings</title>

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
            border-radius: 10px !important;  /* Added !important to override Bootstrap */
            padding: 13px 30px;
            background-color: #03045e;
            border: none;
            cursor: pointer;
            color: white;
        }

        /* Main container styles */
        .main-content {
            flex: 1;
            padding: 25px;
            background-color: #ffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form container styles */
        .settings-form-container {
            margin-bottom: 20px;
        }

        /* Form styles */
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
            margin-bottom: 10px;
        }

        /* Alert messages */
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

        /* Button styles */
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

        /* Tab container styles */
        .tab-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .tab-container a {
            text-decoration: none;
            flex: 1 1 auto;
            min-width: 140px;
        }

        .tab-container .tab {
            padding: 8px 30px;
            text-align: center;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
            font-size: 14px;
            background-color: #1c2531;
            color: white;
            width: 100%;
        }

        .tab-container .tab.active {
            background-color: #00968f;
        }

        .tab:hover {
            background-color: #0175FE;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .tab-container {
                gap: 8px;
                padding: 0 10px;
            }

            .tab-container a {
                min-width: 120px;
            }

            .tab-container .tab {
                padding: 8px 15px;
                font-size: 12px;
            }
            .main-content{
                padding: 0;
            }
            .btn-modal{
                width: 100%;
            }
            .room-info {
                display: grid;
                grid-template-columns: repeat(3, 1fr); /* Three equal columns */
                grid-template-rows: repeat(2, auto); /* Two rows, auto height */
                gap: 10px; /* Optional: spacing between items */
                padding: 10px; /* Optional: padding around the grid */
            }
        }

        @media (max-width: 480px) {
            .tab-container a {
                min-width: 100px;
                flex: 1 1 calc(50% - 8px);
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
                    <li><a class="nav-link text-white" href="pending_reservation.php"><i class="fa-solid fa-circle navcircle"></i> Pending Reservations</a></li>
                    <li><a class="nav-link text-white" href="approved_reservation.php"><i class="fa-solid fa-circle navcircle"></i> Approved Reservations</a></li>
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

    <div id="main-content" class="p-3">
        <div class="flex-container">
            <div class="main-content">
                <h1 class="text-center mb-5 mt-4">Homepage Settings</h1>
                
                <div class="tab-container">
                    <a href="homepage_settings.php">
                        <div class="tab" id="roomInfoTab">
                            Homescreen
                        </div>
                    </a>
                    <a href="homepage_section3.php">
                        <div class="tab " id="archiveInfoTab">
                            About
                        </div>
                    </a>
                    <a href="homepage_section2.php">
                        <div class="tab" id="facilityInfoTab">
                            Gallery
                        </div>
                    </a>
                    <a href="homepage_section4.php">
                        <div class="tab " id="facilityInfoTab">
                            Rooms
                        </div>
                    </a>
                    <a href="homepage_section5.php">
                        <div class="tab active" id="facilityInfoTab">
                            Prices
                        </div>
                    </a>
                    <a href="homepage_section6.php">
                        <div class="tab" id="facilityInfoTab">
                            Update QR codes
                        </div>
                    </a>
                </div>
                <style>
                    .tab-container {
                        display: flex;
                        margin-top: 5px;
                        margin-bottom: 1px;
                    }
                    
                    .tab-container .tab {
                        background-color: black; /* Set background color to black */
                        color: white; /* Set font color to white */
                        padding: 8px 29.8px;
                        text-align: center;
                        cursor: pointer;
                        border: 1px solid transparent;
                        border-radius: 10px 10px 0 0;
                        margin-right: 21px;
                        transition: 0.3s;
                        text-decoration: none;
                    }

                    .tab-container .tab.active {
                        background-color: #19315D; /* Keep active tab color */
                        color: white; /* Ensure active tab font color is white */
                    }

                    .tab:hover {
                        background-color: #0175FE; /* Hover effect */
                        color: white; /* Ensure hover font color is white */
                    }
                </style>

                <div class="flex-container">
                    <div class="main-content">
                        <style>
                            .review-card-container {
                                display: flex;
                                flex-wrap: wrap;
                                gap: 20px; /* Space between cards */
                                margin-top: 20px; /* Space above the card container */
                            }

                            .review-card {
                                background-color: #f8f9fa; /* Light background for cards */
                                border: 1px solid #ddd; /* Light border */
                                border-radius: 8px; /* Rounded corners */
                                padding: 15px; /* Inner padding */
                                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
                                flex: 1 1 calc(30% - 20px); /* Responsive card width */
                                min-width: 250px; /* Minimum width for cards */
                                transition: transform 0.3s; /* Smooth hover effect */
                            }

                            .review-card:hover {
                                transform: translateY(-5px); /* Lift effect on hover */
                            }

                            .review-card h3 {
                                font-size: 1.2rem; /* Font size for payment name */
                                margin: 0 0 10px; /* Margin below the title */
                            }

                            .review-card h2 {
                                font-size: 1rem; /* Font size for price */
                                color: #007bff; /* Color for price */
                                margin: 0; /* No margin */
                            }
                        </style>

<?php
// Fetch reviews with status = 0
$stmt = $conn->prepare("SELECT id, payment_name, price FROM prices_tbl");
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any reviews
if ($result->num_rows > 0) {
    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Payment Name</th>';
    echo '<th>Price</th>';
    echo '<th>Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['payment_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['price']) . '</td>';
        echo '<td><button class="update-btn" data-id="' . $row['id'] . '" data-name="' . htmlspecialchars($row['payment_name']) . '" data-price="' . htmlspecialchars($row['price']) . '">Update Price</button></td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo 'No reviews found.';
}
$stmt->close();
?>

<!-- Modal for updating price -->
<div id="updatePriceModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Update Price</h2>
        <form id="updatePriceForm">
            <input type="hidden" id="priceId" name="id" />
            <label for="paymentName">Payment Name</label>
            <input type="text" id="paymentName" name="payment_name" readonly />
            <label for="newPrice">New Price</label>
            <input type="number" id="newPrice" name="price" required />
            <button type="submit">Update</button>
        </form>
    </div>
</div>

<!-- Modal Styling -->
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close-btn {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close-btn:hover,
    .close-btn:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<!-- JavaScript for handling the modal and AJAX submission -->
<script>
    // Open modal when Update button is clicked
    document.querySelectorAll('.update-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const paymentName = this.getAttribute('data-name');
            const price = this.getAttribute('data-price');

            document.getElementById('priceId').value = id;
            document.getElementById('paymentName').value = paymentName;
            document.getElementById('newPrice').value = price;

            document.getElementById('updatePriceModal').style.display = 'block';
        });
    });

    // Close modal
    document.querySelector('.close-btn').addEventListener('click', function () {
        document.getElementById('updatePriceModal').style.display = 'none';
    });

    // Submit form to update the price in the database via AJAX
    document.getElementById('updatePriceForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('update_price.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Price updated successfully!');
                window.location.reload();  // Optionally reload the page to see the updated price
            } else {
                alert('Failed to update the price');
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>

                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="updateRoomId">
                <p>Payment Name: <span id="updatePaymentName"></span></p>
                <div class="form-group">
                    <label for="updatePrice">New Price</label>
                    <input type="text" class="form-control" id="updatePrice" placeholder="Enter new price">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updatePrice()">Update</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/all.min.js"></script>
<script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>

<style>
/* Add these styles */
.modal-dialog {
    max-width: 400px;
    margin: 1rem auto;
}

.modal-content {
    border-radius: 12px;
}

.modal-header {
    padding: 0.75rem 1rem;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 12px 12px 0 0;
}

.modal-body {
    padding: 1rem;
}

.form-label {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.form-control-sm {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
}

/* Mobile specific styles */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 0.5rem;
        max-width: none;
    }
    
    .modal-content {
        border-radius: 8px;
    }
    
    .modal-body {
        padding: 0.75rem;
    }
    
    .btn {
        padding: 0.375rem 0.75rem;
    }
}
</style>

<script>
    document.querySelectorAll('.collapse').forEach(collapse => {
        collapse.addEventListener('show.bs.collapse', () => {
            collapse.style.height = collapse.scrollHeight + 'px';
        });
        collapse.addEventListener('hidden.bs.collapse', () => {
            collapse.style.height = '0px';
        });
    });

    $(document).on('click', '.openModal', function() {
        const roomId = $(this).data('id');
        const roomName = $(this).data('name');
        const description = $(this).data('capacity');
        const price = $(this).data('price');
        const maxpax = $(this).data('maxpax');
        const minpax = $(this).data('minpax');

        $('#room_id').val(roomId);
        $('#room_name').val(roomName);
        $('#description').val(description);
        $('#price').val(price);
        $('#maxpax').val(maxpax);
        $('#minpax').val(minpax);

        $('#editRoomModal').modal('show');
    });

    $('#editRoomForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'update_room.php', // Create this file to handle the update
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert('Room updated successfully!');
                location.reload(); // Reload the page to see changes
            },
            error: function() {
                alert('Error updating room.');
            }
        });
    });
</script>

</body>
<style>
      .tab-container {
        display: flex;
        margin-top: 5px;
        margin-bottom: 1px;
    }

    .tab-container .tab.active {
        background-color: #19315D;
        color: white;
    }

    .tab-container .tab {
        padding: 8px 29.8px;
        text-align: center;
        cursor: pointer;
        border: 1px solid transparent;
        border-radius: 10px 10px 0 0;
        margin-right: 21px;
        transition: 0.3s;
        background-color: white;
        font-size: 12px;
        background-color: #1c2531;
        color: white;
        border-bottom: 1px solid white;
        text-decoration: none;
    }

    .tab:hover {
        background-color: #0175FE;
        color: white;
    }

    .flex-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .room-card {
        display: flex;
        background: white;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 100%;
    }

    .room-image {
        flex: 0 0 40%;
        max-width: 40%;
    }

    .room-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .room-details {
        flex: 1;
        padding: 15px;
    }

    .room-details h2 {
        margin: 0 0 10px 0;
        font-size: 1.5rem;
    }

    .description {
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .room-info p {
        margin: 5px 0;
        font-size: 0.9rem;
    }

    .action-buttons {
        margin-top: 15px;
        text-align: right;
    }

    .openModal {
        padding: 8px 16px;
        background-color: #19315D;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .room-card {
            flex-direction: column;
        }

        .room-image {
            max-width: 100%;
            height: 200px;
        }

        .room-details {
            padding: 15px;
        }

        .room-details h2 {
            font-size: 1.2rem;
        }

        .room-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .action-buttons {
            text-align: center;
        }
    }

    @media (max-width: 480px) {
    
    }
</style>
</html>

