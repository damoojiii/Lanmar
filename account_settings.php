<?php

session_start();
include("connection.php");
include "role_access.php";
checkAccess('admin');

// Get any messages from session
$success_message = $_SESSION['success_message'] ?? "";
$error_message = $_SESSION['error_message'] ?? "";
$success_message1 = $_SESSION['success_message1'] ?? "";
$error_message1 = $_SESSION['error_message1'] ?? "";


unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
unset($_SESSION['success_message1']);
unset($_SESSION['error_message1']);

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT firstname, lastname, email, contact_number,profile FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname, $email, $contact_number, $profile_path);
$stmt->fetch();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
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
        .settings-form button, 
        .save-btn {
            border-radius: 10px !important; 
            padding: 10px 15px;
            background-color: rgb(29, 69, 104);
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
        }

        .main-section {
            margin-left: 250px; /* Add margin to align with sidebar */
        }

        @media (max-width: 768px){
            .main-section {
                margin-left: 0; /* Remove margin on mobile */
            }
            .main-content{
                padding: 0;
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
                    <li><a class="dropdown-item" href="account_settings.php">Account Settings</a></li>
                    <li><a class="dropdown-item" href="homepage_settings.php">Homepage Settings</a></li>
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
                <h1 class="text-center mb-4 mt-4" style="font-weight: 700;">Account Settings</h1>
                <div class="row">
                    <div class="col-md-4 mb-4"><!-- First column - Profile Picture only -->
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="text-center">
                                    <h2 class="text-center card-title mb-4">Profile Picture</h2>
                                    <!-- Image wrapped in a clickable element -->
                                    <a href="#" id="profileImageLink" data-bs-toggle="modal" data-bs-target="#profileModal">
                                        <img src="<?php echo htmlspecialchars($profile_path ?? 'profile/default_photo.jpg'); ?>" 
                                             alt="Profile Picture" 
                                             style="width: 200px; height: 200px; border-radius: 50%; margin-bottom: 20px;" />
                                    </a>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-center w-100" style="font-weight: 600;" id="profileModalLabel">Profile Picture</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Add image preview here -->
                                                    <div class="text-center mb-3">
                                                        <img id="modalProfileImage" src="" alt="Profile Picture" style="max-width: 100%; height: auto;">
                                                    </div>
                                                    <div class="d-grid gap-2">
                                                        <button type="button" class="btn btn-secondary" id="changeProfileBtn">Change Profile</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden form for updating profile picture -->
                                    <form action="update_profile.php" method="POST" enctype="multipart/form-data" id="profileForm" style="display: none;">
                                        <div class="mb-3">
                                            <input type="file" class="form-control" name="profile_picture" id="profile_picture" accept="image/*" onchange="this.form.submit()">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mb-4"><!-- Second column - All other inputs -->
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <?php if ($success_message): ?>
                                    <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
                                <?php endif; ?>
                                <?php if ($error_message): ?>
                                    <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                                <?php endif; ?>
                                <form class="settings-form" method="POST" action="update_personal_info.php">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="firstname" class="form-label">First Name</label>
                                                <input type="text" class="form-control" id="firstname" name="firstname" 
                                                    value="<?php echo htmlspecialchars($firstname); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="lastname" class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="lastname" name="lastname" 
                                                value="<?php echo htmlspecialchars($lastname); ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" 
                                                    value="<?php echo htmlspecialchars($email); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="contact_number" class="form-label">Contact Number</label>
                                                <input type="tel" class="form-control" id="contact_number" name="contact_number" 
                                                    value="<?php echo htmlspecialchars($contact_number); ?>" required>
                                            </div>
                                        </div>
                                    <div class="button-container text-center">
                                        <button type="submit" name="update_personal_info" class="save-btn">Update Changes</button>
                                    </div>
                                </form>
                                        <hr class="my-4">
                                        <h5 class="card-title mb-3">Change Password</h5>
                                        <?php if ($error_message1): ?>
                                        <div class="alert alert-danger">
                                            <?php echo htmlspecialchars($error_message1); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($success_message1): ?>
                                        <div class="alert alert-success">
                                            <?php echo htmlspecialchars($success_message1); ?>
                                        </div>
                                    <?php endif; ?>
                                <form class="settings-form" method="POST" action="update_password.php">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="confirm_password" class="form-label">Confirm Password</label>
                                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="message"></div>
                                    </div>
                                    <div class="button-container text-end">
                                        <button type="submit" name="update_password" class="btn" id="update_password" >Update Password</button>
                                    </div>
                                </form>
                            </div>
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
    </script>

    <script>
         $(document).ready(function() {
        var $pass = $("#new_password");
        var $button = $("button[name='update_password']");
        var $form = $("form[action='update_password.php']");

        $button.on("click", function(event) {
            // Prevent form submission initially
            event.preventDefault();

            var passValue = $pass.val();

            if (passValue.length === 0) {
                alert("Tip💡: Add UPPERCASE, lowercase, numbers for more secure passwords");
            } else if (passValue.length < 4) {
                alert("Password seems to be weak, Try more secure passwords.");
            } else if (passValue.length >= 6 && passValue.length < 12) {
                alert("Password seems to be medium, update it to be more secure.");
            } else if (passValue.length >= 12) {
                $form.submit();
            }
        });

        $pass.on("input", function() {
            var passValue = $pass.val();

            if (passValue.length < 4) {
                $pass.css("border-color", "#ff5925");
            } else if (passValue.length >= 6 && passValue.length < 12) {
                $pass.css("border-color", "yellow");
            } else if (passValue.length >= 12) {
                $pass.css("border-color", "#26d730");
            }
        });
    });

    </script>

    <script>
    document.getElementById('profileModal').addEventListener('show.bs.modal', function (event) {
        const imgSrc = document.querySelector('#profileImageLink img').src;
        document.getElementById('modalProfileImage').src = imgSrc;
    });

    document.getElementById('changeProfileBtn').addEventListener('click', function() {
        document.getElementById('profile_picture').click();
        $('#profileModal').modal('hide');
    });

   document.getElementById('profileForm').addEventListener('submit', function() {
        document.body.style.cursor = 'wait';
    });
    </script>

</body>
</html>
