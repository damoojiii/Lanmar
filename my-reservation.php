<?php 
    session_start();
    include("connection.php");
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
    <link rel="stylesheet" href="assets/DataTables/datatables.min.css" />
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
            background: linear-gradient(25deg,rgb(29, 69, 104),#19315D) !important;
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
        td.highlight {
          background-color: rgba(var(--dt-row-hover), 0.052) !important;
        }
        .active>.page-link, .page-link.active {
          background-color: #004080;
          border-color: #004080;
        }
        td.highlight {
          background-color: rgba(var(--dt-row-hover), 0.052) !important;
        }
        .modal-mobile, .modal-mobile-remove{
          background-color: #d6d6d6;
          padding-block: 5px;
        }
        .modal-mobile-add{
          background-color: transparent;
        }
        #proofpicture {
          max-width: 419px;
          max-height: 900px;
          overflow: hidden;
        }
        #proofpicture img{
          width: 100%; /* Make the image responsive to the container's width */
          height: auto; /* Maintain the aspect ratio */
          object-fit: contain;
        }

        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
                padding-inline: 10px;
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
            .modal-dialog {
              max-width: 90vw;
            }
            .modal-mobile, .modal-mobile-remove{
              padding-block: 2px;
            }
            .modal-mobile-remove{
              background-color: transparent;
            }
            .modal-mobile-add{
              background-color: #d6d6d6;
            }
      }
      @media print {
        #main-content {
            display: none;
        }
        #header{
            display: none;
        }
        .proof{
            display: none;
        }
        .btn{
          display: none;
        }
        #modalFooter {
            display: none !important; /* Hides the entire footer */
        }
        /* Ensure the content to print is visible */
        .print-content {
            display: block;
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
        <li><a href="my-reservation.php" class="nav-link text-white active">My Reservations</a></li>
        <li><a href="my-notification.php" class="nav-link text-white target">Notification </a></li>
        <li><a href="chats.php" class="nav-link text-white chat">Chat with Lanmar</a></li>
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
    $fullname = ucwords($name['firstname'] . " " . $name['lastname']);
 ?> 

<!-- Main content -->
<div id="main-content" class="mt-4 pt-3">
    <h2 class="mb-4"><strong>My Reservations</strong></h2>
        <div class="table-responsive">
        
            <table class="table table-hover text-center" id="example" style="width:100%">
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
                    <tr class="table-row" id="triggerElement" data-bs-toggle="modal" data-bs-target="#reservationModal" 
                    data-booking-id="<?php echo htmlspecialchars($row['booking_id']); ?>">

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
                          case "Rejected":
                            $class = "cancel";
                            $textstatus = "Rejected";
                            break;
                          case "Completed":
                              $class = "completed";
                              $textstatus = "Completed";
                              break;
                          case "Cancellation1"||"Cancellation2":
                              $class = "cancellation";
                              $textstatus = "For Cancellation";
                              break;
                        }
                        ?>
                        <td><span class="status-badge <?php echo htmlspecialchars($class); ?> "><?php echo htmlspecialchars($textstatus); ?></span></td>
                        </span></td>
                  <?php endforeach; ?>
                  <?php elseif(empty($results)):?>
                    <td colspan="7" style="text-align: center;">No reservations</td>
                    </tr>
                  <?php endif ?>
                </tbody>
            </table>
        </div>
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
        <div class="mb-4" >
          <h6 class="fw-bold">Reservation ID:</h6>
          <p id="reservation-id" class="py-1" style="background-color: #d6d6d6;"> #<span id="modalBookingId"></span> </p>
        </div>

        <!-- Personal Information Section -->
        <div class="mb-2">
          <h6 class="fw-bold">Personal Information</h6>
          <div class="row g-2">
            <div class="col-12 col-md-4 modal-mobile">
              <p><strong>Name:</strong> <span id="modalName"></span></p>
            </div>
            <div class="col-12 col-md-4 modal-mobile-remove">
              <p><strong>Contact No.:</strong> <span id="modalContact"></span></p>
            </div>
            <div class="col-12 col-md-4 modal-mobile">
              <p><strong>Gender:</strong> <span id="modalGender"></span></p>
            </div>
          </div>
        </div>

        <!-- Booking Details Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Booking Details</h6>
          <div class="row g-2 mb-2" >
            <div class="col-12 col-md-5 modal-mobile">
              <p><strong>Date:</strong> <span id="modalDateRange"></span></p>
            </div>
            <div class="col-12 col-md-4 modal-mobile-remove">
              <p><strong>Time:</strong> <span id="modalTimeRange"></span></p>
            </div>
            <div class="col-12 col-md-3 modal-mobile">
              <p><strong>Total Hours:</strong> <span id="modalHours"></span></p>
            </div>
          </div>
          <div class="row g-2 mb-2">
            <div class="col-4 col-md-3">
              <p><strong>Adults:</strong> <span id="modalAdults"></span></p>
            </div>
            <div class="col-4 col-md-3">
              <p><strong>Children:</strong> <span id="modalChild"></span></p>
            </div>
            <div class="col-4 col-md-3">
              <p><strong>PWD:</strong> <span id="modalPwd"></span></p>
            </div>
            <div class="col-12 col-md-3 modal-mobile-add">
              <p><strong>Total Pax:</strong> <span id="modalTotalPax"></span></p>
            </div>
          </div>
          <div class="row g-2 mb-2 modal-mobile-remove">
            <div><p><strong>Reservation Type:</strong> <span id="modalRoomType"></p></div>
          </div>
          <div class="row g-2">
            <div><p><strong>Rooms:</strong> <span id="modalRooms" class="row g-2"></p></div>
          </div>
        </div>

        <!-- Booking Details Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Special Requests</h6>
          <div class="row g-2" style="background-color: #d6d6d6;">
            <div class="col-12 col-md-4">
              <p><strong>Additionals:</strong> <span id="modalAdds"></p>
            </div>
          </div>
        </div>

        <!-- Payment Section -->
        <div class="mb-4">
          <h6 class="fw-bold">Payment</h6>
          <div class="row g-2 mb-2">
            <div class="col-12 col-md-4 modal-mobile">
              <p><strong>Payment Method:</strong> <span id="modalPaymode"></span></p>
            </div>
            <div class="col-6 col-md-4 modal-mobile-remove">
              <p><strong>Total Price:</strong> ₱ <span id="modalTotalBill"></span></p>
            </div>
            <div class="col-6 col-md-4 modal-mobile-remove">
              <p><strong>Balance Remaining:</strong> ₱ <span id="modalBalance"></span></p>
            </div>
          </div>
          <div class="row g-2">
                <div class="col-6 col-md-4">
                <p><strong>Reference Number:</strong> <span id="modalrefNum"></span></p>
                </div>
                <div class="col-6 col-md-4">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#gcashReceiptModal">
                    View Proof
                </button>
                </div>  
          </div>     
        </div>
      </div>
      <div id="modalFooter" class="modal-footer d-flex justify-content-end">
            <button onclick="window.location.href='chats.php'" type="button" class="btn" style="width:50px; background-color: #19315D; border-color: #19315D;">
                <i class="fa-solid fa-message" style="color: #ffffff;"></i>
            </button>

            <button id="makePDF" onclick="printPage()" type="button" class="btn" style="width:50px; background-color: #19315D; border-color: #19315D;">
                <i class="fa-solid fa-print" style="color: #ffffff;"></i>
            </button>

            <!-- Edit Button -->
            <button id="editButton" type="button" class="btn" style="width:50px; background-color: #19315D; border-color: #19315D;">
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
<!-- Modal for Viewing GCash Receipt (Nested Modal) -->
<div class="modal" id="gcashReceiptModal" tabindex="-1" aria-labelledby="gcashReceiptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg " id="proofpicture">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="gcashReceiptModalLabel">Proof of Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-IMAGE">
        <!-- Image will be dynamically inserted here -->
      </div>
    </div>
  </div>
</div>

<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/all.min.js"></script>
<script src="assets/vendor/bootstrap/js/fontawesome.min.js"></script>
<script src="assets/DataTables/datatables.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>
</html>

<script>
document.getElementById('hamburger').addEventListener('click', function() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('show');
  
  const navbar = document.querySelector('.navbar');
  navbar.classList.toggle('shifted');
  
  const mainContent = document.getElementById('main-content');
  mainContent.classList.toggle('shifted');
  });

  document.getElementById('reservationModal').addEventListener('shown.bs.modal', function () {
    document.getElementById('modalBookingId').focus();
  });

$(document).ready(function() {
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

document.addEventListener('DOMContentLoaded', () => {
  const tableIndex = new DataTable('#example', {
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0
            }
        ],
        order: [0, 'asc'],
        paging: true,
        scrollY: '100%'
    });
 
 tableIndex.on('mouseenter', 'td', function () {
     let colIdx = tableIndex.cell(this).index().column;
  
     tableIndex
         .cells()
         .nodes()
         .each((el) => el.classList.remove('highlight'));
  
     tableIndex
         .column(colIdx)
         .nodes()
         .each((el) => el.classList.add('highlight'));
 });
  const urlParams = new URLSearchParams(window.location.search);
  const bookingId1 = urlParams.get('booking_id');

  if(bookingId1){
      modal(bookingId1);
  }
  let bookingIds;

    // Event delegation to handle row click events
  document.querySelector('tbody').addEventListener('click', function (event) {
      // Ensure the clicked element is a table row
      const row = event.target.closest('.table-row');
      if (row) {
          const bookingId = row.dataset.bookingId; // Get the booking ID
          bookingIds = bookingId;

          modal(bookingIds);
      }
  });
  const viewReceiptButton = document.getElementById('modalProof');
  if (viewReceiptButton) {
      viewReceiptButton.addEventListener('click', function () {
          // Check if the modal exists before opening
          const gcashModal = document.getElementById('gcashReceiptModal');
          const modal = new bootstrap.Modal(gcashModal);
          modal.show();
      });
  }
    // Add event listener for closing the GCash Receipt modal and show Reservation modal
  const gcashModal = document.getElementById('gcashReceiptModal');
  if (gcashModal) {
      gcashModal.addEventListener('hidden.bs.modal', function () {
          const reservationModal = new bootstrap.Modal(document.getElementById('reservationModal'));
          reservationModal.show();
      });
  }

    function cancelBooking() {
      if (bookingIds) { // Make sure bookingId is set
          // Navigate to the cancellation page with the bookingId
          window.location.href = `cancellation_form.php?id=${bookingIds}`;
      } else {
          console.log("No bookingId found!");
      }
    }
    function editBooking() {
      if (bookingIds) { // Make sure bookingId is set
          window.location.href = `my-edit-reservation.php?id=${bookingIds}`;
      } else {
          console.log("No bookingId found!");
      }
    }
    document.getElementById('editButton').addEventListener('click', editBooking);
    document.getElementById('cancelButton').addEventListener('click', cancelBooking);

    function modal(bookingId){
      fetch(`my-reservation-fetch.php?booking_id=${bookingId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Booking not found');
                        return;
                    }

                    // Populate the modal with the fetched data
                    document.getElementById('modalBookingId').textContent = data.bookingId;
                    document.getElementById('modalName').textContent = data.name;
                    document.getElementById('modalContact').textContent = data.contact;
                    document.getElementById('modalGender').textContent = data.gender;
                    const bookStartDate = data.dateIn;
                    document.getElementById('modalDateRange').textContent = data.dateRange;
                    document.getElementById('modalTimeRange').textContent = data.timeRange;
                    document.getElementById('modalHours').textContent = data.hours;
                    document.getElementById('modalAdults').textContent = data.adult;
                    document.getElementById('modalChild').textContent = data.child;
                    document.getElementById('modalPwd').textContent = data.pwds;
                    document.getElementById('modalTotalPax').textContent = data.totalPax;
                    document.getElementById('modalRoomType').textContent = data.type;
                    // Optionally, loop over the rooms and display them in the modal (if needed)
                    const roomsContainer = document.getElementById('modalRooms');
                    roomsContainer.innerHTML = '';
                    let ronum = 1;
                    data.roomName.forEach(room => {
                        const roomElement = document.createElement('div');
                        roomElement.classList.add('room-detail','col-3','col-md-3');
                        roomElement.innerHTML = `
                            <strong>Room ${ronum}:</strong> ${room.roomName}<br>
                        `;
                        roomsContainer.appendChild(roomElement);
                        ronum++;
                    });
                    document.getElementById('modalAdds').textContent = data.additional;
                    document.getElementById('modalPaymode').textContent = data.paymode;
                    document.getElementById('modalTotalBill').textContent = data.totalBill;
                    document.getElementById('modalBalance').textContent = data.balance;
                    document.getElementById('modalrefNum').textContent = data.refNumber;
                    const modalBody = document.getElementById('modal-IMAGE');
                    modalBody.innerHTML = `<img src="${data.imageProof}" alt="GCash Receipt" class="img-fluid">`;
                    const chatButton = document.querySelector('.modal-footer .btn:nth-child(1)');

                const editButton = document.querySelector('#editButton');
                const cancelButton = document.querySelector('#cancelButton');
                const print = document.querySelector('#makePDF');

                console.log(data.status);
                switch (data.status) {
                    case 'Pending':
                      chatButton.style.display = 'block';
                      editButton.style.display = 'none';
                      cancelButton.style.display = 'none';
                      print.style.display = 'none';
                      break;
                    case 'Cancellation1':
                      chatButton.style.display = 'block';
                      editButton.style.display = 'none';
                      cancelButton.style.display = 'block';
                      print.style.display = 'none';
                      break;
                    case 'Cancellation2':
                      chatButton.style.display = 'block';
                      editButton.style.display = 'none';
                      cancelButton.style.display = 'block';
                      print.style.display = 'none';
                      break;
                    case 'Rejected':
                      chatButton.style.display = 'block';
                      editButton.style.display = 'none';
                      cancelButton.style.display = 'none';
                      print.style.display = 'none';
                      break;
                    case 'Cancelled':
                      chatButton.style.display = 'block';
                      editButton.style.display = 'none';
                      cancelButton.style.display = 'none';
                      print.style.display = 'none';
                      break;
                    case 'Completed':
                      chatButton.style.display = 'block';
                      editButton.style.display = 'none';
                      cancelButton.style.display = 'none';
                      print.style.display = 'block';
                      break;
                    case 'Approved':
                      chatButton.style.display = 'block';
                      editButton.style.display = 'block';
                      cancelButton.style.display = 'block';
                      print.style.display = 'none';
                      break; // All buttons remain visible
                }

                  const today = new Date();
                  const dateIn = new Date(bookStartDate);

                  if (dateIn < today) {
                      editButton.disabled = true; // Disable the button
                      editButton.title = 'Cannot edit bookings with a start date in the past.'; // Optional tooltip
                  } else {
                      editButton.disabled = false; // Enable the button
                      editButton.title = ''; // Clear any previous tooltip
                  }

                    // Show the modal
                    $('#reservationModal').modal('show');
                })
                .catch(error => console.error('Error fetching data:', error));
    }

});

function printPage() {
    var button = document.getElementById("makePDF");
    if (button.id === "makePDF") {
    window.print();
    }
}

</script>