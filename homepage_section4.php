<?php
include "role_access.php";
include("connection.php");
checkAccess('admin');


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
            z-index: 1000; /* Ensure sidebar is above other content */
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
            .main-content, #main-content{
                padding: 0 !important;
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

    <div id="main-content" class="p-3">
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
                        <div class="tab active" id="facilityInfoTab">
                            Rooms
                        </div>
                    </a>
                    <a href="homepage_section5.php">
                        <div class="tab " id="facilityInfoTab">
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
                    <div class="flex-container">
                    <div class="main-content">
                        <?php
                            $sql = "SELECT r.room_id, r.room_name, r.image_path, r.description, r.minpax, r.maxpax, r.price, r.is_offered, GROUP_CONCAT(i.inclusion_id) as inclusions, GROUP_CONCAT(i.inclusion_name) as inclusion
                            FROM rooms r
                            LEFT JOIN room_inclusions ri ON r.room_id = ri.room_id
                            LEFT JOIN inclusion_tbl i ON ri.inclusion_id = i.inclusion_id
                            GROUP BY r.room_id";
                            $result = $conn->query($sql);
                        ?>
                        <div class="settings-form-container">
                            <h2 class="text-center mb-4">Featured Rooms</h2>
                            <div class="mb-2">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                                    Add Room
                                </button>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inclusionModal">
                                    Add Inclusion
                                </button>
                            </div>
                            <div class="flex-container">
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <div class='room-card'>
                                            <div class="room-image">
                                                <img src='<?php echo $row['image_path']; ?>' alt='Room Image'>
                                            </div>
                                            <div class="room-details">
                                                <h2><?php echo $row['room_name']; ?></h2>
                                                <p class="description"><?php echo $row['description']; ?></p>
                                                <div class="room-info">
                                                    <p>Minpax: <?php echo $row['minpax']; ?></p>
                                                    <p>Maxpax: <?php echo $row['maxpax']; ?></p>
                                                    <p>Price: <?php echo $row['price']; ?></p>
                                                    <p>Offered: <?php echo ($row['is_offered'] === '1' ? 'Yes' : 'No'); ?></p>
                                                    <p>Inclusions: <?php echo $row['inclusion']; ?></p>
                                                </div>
                                                <div class="action-buttons"> 
                                                    <button type='button' class='openModal btn-modal btn' 
                                                        data-id='<?php echo $row['room_id']; ?>' 
                                                        data-name='<?php echo $row['room_name']; ?>' 
                                                        data-description='<?php echo $row['description']; ?>' 
                                                        data-price='<?php echo $row['price']; ?>'
                                                        data-offered='<?php echo $row['is_offered'] ?>' 
                                                        data-maxpax='<?php echo $row['maxpax']; ?>' 
                                                        data-minpax='<?php echo $row['minpax']; ?>'
                                                        data-inclusions='<?php echo $row['inclusions']; ?>'
                                                        >
                                                        Edit
                                                    </button>
                                                    <button type='button' class='btn btn-danger deleteRoom' data-rid='<?php echo $row['room_id']; ?>'>Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p>No rooms available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoomModalLabel">Edit Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRoomForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="room_id" id="room_id">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="room_name" class="form-label">Room Name</label>
                            <input type="text" class="form-control form-control-sm" id="room_name" name="room_name" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control form-control-sm" id="price" name="price" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label for="minpax" class="form-label">Min Pax</label>
                            <input type="number" class="form-control form-control-sm" id="minpax" name="minpax" required>
                        </div>
                        <div class="col-6 mb-2">
                            <label for="maxpax" class="form-label">Max Pax</label>
                            <input type="number" class="form-control form-control-sm" id="maxpax" name="maxpax" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control form-control-sm" id="description" name="description" rows="2" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label for="photo" class="form-label">Room Image</label>
                        <input type="file" class="form-control form-control-sm" id="photo" name="photo">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Is Offered (for Overnight)?</label>
                        <div>
                            <input type="radio" id="is_offered_yes" name="is_offered" value="1" required>
                            <label for="is_offered_yes">Yes</label>
                            <input type="radio" id="is_offered_no" name="is_offered" value="0" required>
                            <label for="is_offered_no">No</label>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Inclusions</label>
                            <div id="inclusions-container" class="row"></div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-sm btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scrollable Inclusions Section -->
<div class="modal fade" id="inclusionModal" tabindex="-1" aria-labelledby="inclusionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inclusionModalLabel">Manage Inclusions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="inclusionName" class="form-label">Add New Inclusion</label>
                    <input type="text" class="form-control" id="inclusionName" name="inclusionName" required>
                    <button type="button" class="btn btn-primary mt-2" id="addInclusionButton">Add Inclusion</button>
                </div>
                <div class="mb-3">
                    <label class="form-label">Current Inclusions</label>
                    <div class="inclusions-container" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                        <!-- Inclusions will be dynamically loaded here -->
                        <ul id="inclusionsList" class="list-group">
                            <!-- Placeholder for inclusions -->
                        </ul>
                    </div>
                </div>
            </div>
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

    $(document).on('click', '.openModal', function() {
        const roomId = $(this).data('id');
        const roomName = $(this).data('name');
        const description = $(this).data('description');
        const price = $(this).data('price');
        const maxpax = $(this).data('maxpax');
        const minpax = $(this).data('minpax');
        const is_offered = $(this).data('offered');
        let inclusions = $(this).data('inclusions');

        if (typeof inclusions === 'string') {
            inclusions = inclusions.split(',');
        } else if (!Array.isArray(inclusions)) {
            inclusions = [];
        }

        $('#room_id').val(roomId);
        $('#room_name').val(roomName);
        $('#description').val(description);
        $('#price').val(price);
        $('#maxpax').val(maxpax);
        $('#minpax').val(minpax);
        console.log(is_offered);
        $(`#is_offered_${is_offered ? 'yes' : 'no'}`).prop('checked', true);
        $('#inclusions-container').empty();

        $.ajax({
            url: 'fetch_inclusions.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                const col1 = $('<div class="col-md-6"></div>');
                const col2 = $('<div class="col-md-6"></div>');
                
                data.forEach((inclusion, index) => {
                    const checkbox = $('<div class="form-check">')
                        .append($('<input>', {
                            type: 'checkbox',
                            id: `inclusion-${inclusion.inclusion_id}`,
                            value: inclusion.inclusion_id,
                            class: 'form-check-input',
                            name: 'inclusions[]'
                        }))
                        .append($('<label>', {
                            for: `inclusion-${inclusion.inclusion_id}`,
                            text: inclusion.inclusion_name,
                            class: 'form-check-label'
                        }));
                    
                    if (index % 2 === 0) {
                        col1.append(checkbox);
                    } else {
                        col2.append(checkbox);
                    }
                });

                $('#inclusions-container').append(col1).append(col2);

                $(`input[name="inclusions[]"]`).each(function() {
                    if (inclusions.includes($(this).val())) {
                        $(this).prop('checked', true);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });

        $('#editRoomModal').modal('show');
    });

    $('#editRoomForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'update_room.php',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('Response:', response);
                alert(response);
                if (response.includes('successfully')) {
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', xhr.responseText);
                alert('Error updating room: ' + xhr.responseText);
            }
        });
    });

    $(document).on('click', '.deleteRoom', function() {
        const roomId = $(this).data('rid');
        
        if (confirm('Are you sure you want to delete this room? This action cannot be undone.')) {
            $.ajax({
                url: 'delete_room.php',
                type: 'POST',
                data: { room_id: roomId },
                success: function(response) {
                    if (response) {
                        console.log(response);
                        alert('Room deleted successfully.');
                        location.reload(); // Refresh the page to reflect the deletion
                    } else {
                        console.log(response);
                        alert('Failed to delete the room. Please try again.');
                    }
                },
                error: function() {
                    alert('An error occurred while trying to delete the room.');
                }
            });
        }
    });

    $(document).ready(function() {
        // Load inclusions when the modal is opened
        $('#inclusionModal').on('show.bs.modal', function() {
            loadInclusions();
        });

        function loadInclusions() {
            $.ajax({
                url: 'fetch_inclusions.php',
                type: 'GET',
                dataType: 'json', // Expect JSON data from the server
                success: function(data) {
                    let inclusionsHtml = '';
                    if (data.length > 0) {
                        data.forEach(function(inclusion) {
                            inclusionsHtml += `
                            <li class='list-group-item d-flex justify-content-between align-items-center'>
                            ${inclusion.inclusion_name}
                            <button class='btn btn-sm btn-danger delete-inclusion' data-id='${inclusion.inclusion_id}'>Delete</button>
                            </li>`;
                        });
                    } else {
                        inclusionsHtml = "<li class='list-group-item'>No inclusions found.</li>";
                    }
                    $('#inclusionsList').html(inclusionsHtml);
                },
                error: function() {
                    alert('Failed to load inclusions.');
                }
            });
        }

        $('#addInclusionButton').on('click', function() {
            const inclusionName = $('#inclusionName').val();
            $.ajax({
                url: 'add_inclusion.php',
                type: 'POST',
                data: { name: inclusionName },
                success: function(response) {
                    if (response) {
                        alert('Inclusion added successfully.');
                        $('#inclusionName').val(''); // Clear the input
                        loadInclusions(); // Refresh the inclusions list
                    } else {
                        alert('Failed to add inclusion.');
                    }
                },
                error: function() {
                    alert('An error occurred.');
                }
            });
        });

        // Delete Inclusion
        $(document).on('click', '.delete-inclusion', function() {
            const inclusionId = $(this).data('id');
            if (confirm('Are you sure you want to delete this inclusion?')) {
                $.ajax({
                    url: 'delete_inclusion.php',
                    type: 'POST',
                    data: { id: inclusionId },
                    success: function(response) {
                        if (response) {
                            alert('Inclusion deleted successfully.');
                            loadInclusions(); // Refresh the inclusions list
                        } else {
                            alert('Failed to delete inclusion.');
                        }
                    },
                    error: function() {
                        alert('An error occurred.');
                    }
                });
            }
        });
    });




</script>



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

<!-- Modal for Adding Room -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoomModalLabel">Add New Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addRoomForm" enctype="multipart/form-data">
                    <div class="mb-2">
                        <label for="room_name" class="form-label">Room Name</label>
                        <input type="text" class="form-control" id="room_name" name="room_name" required>
                    </div>
                    <div class="mb-2">
                        <label for="image_path" class="form-label">Upload Room Image</label>
                        <input type="file" class="form-control" id="image_path" name="image_path" accept="image/*" required>
                    </div>
                    <div class="mb-2">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                    </div>
                    <div class="mb-2">
                        <label for="minpax" class="form-label">Min Pax</label>
                        <input type="number" class="form-control" id="minpax" name="minpax" required>
                    </div>
                    <div class="mb-2">
                        <label for="maxpax" class="form-label">Max Pax</label>
                        <input type="number" class="form-control" id="maxpax" name="maxpax" required>
                    </div>
                    <div class="mb-2">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Is Offered (for Overnight)?</label>
                        <div>
                            <input type="radio" id="is_offered_yes" name="is_offered" value="1" required>
                            <label for="is_offered_yes">Yes</label>
                            <input type="radio" id="is_offered_no" name="is_offered" value="0" required>
                            <label for="is_offered_no">No</label>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="inclusions" class="form-label">Inclusions</label>
                        <div id="inclusions" class="row">
                            <?php
                            $inclusionQuery = "SELECT * FROM inclusion_tbl ORDER BY inclusion_name ASC";
                            $inclusionResult = mysqli_query($conn, $inclusionQuery);

                            if ($inclusionResult) {
                                while ($inclusion = mysqli_fetch_assoc($inclusionResult)) {
                                    echo '<div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="inclusions[]" value="' . htmlspecialchars($inclusion['inclusion_id']) . '" id="inclusion_' . htmlspecialchars($inclusion['inclusion_id']) . '">
                                                <label class="form-check-label" for="inclusion_' . htmlspecialchars($inclusion['inclusion_id']) . '">
                                                    ' . htmlspecialchars($inclusion['inclusion_name']) . '
                                                </label>
                                            </div>
                                        </div>';
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-sm btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary">Add Room</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#addRoomForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this); // Create a FormData object

        $.ajax({
            type: 'POST',
            url: 'add_room.php', // PHP script to handle the insertion
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert('Room added successfully!'); // Notify the user
                location.reload(); // Reload the page to see changes
            },
            error: function() {
                alert('Error adding room.'); // Notify the user of an error
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
