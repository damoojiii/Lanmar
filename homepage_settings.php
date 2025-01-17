<?php
include "role_access.php";
include("connection.php");
checkAccess('admin');

$success_message = "";
$error_message = "";
$desc_success_message = "";
$desc_error_message = "";

// Define the target directory for uploads
$targetDir = "uploads/"; 

// Handle background image upload
if (isset($_POST['background']) && isset($_FILES['background_image'])) {
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

if (isset($_POST['submit_desc'])) {
    var_dump($_POST);
    $description = $_POST['description'] ?? '';
    $description2 = $_POST['description2'] ?? ''; 

    // Validate the descriptions
    if (empty($description) || empty($description2)) {
        $desc_error_message = "Both descriptions are required.";
    } else {
        $conn->query("DELETE FROM about");

        $sql = "INSERT INTO about (description, description_2) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $description, $description2);

        if ($stmt->execute()) {
            $desc_success_message = "Descriptions updated successfully.";
        } else {
            $desc_error_message = "Error updating descriptions: " . $stmt->error;
        }
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
            z-index: 199;
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

        #sidebar .drop{
            height: 50px;
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
                margin-left: 0;
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
        margin-right: 5px;
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
    .head-title{
        font-size: 2.5rem;
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
        .logout{
            margin-bottom: 3rem;
        }
        .settings-form-container {
            margin-top: 10px;
            padding-inline: 10px;
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
                        <div class="tab active" id="roomInfoTab">
                            Homepage
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
                    <a href="homepage_section5.php">
                        <div class="tab " id="facilityInfoTab">
                            Reservation Config
                        </div>
                    </a>
                </div>
                <style>
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
                        background-color: #19315D;
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
                <?php 
                    $sql = "SELECT * FROM about";
                    $sql = $pdo->prepare($sql);
                    $sql->execute();
                    $descriptData = $sql->fetch(PDO::FETCH_ASSOC);
                ?>
                <div class="flex-container">
                    <div class="main-content">
                        <div class="settings-form-container">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">'
                                    <h2 class="text-center mb-4"><strong>Change Background Image</strong></h2>
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
                                            <button type="submit" class="update-button" aria-label="Update Background"  name="background">Update Background</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6 col-sm-12">'
                                <h2 class="text-center mb-4"><strong>About</strong></h2>
                                <?php if ($desc_success_message): ?>
                                    <div class="alert alert-success text-center"><?php echo $desc_success_message; ?></div>
                                <?php endif; ?>
                                <?php if ($desc_error_message): ?>
                                    <div class="alert alert-danger text-center"><?php echo $desc_error_message; ?></div>
                                <?php endif; 
                                ?>

                                <form class="settings-form" method="POST">
                                    <div class="form-group">
                                        <label for="description" class="mb-2">Subtitle: </label>
                                        <textarea name="description" id="description" required class="form-control" rows="4" cols="50"><?php echo htmlspecialchars($descriptData['description'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="description2" class="mb-2">Description: </label>
                                        <textarea type="text" name="description2" id="description2" required class="form-control" rows="4" cols="50"><?php echo htmlspecialchars($descriptData['description_2'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="button-container">
                                        <button type="submit" name="submit_desc" id="submit_desc">Update Description</button>
                                    </div>
                                </form>
                                </div>
                            </div>
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
    document.getElementById('hamburger').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('show');
        
        const navbar = document.getElementById('header');
        navbar.classList.toggle('shifted');
        
        const mainContent = document.getElementById('main-content');
        mainContent.classList.toggle('shifted');
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
