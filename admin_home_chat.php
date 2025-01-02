<?php 
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanmar Resort</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
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

        .contact-container {
            position: relative;
            width: 100%;
            background: #19315D;
            overflow-x: auto;
            white-space: nowrap;
            padding: 10px 0;
            box-sizing: border-box;
            z-index: 0;
        }
        .user-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .user-link:hover .user-name {
            text-decoration: underline;
        }
        
        .user-list {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            margin-right: 20px;
        }

        .user-pic {
            position: relative;
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .user-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .user-name {
            color: #fff;
            font-size: 14px;
            margin-top: 5px;
            text-align: center;
        }

        .new-message {
            position: absolute;
            top: 0;
            right: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 16px;
            height: 16px;
            color: red;
            border-radius: 50%;
        }

    .container {
        max-width: 80%;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .search-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column; 
        position: relative; 
        width: 100%; 
    }

    .search-box {
        width: 600px;
        max-width: 90%;
        border: 1px solid #ccc;
        border-radius: 25px;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative; /* Keep the input box aligned */
        z-index: 1;
    }

    .search-box input {
        flex-grow: 1;
        border: none;
        outline: none;
        font-size: 16px;
        background-color: transparent;
    }

    .search-box button {
        background-color: transparent;
        border: none;
        outline: none;
        cursor: pointer;
        color: #666;
    }
    .search-suggestions {
        position: absolute;
        top: 100%; /* Place directly below the search box */
        width: 600px;
        max-width: 90%;
        background-color: #fff;
        border: 1px solid #ccc;
        border-top: none;
        border-radius: 0 0 25px 25px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 10px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 10;
    }

    .search-suggestion {
        padding: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .search-suggestion img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .search-suggestion:hover {
        background-color: #f5f5f5;
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
            padding: 0;
        }

        #hamburger {
            display: block; /* Show hamburger button on smaller screens */
        }
        .container {
            max-width: 100%;
        }

        .user-list {
            justify-content: space-evenly;
        }

        .user {
            margin: 10px;
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
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

    <div class="contact-container">
        <div class="user-list">
        <?php
        $userlist = $pdo->query("SELECT * FROM users WHERE role = 'user'");
        $users = $userlist->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user) {
            ?>
            <a href="admin_chats.php?user_id=<?php echo $user['user_id']; ?>" class="user-link">
                <div class="user">
                    <div class="user-pic">
                        <img src="https://via.placeholder.com/40" alt="<?php echo htmlspecialchars($user['firstname']); ?>">
                        <span class="new-message"><i class="fas fa-circle fa-beat"></i></span>
                    </div>
                    <div class="user-name"><?php echo htmlspecialchars($user['firstname'] . " " . $user['lastname']); ?></div>
                </div>
            </a>
            <?php
        }
        ?>
        </div>
    </div>

    <div id="main-content" class="container mt-1">
        <h2 class="text-center my-4">Finding a specific person?</h2>
        <div class="search-container">
            <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Search for users...">
                <i class="fas fa-search"></i>
            </div>
            <div class="search-suggestions" id="searchSuggestions" style="display: none;">
                <!-- Search suggestions will be populated here -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        $(document).ready(function () {
        $('#searchInput').on('input', function () {
            const query = $(this).val().trim();

            if (query.length > 0) {
                $.ajax({
                    url: 'search_users.php',
                    method: 'GET',
                    data: { query: query },
                    success: function (response) {
                        const suggestions = JSON.parse(response);
                        let suggestionsHTML = '';

                        suggestions.forEach(user => {
                            suggestionsHTML += `<div class="search-suggestion" data-id="${user.user_id}">
                                ${user.firstname} ${user.lastname}
                            </div>`;
                        });

                        $('#searchSuggestions').html(suggestionsHTML).show();
                    },
                    error: function () {
                        $('#searchSuggestions').hide();
                    }
                });
            } else {
                $('#searchSuggestions').hide();
            }
        });

        // Hide suggestions on clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.search-container').length) {
                $('#searchSuggestions').hide();
            }
        });

        // Handle suggestion click
        $('#searchSuggestions').on('click', '.search-suggestion', function () {
            const userId = $(this).data('id'); // Get the user ID from the data-id attribute
            window.location.href = `admin_chats.php?user_id=${userId}`; // Redirect with the user ID
        });
    });


    </script>
</body>
</html>
