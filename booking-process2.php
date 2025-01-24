<?php 
    include "role_access.php";
    include("connection.php");
    checkAccess('user');

    if(!isset($_SESSION['dateIn'])&&!isset($_SESSION['dateOut'])){
        echo '<script>
                    window.location="/lanmar/index1.php"; 
         </script>';
    }
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
    <?php include "connection.php"; ?>
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
        .progress-bar span {
            font-size: 16px; /* Default font size for larger screens */
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
        .step span{
            color: black;
        }
        .step.completed .circle {
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D); /* Blue background for completed steps */
            border-color: #00214b; /* Blue border */
            color: white; 
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
        .summary {
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
            color: #fff;
            width: 28%;
            height: 100%;
        }
        .collapse:not(.show){
            display: block;
        }
        #selectedRooms:not(.show) {
            display: none;
        }
        .expand-summary {
            width: 100%;
            background-color: #00214b;
            text-align: center;
            border: none;
            color: #fff;
            font-size: 1rem;
            margin-bottom: 5px;
            cursor: pointer;
            display: none;
        }

        .summary .section-header {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .summary .bg-light {
            background-color: #d1d5db; /* light gray for contrast */
            color: #212529; /* dark text for readability */
        }

        .summary #booked-rooms .btn-link {
            font-size: 1.5rem;
            line-height: 1;
        }

        .summary table {
            margin-top: 10px;
            font-size: 1rem;
        }

        .summary table td {
            border: none;
            padding: 5px 0;
        }

        .summary .btn-primary {
            background-color: #003366;
            border: none;
            font-size: 1.2rem;
        }
        .section-header {
            padding: 10px 0;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .container{
            max-width: 80%;
        }
        .mb-3 {
            margin-bottom: 1rem;
        }
        .form-control, .form-select {
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            appearance: none;
            border-radius: 0.25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
        .btn-primary {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .message-box {
            width: 100%; /* Full width of the parent container */
            max-width: 600px; /* Maximum width */
            height: 50px; /* Height of the input box */
            font-size: 16px; /* Font size for better readability */
            border: 1px solid #ccc; /* Border style */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for aesthetics */
        }
        .timer{
            font-weight: bold;
            border-radius: 10px;
            background-color:rgb(11, 69, 156);
            padding: 10px;
            color: #fff;
            gap: 5px;
        }

    </style>
    <style>
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
        }
        @media (max-width: 768px) {
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
            .container{
                max-width: 100%;
                padding: 20px;
            }
            .guest {
                width: 100% !important; /* Override inline styles */
            }
            .summary {
                width: 100%;
                position: fixed;
                bottom: 0;
                left: 0;
                z-index: 1000;
                transition: height 0.3s ease-in-out;
                overflow: hidden;
            }
            .expand-summary{
                display: block;
                background-color: transparent;
            }
            .summary.collapse {
                height: 60px; /* Initial height for collapsed state */
                border-radius: 15px;
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
                padding: 5%;
            }
            .guest {
                width: 100% !important; /* Override inline styles */
            }
            .summary{
                width: 100% !important;
            }
        }
        @media(max-height: 840px){
            .scrollable-table{
                max-height: 300px; /* Adjust as needed */
                overflow-y: auto;
                overflow-x: hidden; /* Prevent horizontal scrolling if not needed */
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
            <a href="index1.php" class="nav-link text-white active">Book Here</a>
        </li>
        <li><a href="my-reservation.php" class="nav-link text-white">My Reservations</a></li>
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
        <div class="step">
            <div class="circle">4</div>
            <span>Payment & Receipt</span>
        </div>
    </div>
    <div class="label-mobile">
        <span>Guest Information</span>
    </div>
</div>
<!-- phpsyntax for temp storage to process 3-->
<?php
    if (isset($_POST['roomIds']) && !empty($_POST['roomIds'])) {
        $roomIds = $_POST['roomIds']; 
        $_SESSION['roomIds'] = $roomIds;
    }

    if (isset($_GET['Continue'])) {
        $_SESSION['origPrice'] = $_GET['origPrice'] ?? '';
    }
    if (isset($_POST['grandTotal'])&& isset($_POST['roomTotal'])) {
        $_SESSION['grandTotal'] = (int)$_POST['grandTotal'];
        $_SESSION['roomTotal'] = (int)$_POST['roomTotal'];
        $_SESSION['paxcharges'] = (int)$_POST['paxcharges'];
        $_SESSION['additional_rate'] = (int)$_POST['additional_rate'];
    }

    $dateIn = $_SESSION['dateIn'] ?? '';
    $dateOut = $_SESSION['dateOut'] ?? '';
    $checkin = $_SESSION['checkin'] ?? '';
    $checkout = $_SESSION['checkout'] ?? '';
    $numhours = $_SESSION['numhours'] ?? '';
    $adults = $_SESSION['adult'];
    $childs = $_SESSION['child'];
    $pwd = $_SESSION['pwd'];
    $totalPax = $_SESSION['totalpax'] ?? '';
    $origPrice = $_SESSION['original'] ?? '';
    $paxCharges = $_SESSION['paxcharges'] ?? '';
    $roomTotal = $_SESSION['roomTotal'] ?? '';
    $additionalCharges = $_SESSION['additional_rate'] ?? '';
    $grandTotal = $origPrice + $paxCharges + $additionalCharges + $roomTotal;
    $_SESSION['grandTotal'] = $grandTotal;

    

    $dateInDisplay = date("F j, Y" , strtotime($dateIn));
    $dateOutDisplay = date("F j, Y" , strtotime($dateOut));
    $checkinDisplay = (new DateTime($checkin))->format('g:i A');
    $checkoutDisplay = (new DateTime($checkout))->format('g:i A');

    $sql = "SELECT * FROM users where user_id = '$userId'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    $roomIds = $_SESSION['roomIds']; // Ensure $roomIds is set in the session
    if (!empty($roomIds)) {
        // Create a comma-separated string of room IDs for the SQL query
        $roomIdsStr = implode(',', array_map('intval', $roomIds));

        // Query to fetch room details
        $query = "SELECT * FROM rooms WHERE room_id IN ($roomIdsStr)";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die('Query Error: ' . mysqli_error($conn));
        }

        $rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
?>

<!-- Main content -->
<div id="main-content" class="container mt-4 pt-3">
    <div class="d-flex justify-content-start p-1">
        <div class="d-flex timer">
            <div class="">Timer: </div>
            <div id="timer-display">10:00</div>
        </div>
    </div>
    <div class="container1">
        <div class="row " style="justify-content:space-between;">
        <div class="guest col-md-6" style="width: 70%;">
                <div class="section-header">Personal Information</div>
                <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo ucwords($user["firstname"]);?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo ucwords($user["lastname"]);?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="gender" class="form-label">Gender</label>
                            <input type="text" id="gender" name="gender" class="form-control" value="<?php echo ucwords($user["gender"]);?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="phonenum" class="form-label">Contact No.</label>
                            <input type="number" id="phonenum" name="phonenum" class="form-control" value="<?php echo $user["contact_number"];?>" readonly>
                        </div>
                    </div>
                </form>
                <div class="section-header">Additionals</div>
                    <div class="row mb-2">
                        <div class="col-md-10">
                            <label for="" class="form-label">Is there any special request?</label>
                            <textarea class="message-box p-3" name="additional" id="additional" placeholder="Type your message here..." rows="4" cols="50"></textarea>
                        </div>
                    </div>
                <div class="section-header">Payment Method</div>
                <form action="booking-process2.1.php" method="get" id="paymentForm">
                    <input type="radio" name="choice" value="Gcash" id="gcash" class="form-label" required>
                    <label for="gcash" class="form-label">GCash</label>
        
                    <input type="radio" name="choice" value="PayMaya" id="paymaya" class="form-label" required>
                    <label for="paymaya" class="form-label">Pay Maya</label>

                    <input type="hidden" name="additional" id="hiddenAdditional">
                </form>
                </div>

            <div class="col-md-6 p-3 summary collapse" id="bookingSummary">
                <button class="btn btn-link expand-summary" onclick="toggleSummary()">View Booking Summary</button>
                <div class="section-header">Booking Summary</div>

                <div class="bg-light p-2 rounded mb-3">
                    <div class="d-flex justify-content-between">
                        <div>                        
                            <p><strong>Date:</strong> <span id="date-input"><?php echo "$dateInDisplay to $dateOutDisplay";?></span></p>
                            <p><strong>Time:</strong> <span id="time-input"><?php echo "$checkinDisplay to $checkoutDisplay";?></span></p>
                            <p><strong>Total No. of Pax:</strong> <span id="total-pax"><?php echo "$totalPax";?></span></p>
                            <p><strong>Reservation Type:</strong> <span id="reservation-type"><?php 
                                        $reservationTypeId = $_SESSION['reservationType'] ?? null;
                                        $reservationType = ""; 

                                        if ($reservationTypeId) {
                                            $stmt = $pdo->prepare("SELECT reservation_type FROM reservationtype_tbl WHERE id = :id");
                                            $stmt->bindValue(':id', $reservationTypeId, PDO::PARAM_INT);
                                            $stmt->execute();
                                            $reservationType = $stmt->fetchColumn() ?? $reservationType;
                                        }

                                        echo htmlspecialchars($reservationType);
                                    ?></span></p>
                        </div>

                    </div>
                </div>

                <!-- Booked Rooms Section -->
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0"><strong>Charges Summary</strong></h5>
                    </div>
                </div>

                <!-- Total Calculation Section -->
            <div class="scrollable-table">
                <table class="w-100 text-light">
                    <tr>
                        <td>Original Rate:</td>
                        <td class="text-end"><?php echo number_format($origPrice ?? 0); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Additional Rate</strong></td>
                    </tr>
                    <tr>
                        <td>Pax: </td>
                        <td class="text-end"><?php echo $paxCharges ?? 0; ?></td>
                    </tr>
                    <tr>
                        <td>Time Rate: </td>
                        <td class="text-end"><?php echo $additionalCharges ?? 0; ?></td>
                    </tr>
                    <tr>
                        <td>
                            <a href="#" onclick="toggleSelectedRooms(); return false;" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="selectedRooms" class="text-white">View</a>
                            Room:
                        </td>
                        <td class="text-end">
                            <?php echo number_format($roomTotal ?? 0); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="collapse" id="selectedRooms">
                                <ul class="list-unstyled">
                                    <?php if (!empty($rooms)): ?>
                                        <?php foreach ($rooms as $room): ?>
                                            <li>
                                                <div class="d-flex justify-content-between mb-3">
                                                    <span>Room Name: <?php echo htmlspecialchars($room['room_name']); ?></span>
                                                    <span>Capacity: <?php echo htmlspecialchars($room['minpax']) . '-' . htmlspecialchars($room['maxpax']); ?> persons</span>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li>No rooms selected.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Total:</strong></td>
                        <td class="text-end"><strong><?php
                        echo number_format($grandTotal ?? 0);
                        ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>Downpayment:</strong></td>
                        <td class="text-end"><strong>
                            <?php
                                $sql = "SELECT * FROM prices_tbl where payment_name = 'downpayment'";
                                $result = $conn->query($sql);
                                $price = $result->fetch_assoc();
                                echo number_format($price["price"]);
                            ?>
                        </strong></td>
                    </tr>
                    <tr>
                        <td>Payment Method:</td>
                        <td class="text-end"><p id="PaymentChoice">none</p></td>
                    </tr>
                </table>
            </div>
                <button type="submit" class="btn btn-primary w-100 mt-3" onclick="submitFormAndRedirect()" >Continue</button>
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
document.addEventListener("DOMContentLoaded", function() {
    // Timer duration in seconds (10 minutes)
    const timerDuration = 10 * 60;
    const redirectUrl = 'index1.php'; // Replace with your main page URL

    // Function to start or resume the timer
    function startTimer() {
        let endTime = sessionStorage.getItem('bookingTimerEndTime');

        if (!endTime) {
            const currentTime = Date.now();
            endTime = currentTime + timerDuration * 1000; // Set end time
            sessionStorage.setItem('bookingTimerEndTime', endTime);
        }

        const interval = setInterval(() => {
            const now = Date.now();
            const timeLeft = Math.max(0, endTime - now);
            const minutes = Math.floor(timeLeft / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            // Display the timer on the page (replace 'timer-display' with your element ID)
            document.getElementById('timer-display').innerText = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

            // Redirect when time runs out
            if (timeLeft <= 0) {
                clearInterval(interval);
                sessionStorage.removeItem('bookingTimerEndTime'); // Clear the timer
                window.location.href = redirectUrl;
            }
        }, 1000);
    }

    // Call the function to start the timer
    startTimer();
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

function toggleSummary() {
    const summarySection = document.getElementById('bookingSummary');
    const expandButton = document.querySelector('.expand-summary');

    if (summarySection.classList.contains('collapse')) {
        summarySection.classList.remove('collapse');
        summarySection.style.height = '100vh'; // Full screen height
        expandButton.textContent = 'Close';
    } else {
        summarySection.classList.add('collapse');
        summarySection.style.height = '60px'; // Reset to initial height
        expandButton.textContent = 'View Booking Summary';
    }
}

function toggleSelectedRooms() {
    const selectedRooms = document.getElementById('selectedRooms');
    
    if (selectedRooms.classList.contains('show')) {
        selectedRooms.classList.remove('show');
        selectedRooms.setAttribute('aria-expanded', 'false');
    } else {
        selectedRooms.classList.add('show');
        selectedRooms.setAttribute('aria-expanded', 'true');
    }
}


// Function to update the choice value
function Choice() {
            const selectedOption = document.querySelector('input[name="choice"]:checked');
            const output = document.getElementById('PaymentChoice');
            output.textContent = selectedOption ? selectedOption.value : "None";
        }

        // Attach event listener to the form for automatic updates
        const form = document.getElementById('paymentForm');
        form.addEventListener('input', Choice);
function submitFormAndRedirect() {
            var form = document.getElementById('paymentForm');
            // Ensure that a choice is selected before submitting
            if (form.choice.value === "") {
                alert("Please select a payment method.");
                return;
            } else{
                form.choice.value;
            }

            var additional = document.getElementById('additional').value;
            document.getElementById('hiddenAdditional').value = additional;
            // Submit the form
            form.submit();
            //window.location.href = 'booking-process2.1.php';
        }
</script>
</body>
</html>