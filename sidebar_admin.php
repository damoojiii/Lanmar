<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanmar Resort</title>

    <link rel="stylesheet" href="../assets/css/main.css">
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
    </style>
</head>
<body>
    <!-- Header -->
    <header id="header">
        <button id="hamburger" class="btn btn-primary" onclick="toggleSidebar()">
            ☰
        </button>
        
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
                    <li><a class="dropdown-item" href="approved_reservation.php">Approved Reservations</a></li>
                </ul>
            </li>
            <li>
                <a href="notifications.php" class="nav-link text-white">Notifications</a>
            </li>
            <li>
                <a href="admin_home_chat.php" class="nav-link text-white">Chat with Customer</a>
            </li>
            <li>
                <a href="reservation_history.php" class="nav-link text-white">Reservation History</a>
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
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Settings
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="account_settings.php">Account Settings</a></li>
                    <li><a class="dropdown-item" href="homepage_settings.php">Homepage Settings</a></li>
                    <li><a class="dropdown-item" href="room_settings.php">Room Settings</a></li>
                </ul>
            </li>
        </ul>
        <hr>
        <a href="logout.php" class="nav-link text-white">Log out</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const header = document.getElementById('header');

            sidebar.classList.toggle('show');

            if (sidebar.classList.contains('show')) {
                mainContent.style.marginLeft = '250px'; // Adjust the margin when sidebar is shown
                header.style.marginLeft = '250px'; // Move the header when sidebar is shown
            } else {
                mainContent.style.marginLeft = '0'; // Reset margin when sidebar is hidden
                header.style.marginLeft = '0'; // Reset header margin when sidebar is hidden
            }
        }
    </script>
</body>
</html>
