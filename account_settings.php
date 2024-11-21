<?php

session_start();

// Get any messages from session
$success_message = $_SESSION['success_message'] ?? "";
$error_message = $_SESSION['error_message'] ?? "";

// Clear the messages so they don't show up again on refresh
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

include("connection.php");

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT firstname, lastname, email, contact_number FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname, $email, $contact_number);
$stmt->fetch();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>

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
    <?php include 'sidebar_admin.php'; ?>
        
    <div id="main-content" class="p-3">
        <div class="flex-container">
            <div class="main-content">
                <h1 class="text-center mb-5 mt-4">Account Settings</h1>
                <div class="row"> <!-- Add this row container -->
                    <div class="col-md-6"> <!-- First column -->
                        <div class="settings-form-container">
                            <h2 class="mb-4">Personal Information</h2>
                            <?php if ($success_message): ?>
                                <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
                            <?php endif; ?>
                            <?php if ($error_message): ?>
                                <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <form class="settings-form" method="POST" action="update_personal_info.php">
                                <div class="form-group">
                                    <label for="firstname">First Name</label>
                                    <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo htmlspecialchars($firstname); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo htmlspecialchars($lastname); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="contact_number">Phone Number</label>
                                    <input type="tel" id="contact_number" name="contact_number" class="form-control" value="<?php echo htmlspecialchars($contact_number); ?>" required>
                                </div>
                                <div class="button-container text-center">
                                    <button type="submit" name="update_personal_info" class="save-btn">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6"> <!-- Second column -->
                        <div class="settings-form-container">
                            <h2 class="mb-4">Change Password</h2>
                            <form class="settings-form" method="POST" action="update_password.php">
                                <div class="form-group">
                                    <label for="email">Current Email</label>
                                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                    <p id="message"><span id="strength"></span></p>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm New Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                </div>
                                <div class="button-container text-center">
                                    <button type="submit" name="update_password" class="save-btn" id="updatePasswordBtn">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var pass = document.getElementById("password");
        var msg = document.getElementById("message");
        var strength = document.getElementById("strength");
        var arrow = document.querySelector("button[name='update_password']");

        arrow.addEventListener("click", function(event) {
            // Prevent form submission initially
            event.preventDefault();

            if (pass.value.length === 0) {
                alert("Tip💡: Add UPPERCASE, lowercase, symbols, letters for more secure passwords");
            } else if (pass.value.length < 4) {
                alert("Password seems to be weak, Try more secure passwords.");
            } else if (pass.value.length >= 6 && pass.value.length < 12) {
                alert("Password seems to be medium, update it to be more secure.");
            } else if (pass.value.length >= 12) {
                alert("Password is strong");
                // Allow form submission if password is strong
                document.querySelector("form[action='update_password.php']").submit();
            }
        });

        pass.addEventListener("input", () => {
            if (pass.value.length > 0) {
                msg.style.display = "block";
            } else {
                msg.style.display = "none";
            }

            if (pass.value.length < 4) {
                strength.innerHTML = "Password is Weak";
                pass.style.borderColor="#ff5925";
                msg.style.color="#ff5925";
            } else if (pass.value.length >= 6 && pass.value.length < 12) {
                strength.innerHTML = "Password is Medium";
                pass.style.borderColor="yellow";
                msg.style.color = "yellow";
            } else if (pass.value.length >= 12) {
                strength.innerHTML = "Password is Strong";
                pass.style.borderColor="#26d730";
                msg.style.color="#26d730";
            }
        });

    </script>

</body>
</html>
