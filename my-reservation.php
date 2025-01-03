<?php 
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }   
    session_start();
    include "role_access.php";
    checkAccess('user');
    $userId = $_SESSION['user_id']; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanmar Resort</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/fontawesome.min.css">
    <?php include "sidebar-design.php"; ?>
    <style>

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

        thead.custom-header, thead.custom-header th {
            background-color: #19315D !important;
            color: white !important;
        }
        .table-row {
        cursor: pointer;
        transition: background-color 0.2s;
        }

        .table-row:hover {
        background-color: #f1f1f1;
        }
        .pending {
        padding: 0.4em 0.8em;
        font-size: 0.9rem;
        border-radius: 12px;
        background-color: #fbe9a1;
        color: #856404;
        font-weight: bold;
        }
        .completed, .approved{
            padding: 0.4em 0.8em;
            font-size: 0.9rem;
            border-radius: 12px;
            background-color: #B4E380;
            color: #1A5319;
            font-weight: bold;
        }
        .cancel, .rejected{
            padding: 0.4em 0.8em;
            font-size: 0.9rem;
            border-radius: 12px;
            background-color: #F95454;
            color: #C62E2E;
            font-weight: bold;
        }
        .modal-body h6 {
        color: #19315D;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 5px;
        margin-bottom: 10px;
        }

        .modal-body p {
        font-size: 14px; /* Slightly smaller text for mobile */
        margin: 0;
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
            .modal-body h6 {
                font-size: 16px; /* Slightly larger headers for readability */
            }
            .table thead th {
                font-size: 0.8rem;
                padding: 0.5rem;
            }
            .table tbody td {
                font-size: 0.8rem;
                padding: 0.5rem;
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
        <li><a href="my-reservation.php" class="nav-link text-white active">My Reservations</a></li>
        <li><a href="my-notification.php" class="nav-link text-white">Notification</a></li>
        <li><a href="chats.php" class="nav-link text-white">Chat with Lanmar</a></li>
        <li><a href="my-feedback.php" class="nav-link text-white">Feedback</a></li>
        <li><a href="settings_user.php" class="nav-link text-white">Settings</a></li>
    </ul>
    <hr>
    <a href="logout.php" class="nav-link text-white">Log out</a>
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
    <h2 class="mb-4">My Reservations</h2>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="custom-header">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th class="d-none d-md-table-cell">Total No. of Pax</th>
                        <th class="">Total Price</th>
                        <th class="">Remaining Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-row" data-bs-toggle="modal" data-bs-target="#reservationModal" onclick="showDetails(1)">
                        <td>1</td>
                        <td>mm-dd-yyyy</td>
                        <td>hh:mm - hh:mm</td>
                        <td class="d-none d-md-table-cell">12</td>
                        <td class="">PHP 5000</td>
                        <td class="">PHP 2000</td>
                        <td><span class="status-badge approved">Approved</span></td>
                    </tr>
                    <tr class="table-row" data-bs-toggle="modal" data-bs-target="#reservationModal" onclick="showDetails(2)">
                        <td>2</td>
                        <td>mm-dd-yyyy</td>
                        <td>hh:mm - hh:mm</td>
                        <td class="d-none d-md-table-cell">20</td>
                        <td class="">PHP 10000</td>
                        <td class="">PHP 5000</td>
                        <td><span class="status-badge cancel">Cancelled</span></td>
                    </tr>
                    <tr class="table-row" data-bs-toggle="modal" data-bs-target="#reservationModal" onclick="showDetails(2)">
                        <td>3</td>
                        <td>mm-dd-yyyy</td>
                        <td>hh:mm - hh:mm</td>
                        <td class="d-none d-md-table-cell">20</td>
                        <td class="">PHP 10000</td>
                        <td class="">PHP 5000</td>
                        <td><span class="status-badge pending">Pending</span></td>
                    </tr>
                    <tr class="table-row" data-bs-toggle="modal" data-bs-target="#reservationModal" onclick="showDetails(2)">
                        <td>4</td>
                        <td>mm-dd-yyyy</td>
                        <td>hh:mm - hh:mm</td>
                        <td class="d-none d-md-table-cell">20</td>
                        <td class="">PHP 10000</td>
                        <td class="">PHP 5000</td>
                        <td><span class="status-badge completed">Completed</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <nav>
            <ul class="pagination justify-content-end">
                <li class="page-item disabled">
                    <a class="page-link">Previous</a>
                </li>
                <li class="page-item active">
                    <a class="page-link">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link">Next</a>
                </li>
            </ul>
        </nav>
</div>

<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reservationModalLabel">Reservation Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Reservation ID -->
        <div class="mb-4">
          <h6 class="fw-bold">Reservation ID:</h6>
          <p id="reservation-id">#12345</p>
        </div>

        <!-- Personal Information Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Personal Information</h6>
          <div class="row g-2">
            <div class="col-12 col-md-4">
              <p><strong>Name:</strong> Jani Doer</p>
            </div>
            <div class="col-12 col-md-4">
              <p><strong>Contact No.:</strong> 0912345678</p>
            </div>
            <div class="col-12 col-md-4">
              <p><strong>Gender:</strong> Female</p>
            </div>
          </div>
        </div>

        <!-- Booking Details Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Booking Details</h6>
          <div class="row g-2">
            <div class="col-12 col-md-4">
              <p><strong>Date:</strong> mm-dd-yyyy</p>
            </div>
            <div class="col-12 col-md-4">
              <p><strong>Time:</strong> hh:mm - hh:mm</p>
            </div>
            <div class="col-12 col-md-4">
              <p><strong>Total Hours:</strong> 4</p>
            </div>
          </div>
          <div class="row g-2">
            <div class="col-4 col-md-2">
              <p><strong>Adults:</strong> 2</p>
            </div>
            <div class="col-4 col-md-2">
              <p><strong>Children:</strong> 1</p>
            </div>
            <div class="col-4 col-md-2">
              <p><strong>PWD:</strong> 0</p>
            </div>
            <div class="col-12 col-md-6">
              <p><strong>Total Pax:</strong> 3</p>
            </div>
          </div>
          <p><strong>Reservation Type:</strong> Regular</p>
        </div>

        <!-- Payment Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Payment</h6>
          <div class="row g-2">
            <div class="col-12 col-md-6">
              <p><strong>Payment Method:</strong> Credit Card</p>
            </div>
            <div class="col-6 col-md-3">
              <p><strong>Total Price:</strong> ₱5000</p>
            </div>
            <div class="col-6 col-md-3">
              <p><strong>Balance Remaining:</strong> ₱2000</p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-end">
        
            <button type="button" class="btn" style="width:50px; background-color: #19315D; border-color: #19315D;">
                <i class="fa-solid fa-message" style="color: #ffffff;"></i>
            </button>

            <button type="button" class="btn" style="width:50px; background-color: #19315D; border-color: #19315D;">
                <i class="fa-solid fa-pen" style="color: #ffffff;"></i>
            </button>

            <!-- Cancel Button -->
            <button type="button" class="btn" style="width:50px; background-color: #ee1717; border-color: #ee1717;">
                <i class="fa-solid fa-xmark" style="color: #ffffff;"></i>
            </button>
        </div>

    </div>
  </div>
</div>

<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/all.min.js"></script>
<script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>

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