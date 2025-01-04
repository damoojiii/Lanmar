<?php
// Start the session at the very beginning of the file
session_start();

include("connection.php");

$success_message = "";
$error_message = "";
$gallery_success_message = "";
$gallery_error_message = "";

// Define the target directory for uploads
$targetDir = "uploads/"; 

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle background image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['background_image'])) {
    $image = $_FILES['background_image'];
    $imageName = basename($image['name']);
    $targetFilePath = $targetDir . $imageName;
    $imageType = $image['type'];

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Clear existing settings
    $conn->query("DELETE FROM settings_admin");

    if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO settings_admin (image, image_type) VALUES (?, ?)");
        $stmt->bind_param("ss", $targetFilePath, $imageType); 

        if ($stmt->execute()) {
            $success_message = "Background image updated successfully.";
        } else {
            $error_message = "Error updating background image in the database: " . $stmt->error;
        }

        $stmt->close();
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
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Manage Reservations
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="pending_reservation.php">Pending Reservations</a></li>
                    <li><a class="dropdown-item" href=".php">Approved Reservations</a></li>
                </ul>
            </li>
            <li>
                <a href="admin_notifications.php" class="nav-link text-white">Notifications</a>
            </li>
            <li>
                <a href="admin_home_chat.php" class="nav-link text-white">Chat with Customer</a>
            </li>
            <li>
                <a href="feedback.php" class="nav-link text-white">Feedback</a>
            </li>
            <li>
                <a href="reports.php" class="nav-link text-white">Reports</a>
            </li>
            <li>
                <a href="account_lists.php" class="nav-link text-white">Account List</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Settings
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="account_settings.php">Account Settings</a></li>
                    <li><a class="dropdown-item" href="homepage_settings.php">Homepage Settings</a></li>
                    <li><a class="dropdown-item" href="privacy_settings.php">Privacy Settings</a></li>
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
                        <div class="tab active" id="roomInfoTab">
                            Section 1
                        </div>
                    </a>
                    <a href="homepage_section3.php">
                        <div class="tab " id="archiveInfoTab">
                            Section 2
                        </div>
                    </a>
                    <a href="homepage_section2.php">
                        <div class="tab" id="facilityInfoTab">
                            Section 3
                        </div>
                    </a>
                    <a href="homepage_section4.php">
                        <div class="tab" id="facilityInfoTab">
                            Section 4
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
                        justify-content: center;
                        gap: 10px;
                        flex-wrap: wrap; /* Allow tabs to wrap on smaller screens */
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

                    /* Mobile-specific styles */
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
                    }

                    @media (max-width: 480px) {
                        .tab-container a {
                            min-width: 100px;
                            flex: 1 1 calc(50% - 8px);
                        }
                    }
                </style>
                <div class="flex-container">
                    <!-- Sidebar -->
                    <!-- Main Content -->
                    <div class="main-content">
                        <!-- Main content goes here -->
                        <div class="settings-form-container">
                            <h2 class="text-center mb-4">Change Background Image</h2>
                            <?php if ($success_message): ?>
                                <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
                            <?php endif; ?>
                            <?php if ($error_message): ?>
                                <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                            <?php endif; ?>

                            <form class="settings-form" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="background_image" class="mb-2">Upload New Background Image:</label>
                                    <input type="file" name="background_image" id="background_image" accept="image/*" required class="form-control-file mx-auto d-block" aria-label="Upload New Background Image">
                                </div>
                                <div class="button-container">
                                    <button type="submit" class="update-button" aria-label="Update Background">Update Background</button>
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
</body>
<style>
      .tab-container {
        display: flex;
        margin-top: 5px;
        margin-bottom: 1px;
    }

    .tab-container .tab.active {
        background-color: #01968F;
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
