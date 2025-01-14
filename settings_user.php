<?php
session_start();
include("connection.php");
include "role_access.php";
checkAccess('user'); 

$success_message = "";
$error_message = "";

if (isset($_GET['success_message'])) {
    $success_message = urldecode($_GET['success_message']);
}

if (isset($_GET['error_message'])) {
    $error_message = urldecode($_GET['error_message']);
}


// Fetch current user information
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['update_password'])) {
    $new_password = $_POST['new_password'];
    $current_password = $_POST['current_password'];
    $conpass = $_POST['conpass'];

    // Verify current password
    if (password_verify($current_password, $user['password'])) {
            // Update password if provided
        if (!empty($new_password)) {
            if($new_password === $conpass){
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $password_query = "UPDATE users SET password = ? WHERE user_id = ?";
                $password_stmt = $conn->prepare($password_query);
                $password_stmt->bind_param("si", $hashed_password, $user_id);
                $password_stmt->execute();

                $success_message = "Password changed successfully";
            }else{
                $error_message = "New password and Confirm password doesnt match.";
            }    
        }else{
            header("Location: settings_user.php");
            exit();
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
}else if(isset($_POST['update_contact'])){
    $contact_number = $_POST['contact_number'];
    $gender = $_POST['gender'];

    // Update user information
    $update_query = "UPDATE users SET contact_number = ?, gender = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssi", $contact_number,$gender, $user_id);
    if ($update_stmt->execute()) {
        $success_message = "Your information has been updated successfully.";
    } else {
        $error_message = "Error updating your information. Please try again.";
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
    <?php include "sidebar-design.php"; ?>

    <style>

        .dropdown-menu {
            width: 100%;
        }

        .dropdown-item {
            color: #000 !important;
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
            padding: 13px 30px;
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
            border: none;
            cursor: pointer;
            color: white;
        }
        .btn:hover{
            color: #ffffff;
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
        @media (max-width: 768px) {
            .main-section {
                margin-left: 0; /* Remove margin on mobile */
            }
        }
        @media (max-width: 480px) {
            .main-section {
                margin-left: 0; /* Remove margin on mobile */
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
<div id="sidebar" class="d-flex flex-column p-3 text-white position-fixed vh-100">
    <a href="#" class="mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <span class="fs-4 logo">Lanmar Resort</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index1.php" class="nav-link text-white">Book Here</a>
        </li>
        <li><a href="my-reservation.php" class="nav-link text-white">My Reservations</a></li>
        <li><a href="my-notification.php" class="nav-link text-white target">Notification </a></li>
        <li><a href="chats.php" class="nav-link text-white chat">Chat with Lanmar</a></li>
        <li><a href="my-feedback.php" class="nav-link text-white">Feedback</a></li>
        <li><a href="settings_user.php" class="nav-link text-white active">Settings</a></li>
    </ul>
    <hr>
    <a href="logout.php" class="nav-link text-white">Log out</a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <button id="hamburger" class="navbar-toggler" type="button"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
        </div>
    </div>
</nav>
    
    <div class="main-section" class="p-3">
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
                                        <img src="<?php echo htmlspecialchars($user['profile'] ?? 'profile/default_photo.jpg'); ?>" 
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
                                <h2 class="text-center card-title mb-4">Account Information</h2>
                                <?php if (!empty($success_message)): ?>
                                    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($error_message)): ?>
                                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                                <?php endif; ?>
                                <form class="settings-form" action="" method="POST">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="firstname" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname" 
                                                   value="<?php echo htmlspecialchars($user['firstname']); ?>" readonly>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="lastname" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname" 
                                                   value="<?php echo htmlspecialchars($user['lastname']); ?>" readonly>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="contact_number" class="form-label">Contact Number</label>
                                            <input type="tel" class="form-control" id="contact_number" name="contact_number" 
                                                value="<?php echo htmlspecialchars($user['contact_number']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="gender" class="form-label">Gender</label>
                                            <select id="gender" name="gender" class="form-control" required>
                                                <option value="Male" <?php echo ($user['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                                <option value="Female" <?php echo ($user['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                                <option value="Other" <?php echo ($user['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" name="update_contact" class="btn">Update Information</button>
                                    </div>
                                </form>

                                <hr class="my-4">

                                <form class="settings-form" action="" method="POST">
                                    <h2 class="card-title mb-3 text-center ">Change Password</h2>
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                                            <p id="message" style="display: none;"><span id="strength"></span></p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="conpass" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="conpass" name="conpass" required>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" name="update_password" class="btn" id="update_password">Change Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/bootstrap/js/all.min.js"></script>
    <script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>

    <script>
    document.getElementById('hamburger').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
    
    const navbar = document.querySelector('.navbar');
    navbar.classList.toggle('shifted');
    
    const mainContent = document.getElementById('main-content');
    mainContent.classList.toggle('shifted');
    });

    $(document).ready(function() {
        $('input[name="contact_number"]').on('input', function() {
            let value = $(this).val();
            if (value.length > 2) {
                $(this).val(value.slice(0, 11)); // Limit to 2 digits
            }
        });
        function updateNotificationCount() {
            $.ajax({
                url: 'notification_count.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var notificationCount = data;
                    // Update the notification counter in the sidebar
                    var notificationLink = $('.nav-link.text-white.target');
                    if (notificationCount >= 1) {
                        notificationLink.html('Notification <span class="badge badge-notif bg-secondary"></span>');
                    }
                },
                error: function() {
                    console.log('Error retrieving notification count.');
                }
            });
        }
        function updateChatPopup() {
            $.ajax({
                url: 'chat_count.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var counter = data;
                    // Update the notification counter in the sidebar
                    var notificationLink = $('.nav-link.text-white.chat');
          
                    if (counter >= 1) {
                        notificationLink.html('Chat with Lanmar <span class="badge badge-chat bg-secondary"></span>');
                    }
                },
                error: function() {
                    console.log('Error retrieving notification count.');
                }
            });
        }
        updateNotificationCount();
        updateChatPopup();
        setInterval(updateNotificationCount, 5000);
        setInterval(updateChatPopup, 5000);
    });

    document.addEventListener("DOMContentLoaded", function() {
        var pass = document.getElementById("new_password"); // Reference the correct ID
        var msg = document.getElementById("message");
        var strength = document.getElementById("strength");
        var arrow = document.getElementById("update_password"); // Updated to use the correct ID

        pass.addEventListener("input", () => {
            if (pass.value.length > 0) {
                msg.style.display = "block"; // Show the message
            } else {
                msg.style.display = "none"; // Hide the message
            }

            if (pass.value.length < 5) {
                pass.style.borderColor = "#ff5925"; // Set border color to red
                msg.style.color = "#ff5925"; // Set message color to red
                strength.style.color = "#ff5925"; // Set strength text color to red
            } else if (pass.value.length >= 5 && pass.value.length < 8) {
                pass.style.borderColor = "#FFA500"; // Set border color to orange
                msg.style.color = "#FFA500"; // Set message color to orange
                strength.style.color = "#FFA500"; // Set strength text color to orange
            } else if (pass.value.length >= 8) {
                pass.style.borderColor = "#26d730"; // Set border color to green
                msg.style.color = "#26d730"; // Set message color to green
                strength.style.color = "#26d730"; // Set strength text color to green
            }
        });
    });

    document.getElementById('profileModal').addEventListener('show.bs.modal', function (event) {
        const imgSrc = document.querySelector('#profileImageLink img').src;
        document.getElementById('modalProfileImage').src = imgSrc;
    });

    document.getElementById('changeProfileBtn').addEventListener('click', function() {
        document.getElementById('profile_picture').click(); // Trigger file input directly
        $('#profileModal').modal('hide');
    });

    // Optional: Add loading indicator when form is submitted
    document.getElementById('profileForm').addEventListener('submit', function() {
        // You could add a loading spinner here if desired
        document.body.style.cursor = 'wait';
    });
</script>
</body>
</html>

