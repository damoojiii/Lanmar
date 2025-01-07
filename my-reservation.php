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
        .pending, .cancellation {
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
                padding-inline: 10px;
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

<!-- data fetch-->
<?php
    $id = $userId;
    // Assuming you have already executed the SQL query as shown in your code:
    $sql_solo = "
        SELECT 
            booking_tbl.booking_id, booking_tbl.dateIn, booking_tbl.dateOut, booking_tbl.checkin, booking_tbl.checkout, booking_tbl.hours, booking_tbl.status,
            reservationType_tbl.reservation_type,
            pax_tbl.adult, pax_tbl.child, pax_tbl.pwd,
            bill_tbl.total_bill, bill_tbl.balance, bill_tbl.pay_mode
        FROM booking_tbl
        LEFT JOIN reservationType_tbl ON booking_tbl.reservation_id = reservationType_tbl.id
        LEFT JOIN pax_tbl ON booking_tbl.pax_id = pax_tbl.pax_id
        LEFT JOIN bill_tbl ON booking_tbl.bill_id = bill_tbl.bill_id
        WHERE booking_tbl.user_id = :id ORDER BY booking_id DESC
    ";
    $stmt_solo = $pdo->prepare($sql_solo);
    $stmt_solo->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt_solo->execute();
    $results = $stmt_solo->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = $userId");
    $stmt->execute();
    $name = $stmt->fetch(PDO::FETCH_ASSOC);
    $fullname = $name['firstname'] . " " . $name['lastname'];
 ?> 

<!-- Main content -->
<div id="main-content" class="mt-4 pt-3">
    <h2 class="mb-4">My Reservations</h2>
        <div class="table-responsive">
            <table class="table table-hover text-center">
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
                <?php if(!empty($results)): ?>
                <?php foreach ($results as $row): ?>
                    <tr class="table-row" data-bs-toggle="modal" data-bs-target="#reservationModal" 
                    onclick="showDetails(this)"
                      data-booking-id="<?php echo htmlspecialchars($row['booking_id']); ?>"
                      data-date-range="<?php echo ($row["dateIn"] != $row["dateOut"]) 
                          ? date("F j", strtotime($row["dateIn"])) . ' to ' . date("F j, Y", strtotime($row["dateOut"])) 
                          : date("F j, Y", strtotime($row["dateIn"])); ?>"
                      data-time-range="<?php echo date("g:i A", strtotime($row["checkin"])) . ' to ' . date("g:i A", strtotime($row["checkout"])); ?>"
                      data-adult = "<?php echo htmlspecialchars($row['adult']); ?>"
                      data-child = "<?php echo htmlspecialchars($row['child']); ?>"
                      data-pwd = "<?php echo htmlspecialchars($row['pwd']); ?>"
                      data-total-pax="<?php echo htmlspecialchars($row['adult'] + $row['child'] + $row['pwd']); ?>"
                      data-hours="<?php echo htmlspecialchars($row['hours']); ?>"
                      data-roomtype = "<?php echo htmlspecialchars($row['reservation_type']); ?>"
                      data-paymode="<?php echo htmlspecialchars(htmlspecialchars($row['pay_mode'])); ?>"
                      data-total-bill="<?php echo htmlspecialchars(number_format($row['total_bill'])); ?>"
                      data-balance="<?php echo htmlspecialchars(number_format($row['balance'])); ?>"
                      data-status="<?php echo htmlspecialchars($row['status']); ?>">

                        <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                        
                        <td><?php if ($row["dateIn"] != $row["dateOut"] ) {
                          echo date("F j" , strtotime($row["dateIn"])) . " to " . date("F j, Y" , strtotime($row["dateOut"]));
                          } else {
                            echo date("F j, Y" , strtotime($row["dateIn"]));
                          } ?></td>
                        <td><?php 
                          echo date("g:i A" , strtotime($row["checkin"])) . " to " . date("g:i A" , strtotime($row["checkout"]));
                          ?></td>
                        <td class="d-none d-md-table-cell"><?php $totalPax = $row['adult'] + $row['child'] + $row['pwd'];
                        echo htmlspecialchars($totalPax); ?></td>
                        <td class="">PHP <?php echo number_format($row['total_bill']); ?></td>
                        <td class="">PHP <?php echo number_format($row['balance']); ?></td>
                        <?php 
                        switch ($row['status']) {
                          case "Approved":
                              $class = "approved";
                              $textstatus = "Approved";
                              break;
                          case "Pending":
                              $class = "pending";
                              $textstatus = "Pending";
                              break;
                          case "Cancelled":
                              $class = "cancel";
                              $textstatus = "Cancelled";
                              break;
                          case "Completed":
                              $class = "completed";
                              $textstatus = "Completed";
                              break;
                          case "Cancellation1":
                              $class = "cancellation";
                              $textstatus = "For Cancellation";
                              break;
                        }
                        ?>
                        <td><span class="status-badge <?php echo htmlspecialchars($class); ?> "><?php echo htmlspecialchars($textstatus); ?></span></td>
                        <td class="d-none"><span>
                          <?php 
                          $sql = "
                          SELECT 
                              booking_tbl.booking_id,
                              room_tbl.bill_id, room_tbl.room_name
                          FROM booking_tbl
                          LEFT JOIN room_tbl ON booking_tbl.bill_id = room_tbl.bill_id
                          WHERE booking_tbl.booking_id = :id
                            ";
                          $stmt = $pdo->prepare($sql);
                          $stmt->bindParam(":id", $row['booking_id'], PDO::PARAM_INT);
                          $stmt->execute();
                          $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                          $room_data = [];
                          if (!empty($rows)) {
                              foreach ($rows as $row) {
                                  $room_data[] = [
                                      'room_name'=>$row['room_name'] ?? null,
                                  ];
                              }
                          }
                          ?>
                        </span></td>
                  <?php endforeach; ?>
                  <?php elseif(empty($results)):?>
                    <td colspan="7" style="text-align: center;">No reservations</td>
                    </tr>
                  <?php endif ?>
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
          <p id="reservation-id"><span id="modalBookingId"></p>
        </div>

        <!-- Personal Information Section -->
        <div class="mb-4 ">
          <h6 class="fw-bold">Personal Information</h6>
          <div class="row g-2" >
            <div class="col-12 col-md-4">
              <p><strong>Name:</strong> <?php echo $fullname ?></p>
            </div>
            <div class="col-12 col-md-4">
              <p><strong>Contact No.:</strong> <?php echo $name['contact_number'] ?></p>
            </div>
            <div class="col-12 col-md-4">
              <p><strong>Gender:</strong> WRONG-TURN</p>
            </div>
          </div>
        </div>

        <!-- Booking Details Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Booking Details</h6>
          <div class="row g-2">
            <div class="col-12 col-md-5">
              <p><strong>Date:</strong> <span id="modalDateRange"></p>
            </div>
            <div class="col-12 col-md-3">
              <p><strong>Time:</strong> <span id="modalTimeRange"></p>
            </div>
            <div class="col-12 col-md-3">
              <p><strong>Total Hours:</strong> <span id="modalHours"></span></p>
            </div>
          </div>
          <div class="row g-2">
            <div class="col-4 col-md-2">
              <p><strong>Adults:</strong> <span id="modalAdults"></p>
            </div>
            <div class="col-4 col-md-2">
              <p><strong>Children:</strong> <span id="modalChild"></p>
            </div>
            <div class="col-4 col-md-2">
              <p><strong>PWD:</strong> <span id="modalPwd"></p>
            </div>
            <div class="col-12 col-md-6">
              <p><strong>Total Pax:</strong> <span id="modalTotalPax"></p>
            </div>
          </div>
          <div class="row g-2">
            <div><p><strong>Reservation Type:</strong> <span id="modalroomtype"></p></div>
          </div>
          <div class="row g-2">
            <div><p><strong>Rooms:</strong> <span id="modalrooms"></p></div>
          </div>
        </div>

        <!-- Booking Details Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Booking Details</h6>
          <div class="row g-2">
            <div class="col-12 col-md-4">
              <p><strong>Additionals:</strong> <span id=""></p>
            </div>
          </div>
        </div>

        <!-- Payment Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Payment</h6>
          <div class="row g-2">
            <div class="col-6 col-md-5">
              <p><strong>Payment Method:</strong> <span id="modalpaymode"></span></p>
            </div>
            <div class="col-6 col-md-3">
              <p><strong>Total Price:</strong> ₱ <span id="modalTotalBill"></p>
            </div>
            <div class="col-6 col-md-4">
              <p><strong>Balance Remaining:</strong> ₱ <span id="modalBalance"></p>
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
            <button id="cancelButton" type="button" class="btn" style="width:50px; background-color: #ee1717; border-color: #ee1717;">
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
let bookingId;

function showDetails(row) {
    // Extract data from the clicked row
    bookingId = row.dataset.bookingId;
    const dateRange = row.dataset.dateRange;
    const timeRange = row.dataset.timeRange;
    const hours = row.dataset.hours;
    const adults = row.dataset.adult;
    const children = row.dataset.child;
    const pwds = row.dataset.pwd;
    const totalPax = row.dataset.totalPax;
    const type = row.dataset.roomtype;
    const paymode = row.dataset.paymode;
    const totalBill = row.dataset.totalBill;
    const balance = row.dataset.balance;
    const status = row.dataset.status;

    // Populate the modal with the extracted data
    document.getElementById('modalBookingId').textContent = bookingId;
    document.getElementById('modalDateRange').textContent = dateRange;
    document.getElementById('modalTimeRange').textContent = timeRange;
    document.getElementById('modalHours').textContent = hours;
    document.getElementById('modalAdults').textContent = adults;
    document.getElementById('modalChild').textContent = children;
    document.getElementById('modalPwd').textContent = pwds;
    document.getElementById('modalTotalPax').textContent = totalPax;
    document.getElementById('modalroomtype').textContent = type;
    document.getElementById('modalpaymode').textContent = paymode;
    document.getElementById('modalTotalBill').textContent = totalBill;
    document.getElementById('modalBalance').textContent = balance;
    document.getElementById('modalStatus').textContent = status;

}
function cancelBooking() {
    if (bookingId) { // Make sure bookingId is set
        // Navigate to the cancellation page with the bookingId
        window.location.href = `cancellation_form.php?id=${bookingId}`;
    } else {
        console.log("No bookingId found!");
    }
}
document.getElementById('cancelButton').addEventListener('click', cancelBooking);

</script>