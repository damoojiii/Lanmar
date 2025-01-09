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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/fontawesome.min.css">
    <?php include "sidebar-design.php"; ?>
    <style>
        .progress-container {
            width: 100%;
            margin: 5px 0;
            height: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background:lightgray;
            transition: margin-left 0.3s ease;
        }

        .progress-bar {
            width: 100%;
            margin-left: 300px;
            display: flex;
            flex-direction: row;
            gap: 3.5rem;
            position: relative;
        }

        .progress-bar::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 28%;
            width: 45%;
            height: 5px;
            background-color: white;
            z-index: -1;
            transform: translateY(-50%);
        }

        .step {
            text-align: center;
            position: relative;
        }

        .step .circle {
            width: 30px;
            height: 30px;
            background-color: white;
            border: 2px solid white;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
        }

        .step.completed .circle {
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D); /* Blue background for completed steps */
            border-color: #00214b; /* Blue border */
            color: white; 
        }

        .step span{
            color: black;
        }

        .step.completed, .step .circle {
            background-color: lightgrey;
            border-color: white; 
            color: white; 
        }

        .step::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 5px;
            background-color: #00214b; /* Blue color for progress line */
            transform: translateY(-50%);
            z-index: -1;
        }

        .step:last-child::after {
            content: none;
        }

        .step:not(.completed)::after {
            background-color: white;
        }

        .label-mobile{
            display: none;
        }
    </style>
    <style>
        .container1{
            width: 80%;
        }
        .bill-message{
            display: flex;
            justify-content: space-between;
            width: 100%;
            gap: 10px;
        }
        .receipt-section {
            padding: 5px 10px;
            border: 1px solid black;
            width: 100%;
        }
        .summary {
            padding: 5px 10px;
        }
        .summary h2{
            text-align: center;
        }
        .summary p{
            text-align: justify;
        }
        .room-list{
            margin-left: 50px;
        }

    </style>
    <style>
        /* responsive */
        @media (max-width: 1024px) {
            nav {
                padding: 20px 50px;
                height: 70px;
            }

            nav a span {
                font-size: 100px;
            }
            
            .progress-bar {
                flex-direction: row;
                gap: 1rem;
            }

            .step .circle {
                width: 25px;
                height: 25px;
                font-size: 12px;
            }
            .progress-bar span {
                font-size: 14px; /* Reduce font size slightly for tablets */
            }
            .container{
                max-width: fit-content;
            }
        }
        @media (max-width: 768px) {
            .container1{
                width: 100%;
            }
            nav {
                padding: 15px 30px;
                height: 60px;
            }

            nav a span {
                font-size: 80px;
            }
            .progress-container.shifted{
                transition: margin-left 0.3s ease;
            }

            .progress-bar {
                flex-direction: row;
                gap: 0;
                margin-left: 0px;
                justify-content: space-evenly;
            }

            .progress-container {
                height: 80px;
                flex-direction: column;
                justify-content: space-evenly;
            }

            .step .circle {
                width: 30px;
                height: 30px;
                font-size: 15px;
            }
            .step span{
                display: none;
            }
            .label-mobile{
                display: block;
                font-size: 13px;
            }
            .bill-message {
                width: 100% !important; /* Override inline styles */
                display: flex !important;
                flex-direction: column !important;
                justify-content: center;
                align-items: center;
            }
            .summary{
                margin: 0;
            }
            .room-list{
            margin-left: 35px;
            }
        }
        @media (max-width: 430px) {
            nav {
                padding: 10px 20px;
                height: 50px;
            }

            nav a span {
                font-size: 60px;
            }

            .progress-bar {
                flex-direction: row;
                gap: 1rem;
            }
            .container{
                max-width: 100%;
                padding: 10%;
            }
            .bill-message {
                width: 100% !important; /* Override inline styles */
                display: flex !important;
                flex-direction: column !important;
            }
            .receipt-section{
                width: 100% !important;
            }
            .summary{
                width: 100% !important;
            }
            .room-list{
            margin-left: 32px;
        }
        }
        @media print {
  /* Hide the main-content during printing */
  #navbar {
    display: none;
  }
  #sidebar{
    display: none;
  }
  .progress-container{
    display: none;
  }
  #makePDF{
    display: none;
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
        <span class="fs-4">Lanmar Resort</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index1.php" class="nav-link text-white active">Book Here</a>
        </li>
        <li><a href="my-reservation.php" class="nav-link text-white">My Reservations</a></li>
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
        <button id="hamburger" class="navbar-toggler" type="button"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
        </div>
    </div>
</nav>

<!-- Progress bar -->
<div class="progress-container">
    <div class="progress-bar">
        <div class="step completed">
            <div class="circle">1</div>
            <span>Check in & Check out</span>
        </div>
        <div class="step completed">
            <div class="circle">2</div>
            <span>Rooms & Rates</span>
        </div>
        <div class="step completed">
            <div class="circle">3</div>
            <span>Guest Information</span>
        </div>
        <div class="step completed">
            <div class="circle">4</div>
            <span>Payment & Receipt</span>
        </div>
    </div>
    <div class="label-mobile">
        <span>Payment & Receipt</span>
    </div>
</div>

<!-- called passing information -->
<?php
    $stmt = $pdo->prepare("SELECT * FROM booking_tbl ORDER BY booking_id DESC LIMIT 1");
    $stmt->execute();
    $latestRow = $stmt->fetch(PDO::FETCH_ASSOC);
    if($latestRow['user_id'] == $userId){
        $id = $latestRow['booking_id'];
    }
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = $userId");
    $stmt->execute();
    $name = $stmt->fetch(PDO::FETCH_ASSOC);
    $fullname = ucwords($name['firstname']) . " " . ucwords($name['lastname']);
    
    $sql = "
        SELECT 
            booking_tbl.booking_id,
            room_tbl.bill_id, room_tbl.room_name
        FROM booking_tbl
        LEFT JOIN room_tbl ON booking_tbl.bill_id = room_tbl.bill_id
        WHERE booking_tbl.booking_id = :id
    ";
    
    $stmt = $pdo->prepare($sql);
    // Bind the parameter
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    // Execute the statement
    $stmt->execute();
    // Fetch all rows
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Process the fetched rows
    $room_data = [];
    if (!empty($rows)) {
        foreach ($rows as $row) {
            $room_data[] = [
                'room_id' => $row['room_id'] ?? null,
                'room_name' => $row['room_name'] ?? null,
            ];
        }
    }

    $sql_solo = "
    SELECT 
        booking_tbl.booking_id, booking_tbl.dateIn, booking_tbl.dateOut, booking_tbl.checkin, booking_tbl.checkout, booking_tbl.hours,
        reservationType_tbl.reservation_type,
        pax_tbl.adult, pax_tbl.child, pax_tbl.pwd,
        bill_tbl.total_bill, bill_tbl.balance
    FROM booking_tbl
    LEFT JOIN reservationType_tbl ON booking_tbl.reservation_id = reservationType_tbl.id
    LEFT JOIN pax_tbl ON booking_tbl.pax_id = pax_tbl.pax_id
    LEFT JOIN bill_tbl ON booking_tbl.bill_id = bill_tbl.bill_id
    WHERE booking_tbl.booking_id = :id
    ";
    $stmt_solo = $pdo->prepare($sql_solo);
    // Bind the parameter
    $stmt_solo->bindParam(":id", $id, PDO::PARAM_INT);
    // Execute the statement
    $stmt_solo->execute();
    // Fetch all rows
    $row = $stmt_solo->fetch(PDO::FETCH_ASSOC);
?>

<!-- Main content -->
<div id="main-content" class="container mt-4 pt-3">
    <div class="container1">
        <div class="row" style="justify-content:space-between;">
        <div class="bill-message" >
            <div class="receipt-section">
                <h2 class="section-header">Reservation Receipt</h2>
                <p><strong>Reservation ID: </strong><span id="ID"><?php if ($row) {
                    echo $row["booking_id"];
                    } else {
                    echo "No record found with user ID: $id.";
                    }
                ?></span></p><br>
                <p><strong>Name: </strong> <span id="name-input"><?php echo $fullname?>
                </span></p>
                <p><strong>Date: </strong> <span id="date-input"><?php if ($row) {
                    echo date("F j, Y", strtotime($row["dateIn"])) . " to " . date("F j, Y", strtotime($row["dateOut"]));
                    } else {
                    echo "No record found with user ID: $id.";
                    }
                ?></span></p>
                <p><strong>Time: </strong> <span id="time-input"><?php if ($row) {
                    $checkinDisplay = (new DateTime($row['checkin']))->format('g:i A');
                    $checkoutDisplay = (new DateTime($row['checkout']))->format('g:i A');
                    echo $checkinDisplay . " to " . $checkoutDisplay;
                    } else {
                    echo "No record found with user ID: $id.";
                    }
                ?></span></p>
                <p><strong>Total No. of Pax: </strong> <span id="total-pax"><?php if ($row) {
                    $totalPax = $row['adult'] + $row['child'] + $row['pwd'];
                    echo $totalPax;
                    } else {
                    echo "No record found with user ID: $id.";
                    }
                ?></span></p>
                <p><strong>Reservation Type: </strong> <span id="reservation-type"><?php if ($row) {
                    echo $row["reservation_type"];
                    } else {
                    echo "No record found with user ID: $id.";
                    }
                ?></span></p>
                <p><strong>Rooms: </strong> <span id="rooms"><?php
                    if (!empty($room_data)) {
                        echo '<div class="room-list">'; // Start the div container
                        foreach ($room_data as $room) {
                            echo '<div class="room-item">' . $room['room_name'] . '</div>'; // Room name inside a div
                        }
                        echo '</div>';
                    }
                ?></span></p><br>
                <p><Strong>Total Amount: </Strong><span><?php if ($row) {
                    echo 'PHP '. number_format($row["total_bill"] ?? 0);
                    } else {
                    echo "No record found with user ID: $id.";
                    }
                    ?></span></p>
                <p><strong>Balance Remaining: </strong><?php echo 'PHP '. number_format($row["balance"]) ?><span></span></p>
            </div>
            <div class="summary">
                <h2>Thank You For Choosing <br> Lanmar Resort</h2>
                <p>If you want two elements with specific dimensions (30px by 70px) displayed side by side horizontally, you can define their width and height in CSS. Here's an example using Flexbox:</p><br><br>
                <div><a href="" onclick="printPage()" id="makePDF">Download Receipt</a></div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.getElementById('hamburger').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
    
    const navbar = document.querySelector('.navbar');
    navbar.classList.toggle('shifted');

    const progbar = document.querySelector('.progress-container');
    progbar.classList.toggle('shifted');
    
    const mainContent = document.getElementById('main-content');
    mainContent.classList.toggle('shifted');
});
function printPage() {
    // Get the button by its ID
    var button = document.getElementById("makePDF");

    // Perform actions based on the button ID
    if (button.id === "makePDF") {
      // You can add more actions here if needed

      // Trigger the print dialog
      window.print();
    }
  }
</script>
</body>
</html>
