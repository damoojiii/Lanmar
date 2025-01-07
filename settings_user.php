<?php
// Start the session
session_start();
include "role_access.php";
checkAccess('user'); 

include("connection.php");

$success_message = "";
$error_message = "";


// Fetch current user information
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $new_password = $_POST['new_password'];
    $current_password = $_POST['current_password'];

    // Verify current password
    if (password_verify($current_password, $user['password'])) {
        // Update user information
        $update_query = "UPDATE users SET firstname = ?, lastname = ?, email = ?, contact_number = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssssi", $firstname, $lastname, $email, $contact_number, $user_id);
        
        if ($update_stmt->execute()) {
            $success_message = "Your information has been updated successfully.";
            
            // Update password if provided
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $password_query = "UPDATE users SET password = ? WHERE user_id = ?";
                $password_stmt = $conn->prepare($password_query);
                $password_stmt->bind_param("si", $hashed_password, $user_id);
                $password_stmt->execute();
            }
        } else {
            $error_message = "Error updating your information. Please try again.";
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
            border-radius: 10   px !important;  /* Added !important to override Bootstrap */
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
        <span class="fs-4">Lanmar Resort</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index1.php" class="nav-link text-white">Book Here</a>
        </li>
        <li><a href="my-reservation.php" class="nav-link text-white">My Reservations</a></li>
        <li><a href="my-notification.php" class="nav-link text-white">Notification</a></li>
        <li><a href="chats.php" class="nav-link text-white">Chat with Lanmar</a></li>
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
                                        <div class="col-md-6 mb-3">
                                            <label for="firstname" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname" 
                                                   value="<?php echo htmlspecialchars($user['firstname']); ?>" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="lastname" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname" 
                                                   value="<?php echo htmlspecialchars($user['lastname']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact_number" class="form-label">Contact Number</label>
                                        <input type="tel" class="form-control" id="contact_number" name="contact_number" 
                                               value="<?php echo htmlspecialchars($user['contact_number']); ?>" required>
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
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        <p id="message" style="display: none;"><span id="strength"></span></p>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" name="update_password" class="btn" id="update_password">Update Password</button>
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

    document.addEventListener("DOMContentLoaded", function() {
        var pass = document.getElementById("new_password"); // Reference the correct ID
        var msg = document.getElementById("message");
        var strength = document.getElementById("strength");
        var arrow = document.getElementById("update_password"); // Updated to use the correct ID

        arrow.addEventListener("click", function(event) {
            // Check password strength
            if (pass.value.length === 0) {
                alert("Tip💡: Add UPPERCASE, lowercase, symbols, letters for more secure passwords");
                event.preventDefault(); // Prevent form submission
            } else if (pass.value.length < 5) {
                alert("Password seems to be weak, Try more secure passwords.");
                event.preventDefault(); // Prevent form submission
            } else if (pass.value.length >= 5 && pass.value.length < 8) {
                alert("Password seems to be medium, update it to be more secure.");
                event.preventDefault(); // Prevent form submission
            } else if (pass.value.length >= 8) {
                alert("Password is strong. You can update your password.");
                // Allow form submission if password is strong
                document.querySelector("form").submit(); // Submit the form
            }
        });

        pass.addEventListener("input", () => {
            if (pass.value.length > 0) {
                msg.style.display = "block"; // Show the message
            } else {
                msg.style.display = "none"; // Hide the message
            }

            if (pass.value.length < 5) {
                strength.innerHTML = "Password is Weak";
                pass.style.borderColor = "#ff5925"; // Set border color to red
                msg.style.color = "#ff5925"; // Set message color to red
                strength.style.color = "#ff5925"; // Set strength text color to red
            } else if (pass.value.length >= 5 && pass.value.length < 8) {
                strength.innerHTML = "Password is Medium";
                pass.style.borderColor = "#FFA500"; // Set border color to orange
                msg.style.color = "#FFA500"; // Set message color to orange
                strength.style.color = "#FFA500"; // Set strength text color to orange
            } else if (pass.value.length >= 8) {
                strength.innerHTML = "Password is Strong";
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

