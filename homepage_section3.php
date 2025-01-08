<?php

session_start();

include "connection.php";

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the description from the form
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $description2 = isset($_POST['description2']) ? $_POST['description2'] : ''; 

    $conn->query("DELETE FROM about");

    if ($description === '' || $description2 === '') {
        $error_message = "Both descriptions are required.";
    } else {
        // Prepare the SQL statement
        $sql = "INSERT INTO about (description, description_2) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $description, $description2); // Bind parameters

        // Execute the statement
        if ($stmt->execute()) {
            $success_message = "Descriptions updated successfully.";
        } else {
            $error_message = "Error updating descriptions: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
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
            margin-top: 25px;
            padding: 20px;
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
            background-color: black;
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
                        <div class="tab active" id="archiveInfoTab">
                            About
                        </div>
                    </a>
                    <a href="homepage_section2.php">
                        <div class="tab" id="facilityInfoTab">
                            Gallery
                        </div>
                    </a>
                    <a href="homepage_section4.php">
                        <div class="tab" id="facilityInfoTab">
                            Rooms
                        </div>
                    </a>
                    <a href="section_5.php">
                        <div class="tab" id="facilityInfoTab">
                            Section 5
                        </div>
                    </a>
                    <a href="section_6.php">
                        <div class="tab" id="facilityInfoTab">
                            Section 6
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
                    <!-- Sidebar -->
                    <!-- Main Content -->
                    <div class="main-content">
                        <!-- Main content goes here -->
                        <div class="settings-form-container">
                        <h2 class="text-center mb-4">About</h2>
                            <?php if ($success_message): ?>
                                <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
                            <?php endif; ?>
                            <?php if ($error_message): ?>
                                <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                            <?php endif; ?>

                            <form class="settings-form" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="description" class="mb-2">Description 1: </label>
                                    <input type="text" name="description" id="description" required class="form-control-file mx-auto d-block">
                                </div>
                                <div class="form-group">
                                    <label for="description2" class="mb-2">Description 2: </label>
                                    <input type="text" name="description2" id="description2" required class="form-control-file mx-auto d-block">
                                </div>
                                <div class="button-container">
                                    <button type="submit">Update Description</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/all.min.js"></script>
<script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>
<script>
    document.querySelectorAll('.collapse').forEach(collapse => {
        collapse.addEventListener('show.bs.collapse', () => {
            collapse.style.height = collapse.scrollHeight + 'px';
        });
        collapse.addEventListener('hidden.bs.collapse', () => {
            collapse.style.height = '0px';
        });
    });
</script>
</body>
<style>
    
</style>
</html>
