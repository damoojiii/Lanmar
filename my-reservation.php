<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanmar Resort</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/fontawesome.min.css">
    <style>
        .navbar {
            margin-left: 250px; 
            z-index: 1; 
            width: calc(100% - 250px);
            height: 50px;
            transition: margin-left 0.3s ease; 
        }
        #sidebar {
            width: 250px;
            position: -webkit-sticky;
            position: sticky;
            top: 0; 
            height: 100vh;
            overflow-y: auto; 
            transition: transform 0.3s ease;
            background: #001A3E;
        }

        #main-content {
            transition: margin-left 0.3s ease;
            margin: 0 0 0 300px; 
        }

        #hamburger {
            border: none;
            background: none;
        }
        hr{
            background-color: #ffff;
            height: 1.5px;
        }
        .progress{
            width: 100%;
            height: 80px;
            background: #D9D9D9;
        }
        .container{
            display: flex;
            width: 100%;
            padding: 0;
            gap: 20px;
        }
        .legend{
            display: flex;
            justify-content: center;
        }
        #sidebar .nav-link {
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            background-color: #fff !important;
            color: #000 !important;
        }

        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-250px);
            }
            #sidebar.show {
                transform: translateX(0);
            }

            .navbar {
                margin-left: 0;
                width: 100%; 
            }
            .navbar.shifted {
                margin-left: 250px; 
                width: calc(100% - 250px); 
            }

            #main-content {
                margin-left: 0;
            }
            #main-content.shifted {
                margin-left: 250px; 
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
            <a href="index1.php" class="nav-link text-white">
                Book Here
            </a>
        </li>
        <li>
            <a href="my-reservation.php" class="nav-link text-white active">
                My Reservations
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                Notification
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                Chat with Lanmar
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                Feedback
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                Settings
            </a>
        </li>
    </ul>
    <hr>
    <a href="#" class="nav-link text-white">
        Log out
    </a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <button id="hamburger" class="navbar-toggler" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                
            </ul>
        </div>
    </div>
</nav>
<!-- Main content -->
<div id="main-content" class="mt-4 pt-3">
    
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>
</html>

<script>
    document.getElementById('hamburger').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
    
    const navbar = document.querySelector('.navbar');
    navbar.classList.toggle('shifted');
    
    const mainContent = document.getElementById('main-content');
    mainContent.classList.toggle('shifted');
});
</script>