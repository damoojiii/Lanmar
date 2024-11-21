<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanmar Resort</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="assets/css/main.css" rel="stylesheet">
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
            margin-left: 250px; 
        }

        #hamburger {
            border: none;
            background: none;
        }
        hr{
            background-color: #ffff;
            height: 1.5px;
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
            <a href="#" class="nav-link text-white">
                Book Here
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
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
    <a href="logout.php" class="nav-link text-white">
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
<div id="main-content" class="container mt-5 pt-5">
    <div class="container">
        <input id="date-picker" type="text" placeholder="Select a date" readonly>
        <button id="continue-button" class="btn btn-primary mt-2">Continue</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="script.js"></script>
</body>
</html>


<script src="assets/js/script.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#date-picker", {
            enableTime: false,
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                document.querySelector("#date-picker").value = dateStr;
            }
        });

        document.querySelector("#continue-button").addEventListener("click", function() {
            const selectedDate = document.querySelector("#date-picker").value;
            if (selectedDate) {
                // Proceed with the selected date
                alert("Selected date: " + selectedDate);
            } else {
                alert("Please select a date first.");
            }
        });
    });
</script>
