<?php
// Start the session
session_start();

include("connection.php");

$success_message = "";
$error_message = "";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert(' Engggggg'); window.location.href='login.php';</script>";
    exit();
}

// Fetch current user information
$user_id = $_SESSION['user_id']; // Make sure user_id is set
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
    <link rel="stylesheet" href="../assets/css/main.css">

    <style>
        @font-face {
            font-family: 'nautigal';
            src: url(font/TheNautigal-Regular.ttf);
        }

        #sidebar span {
            font-family: 'nautigal';
            font-size: 50px !important;
        }

        #sidebar {
            width: 250px;
            position: fixed;
            top: 0; 
            height: 100vh;
            overflow-y: auto; 
            background: #001A3E;
            transition: transform 0.3s ease;
        }

        #sidebar.collapsed {
            transform: translateX(-100%); /* Hide sidebar */
        }

        .navbar {
            margin-left: 250px; 
            z-index: 1; 
            width: calc(100% - 250px);
            height: 50px;
            transition: margin-left 0.3s ease; 
        }

        #main-content {
            transition: margin-left 0.3s ease;
            margin-left: 250px; 
        }

        #hamburger {
            border: none;
            background: none;
            cursor: pointer;
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
        }

        .dropdown-item {
            color: #000 !important;
        }

        @media (max-width: 768px) {
            #sidebar {
                position: absolute;
                transform: translateX(-100%); /* Hide sidebar off-screen */
            }
            #sidebar.show {
                transform: translateX(0); /* Show sidebar */
            }

            .navbar {
                margin-left: 0;
                width: 100%; 
            }

            #main-content {
                margin-left: 0;
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
    </style>
</head>
<body>
    <?php include 'sidebar_user.php'; ?>
    
    <div class="main-section" class="p-3">
        <div class="flex-container">
            <div class="main-content">
                <h1 class="text-center mb-5 mt-4">Account Settings</h1>
                <div class="row">
                    <div class="col-md-6"><!-- First column -->
                        <div class="settings-form-container">
                            <h2 class="mb-4">Personal Information</h2>
                            <?php if (!empty($success_message)): ?>
                                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                            <?php endif; ?>
                            <form class="settings-form" action="" method="POST">
                                <div>
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
                                </div>
                                <div>
                                    <label for="lastname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                                </div>
                                <div>
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div>
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="tel" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>" required>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6"><!-- Second column -->
                        <div class="settings-form-container">
                            <h2 class="mb-4">Change Password</h2>
                            <form class="settings-form" action="" method="POST">
                                <div>
                                    <label for="current_password" class="form-label">Current Password (required to make changes)</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                <div>
                                    <label for="new_password" class="form-label">New Password (leave blank to keep current password)</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                    <p id="message" style="display: none;"><span id="strength"></span></p>
                                </div>
                                <button type="submit" class="btn" id="update_password">Update Information</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
</script>
</body>
</html>

