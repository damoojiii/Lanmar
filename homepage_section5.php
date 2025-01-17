<?php
include "role_access.php";
include("connection.php");
checkAccess('admin');

$success_message = "";
$error_message = "";

$targetDir = "uploads/qr/"; 


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['QRimage'])) {
    $image = $_FILES['QRimage'];
    $targetDir = "uploads/qr/"; 
    $imageName = "G_image.jpg"; 
    $targetFilePath = $targetDir . $imageName;

    // Ensure the target directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
        $success_message = "QR code for Gcash uploaded successfully!.";
    } else {
        $error_message = "Error uploading the file.";
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['QRimage1'])) {
    $image = $_FILES['QRimage1'];
    $targetDir = "uploads/qr/"; 
    $imageName = "P_image.jpg"; 
    $targetFilePath = $targetDir . $imageName;

    // Ensure the target directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
        $success_message = "QR code for PayMaya uploaded successfully !.";
    } else {
        $error_message = "Error uploading the file.";
    }
}
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
            z-index: 199;
            display: flex;
            align-items: center;
            padding: 0 15px;
            transition: margin-left 0.3s ease, width 0.3s ease; /* Smooth transition for header */
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
        border-radius: 10px !important; 
        padding: 10px 15px;
        background-color: rgb(29, 69, 104);
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

        .tab:hover {
            background-color: #0175FE;
        }
        .head-title{
            font-size: 2.5rem;
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
            .logout{
                margin-bottom: 3rem;
            }
            .settings-form-container {
                margin-top: 10px;
                padding-inline: 10px;
            }
        }

        @media (max-width: 480px) {
            .tab-container a {
                min-width: 100px;
                flex: 1 1 calc(50% - 8px);
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

    <div id="main-content" class="p-2">
        <div class="flex-container">
            <div class="main-content">
                <h2 class="text-center mb-5 mt-4 head-title"><strong>Content Manager</strong></h2>
                
                <div class="tab-container">
                    <a href="homepage_settings.php">
                        <div class="tab" id="roomInfoTab">
                            Homescreen
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
                            Reservation Config
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

    <div class="settings-form-container mt-4">
        <div class="row">
            <!-- Price Form -->
            <div class="col-md-6 col-sm-12">
                <div id="response-message"></div>
                <form id="update-price-form">
                    <div class="mb-3">
                        <label for="price-selector" class="form-label" id="">Select Price</label>
                        <div id="dropdown-container">
                            <select id="price-selector" class="form-select">

                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="price-input" class="form-label">Edit Price</label>
                        <input type="number" id="price-input" class="form-control" placeholder="Enter new price">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="save-btn btn btn-primary">Update Price</button>
                    </div>
                </form>
            </div>

            <!-- Booking Hour Form -->
            <div class="col-md-6 col-sm-12">
                <div id="response-message-hour"></div>
                <form id="update-booking-hour-form">
                    <div class="mb-3">
                        <label for="booking-selector" class="form-label">Edit Booking Time</label>
                        <select id="booking-selector" class="form-control">
                            <option value="1">Starting Time</option>
                            <option value="2">Closing Time</option>
                            <option value="3">Cleanup Time</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="value-input" class="form-label">Edit Hours</label>
                        <input type="number" step="0.1" id="value-input" class="form-control" placeholder="Enter new value">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="save-btn btn btn-primary">Update Hour</button>
                    </div>
                </form>
                
            </div>
        </div>
            <hr class="my-5">
        <div class="row mt-3">
            <div class="col-md-12">
            <h2 class="text-center mb-4"><strong>Change QR Payment</strong></h2>
            <?php if ($success_message): ?>
                <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form class="settings-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="background_image" class="mb-2">LANMAR GCASH QRCode:</label>
                    <input type="file" name="QRimage" id="background_image" accept="image/*" required class="form-control-file mx-auto d-block" aria-label="Upload New Background Image">
                </div>
                <div class="button-container">
                    <button type="submit" class="update-button" aria-label="Update Background">Update</button>
                </div>
            </form>
            <form class="settings-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="background_image" class="mb-2">LANMAR PayMaya QRCode:</label>
                    <input type="file" name="QRimage1" id="background_image" accept="image/*" required class="form-control-file mx-auto d-block" aria-label="Upload New Background Image">
                </div>
                <div class="button-container">
                    <button type="submit" class="update-button" aria-label="Update Background">Update</button>
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
    $(document).ready(function() {
        $.ajax({
            url: 'get_prices.php',
            type: 'GET',
            success: function(data) {
                $('#price-selector').html(data);
            },
            error: function() {
                $('##response-message').html('<div class="alert alert-danger">Failed to load prices.</div>');
            }
        });

        // Fetch price or booking hour based on selection
        function fetchValue(url, selector, inputField, responseContainer) {
            var selectedId = $(selector).val();
            $.ajax({
                url: url,
                type: 'POST',
                data: { id: selectedId },
                success: function(value) {
                    $(inputField).val(value);
                },
                error: function() {
                    $(responseContainer).html('<div class="alert alert-danger">Failed to fetch hours.</div>');
                }
            });
        }

        $(document).on('change', '#price-selector', function() {
            fetchValue('fetch_price.php', '#price-selector', '#price-input', '#response-message');
        });

        $('#booking-selector').on('change', function() {
            fetchValue('fetch_booking_hour.php', '#booking-selector', '#value-input', '#response-message-hour');
        });

        function handleFormSubmission(formId, url, responseContainer) {
            $(formId).on('submit', function(event) {
                event.preventDefault();
                const id = $('#price-selector').val();
                const price = $('#price-input').val();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {id: id, price: price},
                    success: function(response) {
                        $(responseContainer).html('<div class="alert alert-success">' + response + '</div>');
                    },
                    error: function() {
                        $(responseContainer).html('<div class="alert alert-danger">Error updating hour. Please try again.</div>');
                    }
                });
            });
        }

        // Price form submission
        handleFormSubmission('#update-price-form', 'update_price.php','#response-message');


        // Booking hour form submission with validation
        $('#update-booking-hour-form').on('submit', function(event) {
            event.preventDefault();

            var selectedId = $('#booking-selector').val();
            var newValue = parseFloat($('#value-input').val());
            var isValid = true;
            var errorMsg = '';

            if (newValue % 1 !== 0 && newValue % 1 !== 0.5) {
                isValid = false;
                errorMsg = "Hour must be a whole number or end in .5 (e.g., 6, 6.5, 7, 7.5).";
            }

            if (isValid) {
                if ((selectedId == 1 || selectedId == 2) && (newValue < 6 || newValue > 23.5)) {
                    isValid = false;
                    errorMsg = "Hour for Starting Time and Closing Time must be between 6 and 23.5.";
                } else if (selectedId == 3 && (newValue < 1 || newValue > 5)) {
                    isValid = false;
                    errorMsg = "Hour for Cleanup Time must be between 1 and 5.";
                }
            }

            if (!isValid) {
                $('#response-message-hour').html('<div class="alert alert-danger">' + errorMsg + '</div>');
            }
            else{
                $.ajax({
                    url: 'update_booking_hour.php',
                    type: 'POST',
                    data: {
                        id: selectedId,
                        value: newValue
                    },
                    success: function(response) {
                        $('#response-message-hour').html('<div class="alert alert-success">' + response + '</div>');
                    },
                    error: function() {
                        $('#response-message-hour').html('<div class="alert alert-danger">Error updating hour. Please try again.</div>');
                    }
                });
            }

        });

        // Trigger change event to load initial value
        $('#booking-selector').trigger('change');
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
</style>
</html>

