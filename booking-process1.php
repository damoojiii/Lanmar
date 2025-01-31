<?php    
    include "role_access.php";
    include "connection.php";
    checkAccess('user');
    $userId = $_SESSION['user_id'];
    unset($_SESSION['preDateIn']);
    unset($_SESSION['preDateOut']);

    if (isset($_GET['continue'])) {
        $_SESSION['dateIn'] = $_GET['dateIn'];
        $_SESSION['dateOut'] = $_GET['dateOut'];
        $_SESSION['checkin'] = $_GET['checkin'];
        $_SESSION['checkout'] = $_GET['checkout'];
        $_SESSION['numhours'] = $_GET['numhours'];

        
    }
    
    if(!isset($_SESSION['dateIn'])&&!isset($_SESSION['dateOut'])){
        echo '<script>
                    window.location="/lanmar/index1.php"; 
         </script>';
    }
    $dIn = $_SESSION['dateIn'];
    $dOut = $_SESSION['dateOut'];
    $cIn = $_SESSION['checkin'];
    $cOut = $_SESSION['checkout'];

    $validateTemporary = $pdo->prepare("
        SELECT * FROM temp_booking_tbl 
        WHERE 
            user_id != :userId 
            AND (
                (dateIn <= :dateOut AND dateOut >= :dateIn) -- Date ranges overlap
                AND (
                    (
                        dateIn = :dateIn AND checkin <= :checkout -- Same date as dateIn
                        AND checkout >= :checkin
                    ) OR (
                        dateOut = :dateOut AND checkin <= :checkout -- Same date as dateOut
                        AND checkout >= :checkin
                    ) OR (
                        dateIn < :dateIn AND dateOut > :dateOut -- Fully covers the new booking range
                    )
                )
            )
    ");
    $validateTemporary->execute([
        'userId' => $userId,
        'dateIn' => $dIn,
        'dateOut' => $dOut,
        'checkin' => $cIn,
        'checkout' => $cOut,
    ]);
    $temporaryConflict = $validateTemporary->rowCount() > 0;
    if($temporaryConflict){
        echo '<script>
                    alert("Someones Booking for this range of date or time, Please wait or choose another.");
                    window.location="/lanmar/index1.php"; 
         </script>';
    }else{
        $existingTempBooking = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM temp_booking_tbl 
            WHERE user_id = :userId
        ");
        $existingTempBooking->execute(['userId' => $userId]);
        $existingTempCount = $existingTempBooking->fetchColumn();

        if ($existingTempCount === 0) {
            $temporaryInsert = $pdo->prepare("
                INSERT INTO temp_booking_tbl (user_id, dateIn, dateOut, checkin, checkout, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $temporaryInsert->execute([$userId, $dIn, $dOut, $cIn, $cOut]);
        }
    }
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
        .progress-bar span {
            font-size: 16px; /* Default font size for larger screens */
        }

        .step {
            text-align: center;
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
            color: black;
            font-weight: bold;
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

        .step.completed span{
            color: black;
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
        .add-room:hover, .check:hover{
            background:#19315D;
            color: #fff;
        }
        .mobile-room{
            width: 80%;
        }
        .whole{
            justify-content: space-between;
        }
        .summary {
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
            color: #fff;
            width: 25%;
            height: 100%;
        }
        .collapse:not(.show){
            display: block;
        }
        .expand-summary {
            width: 100%;
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
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
            margin-top: 20px;
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
            background:rgb(24, 50, 99);
            border-color: #0d6efd;
        }
        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .placeholder-img {
            background-size: cover;
            background-position: center;
        }

        #room-selection .list-group-item {
            cursor: pointer;
            height: 70px;
            margin-bottom: 0.5rem;
        }

        #room-selection .list-group-item.active, .add-room, .check{
            background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
            color: white;
        }
        .table-summary{
            margin-top: 10px;
            border-top: 1px solid #ccc;
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
            #room-selection {
                display: none !important;
            }
            
            #room-selection-dropdown {
                display: block;
            }
            .mobile-room{
                width: 100%;
            }
            .add-room{
                font-size: 14px;
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
            .check-btn{
                margin-top: 15px;
            }
            .check{
                width: 100%;
            }
            
        }
        @media (min-width: 768px) {
            #room-selection-dropdown {
                display: none !important;
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
            .summary{
                width: 100% !important;
            }
        }
        @media (max-height: 932px){
            #booked-rooms {
                max-height: 300px; /* Adjust height as needed */
                overflow-y: auto;
                padding: 5px;
            }
        }
        @media (max-height: 844px){
            #booked-rooms {
                max-height: 200px; /* Adjust height as needed */
            }
        }
        @media (max-height: 740px){
            #booked-rooms {
                max-height: 100px; /* Adjust height as needed */
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
        <div class="step">
            <div class="circle">3</div>
            <span>Guest Information</span>
        </div>
        <div class="step">
            <div class="circle">4</div>
            <span>Payment & Receipt</span>
        </div>
    </div>
    <div class="label-mobile">
        <span>Room & Rates</span>
    </div>
</div>

<?php 
    $rooms = [];
    $totalpax = 0;

    // Load from session if available
    $dateIn = $_SESSION['dateIn'] ?? '';
    $dateOut = $_SESSION['dateOut'] ?? '';
    $checkin = $_SESSION['checkin'] ?? '';
    $checkout = $_SESSION['checkout'] ?? '';
    $numhours = $_SESSION['numhours'] ?? '';

    // Check if booking is for a single day or overnight
    $rateType = ($dateIn === $dateOut) ? '1' : '2';

    $rateQuery = $pdo->prepare("SELECT price FROM prices_tbl WHERE id = :rateType");
    $rateQuery->bindValue(':rateType', $rateType, PDO::PARAM_STR);
    $rateQuery->execute();
    $rate = $rateQuery->fetchColumn();

    $_SESSION['rate'] = $rate;
    $_SESSION['original'] = $rate;
    
    $dateInDisplay = date("F j, Y" , strtotime($dateIn));
    $dateOutDisplay = date("F j, Y" , strtotime($dateOut));
    $checkinDisplay = (new DateTime($checkin))->format('g:i A');
    $checkoutDisplay = (new DateTime($checkout))->format('g:i A');

    // Calculate total pax and get room info if check is set
    if (isset($_GET['check'])) {
        $_SESSION['adult'] = filter_input(INPUT_GET, 'adults', FILTER_SANITIZE_NUMBER_INT);
        $_SESSION['child'] = filter_input(INPUT_GET, 'children', FILTER_SANITIZE_NUMBER_INT);
        $_SESSION['pwd'] = filter_input(INPUT_GET, 'pwd', FILTER_SANITIZE_NUMBER_INT);
        $_SESSION['reservationType'] = $_GET['reservationType'];

        $totalpax = (int)$_SESSION['adult'] + (int)$_SESSION['child'] + (int)$_SESSION['pwd'];
        //if($totalpax >= 10 && $totalpax <= 15 && )
        $_SESSION['totalpax'] = $totalpax;

        $adult = $_SESSION['adult'] ?? 0;
        $child = $_SESSION['child'] ?? 0;
        $pwd = $_SESSION['pwd'] ?? 0;
        $_SESSION['roomIds'] = 0;
        
        $additionalCharge = 0;
        if ($adult > 10) {
            $extraAdultCount = $adult - 10;
            $extraRateQuery = $pdo->prepare("SELECT price FROM prices_tbl WHERE id = :id");
            $extraRateQuery->bindValue(':id', 3 , PDO::PARAM_INT);
            $extraRateQuery->execute();
            $additionalCharge = $extraRateQuery->fetchColumn() * $extraAdultCount;
        }
        $_SESSION['additionalCharge'] = $additionalCharge;
        $_SESSION['rate'] = $rate + $additionalCharge;

        // Load rooms based on pax capacity
        if ($totalpax > 0) {
            $sql = "
                SELECT room_id, room_name, image_path, description, minpax, maxpax, price, is_offered 
                FROM rooms
                " . 
                ($rateType == '1' ? "ORDER BY (minpax <= :totalpax AND maxpax >= :totalpax) DESC, minpax ASC" : "") . "
            ";

            $stmt = $pdo->prepare($sql);

            if ($rateType == '1') {
                $stmt->bindValue(':totalpax', $totalpax, PDO::PARAM_INT);
            }

            $stmt->execute();
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
    } else {
        $totalpax = $_SESSION['totalpax'] ?? 0;
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
        <div class="row whole">
            <div class="guest col-md-6" style="width: 75%;">
                <div class="section-header">Number of Guest (Pax)</div>
                <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row mb-2">
                        <div class="col-12 col-md-2">
                            <label for="adults" class="form-label">Adult(s)</label>
                            <input type="number" min="0" id="adults" name="adults" class="form-control" value="<?php echo $adult; ?>" required>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="children" class="form-label">Child(ren) (5 years below)</label>
                            <input type="number" min="0" id="children" name="children" class="form-control" value="<?php echo $child; ?>">
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="pwd" class="form-label">PWD(s)</label>
                            <input type="number" min="0" id="pwd" name="pwd" class="form-control" value="<?php echo $pwd; ?>">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="reservationType" class="form-label">Type of Reservation:</label>
                            <select id="reservationType" name="reservationType" class="form-control" required>
                                <?php 
                                    $typelist = $pdo->query("SELECT * FROM reservationtype_tbl;");
                                    $types = $typelist->fetchAll(PDO::FETCH_ASSOC);

                                    $selectedType = $_SESSION['reservationType'] ?? '';

                                    echo '<option value="" hidden>Choose...</option>';
                                    foreach($types as $type) {
                                        $typename = $type['reservation_type'];
                                        $typeId = $type['id'];
                                    
                                        $isSelected = ($typeId == $selectedType) ? 'selected' : '';
                                        
                                        echo "<option value='$typeId' $isSelected>$typename</option>";
                                    }
                                ?>
                            </select>

                        </div>
                        <div class="col-12 col-md-2 check-btn" style="align-content: flex-end;">
                            <button type="submit" name="check" class="btn check" id="firstform" >Check Rooms</button>
                        </div>
                    </div>
                </form>

                <div class="section-header">Select Room(s)</div>

                <?php if ($totalpax > 0 && !empty($rooms)): ?>
                    <div class="row px-2">
                        <!-- Room Selection for Desktop -->
                        <div class="list-group d-none d-md-block" id="room-selection" style="width: 20%;">
                            <?php foreach ($rooms as $room): ?>
                                <button type="button" class="list-group-item list-group-item-action room-btn" 
                                        data-id="<?php echo $room['room_id']; ?>" 
                                        data-offered="<?php echo $room['is_offered']; ?>">
                                    <?php echo htmlspecialchars($room['room_name']); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <!-- Room Selection for Mobile -->
                        <div class="d-md-none mb-3">
                            <select id="room-selection-dropdown" class="form-select">
                                <option selected hidden disabled>Select a room</option>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?php echo $room['room_id']; ?>" 
                                            data-offered="<?php echo $room['is_offered']; ?>">
                                        <?php echo htmlspecialchars($room['room_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Room Details Section -->
                        <div class="mobile-room col-md-6 py-3">
                            <div id="room-details">
                                <div class="placeholder-text">Select a room to view details.</div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-danger text-center">Please fill up the number of guests before you view the rooms.</p>
                <?php endif; ?>
        
            </div>
                    
            <div class="col-md-6 p-3 summary collapse" id="bookingSummary">
                <button class="btn btn-link expand-summary" type="button" onclick="toggleSummary()">View Booking Summary</button>
                <form action="booking-process2.php" method="POST" id="secondForm">
                    <div class="section-header">Booking Summary</div>

                    <div class="bg-light p-3 rounded mb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p><strong>Date:</strong> <span id="date-input"><?php echo "$dateInDisplay to $dateOutDisplay";?></span></p>
                                <input type="hidden" id="date-in" value="<?php echo $dateIn;?>">
                                <input type="hidden" id="date-out" value="<?php echo $dateOut;?>">
                                <input type="hidden" id="check-in" value="<?php echo $checkin;?>">
                                <input type="hidden" id="check-out" value="<?php echo $checkout;?>">
                                <p><strong>Time:</strong> <span id="time-input"><?php echo "$checkinDisplay to $checkoutDisplay";?></span></p>
                                <p><strong>Total of Hours:</strong> <span id="hour-input"><?php echo $numhours;?></span></p>
                                <p><strong>No. of Pax:</strong> <span id="total-pax">0</span></p>
                                <p><strong>Reservation Type:</strong> <span id="reservation-type">
                                    <?php 
                                        $reservationTypeId = $_SESSION['reservationType'] ?? null;
                                        $reservationType = ""; 

                                        if ($reservationTypeId) {
                                            $stmt = $pdo->prepare("SELECT reservation_type FROM reservationtype_tbl WHERE id = :id");
                                            $stmt->bindValue(':id', $reservationTypeId, PDO::PARAM_INT);
                                            $stmt->execute();
                                            $reservationType = $stmt->fetchColumn() ?? $reservationType;
                                        }

                                        echo htmlspecialchars($reservationType);
                                    ?>
                                </span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Booked Rooms Section -->
                    <div class="row align-items-center mb-2">
                        <div class="col">
                            <h6 class="mb-0">Booked Rooms</h6>
                        </div>
                    </div>
                
                    <div id="booked-rooms" class="mb-3">
                        <div id="no-rooms-message" class="text-light text-center py-2">No Room(s) Selected</div>
                    </div>

                    <!-- Total Calculation Section -->
                    <table class="w-100 text-light table-summary">
                        <tr>
                            <td>Rate:</td>
                            <td class="text-end" id="rate"></td>
                        </tr>
                        <tr>
                            <td>Room:</td>
                            <td class="text-end" id="room-total">PHP 0</td>
                        </tr>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td class="text-end"><strong id="grand-total"></strong></td>
                        </tr>
                    </table>
                    <input type="hidden" name="base_rate" value="">
                    <input type="hidden" name="extra_adult_rate" value="">
                    <input type="hidden" name="additional_rate" value="">
                    <input type="hidden" name="reservationType" value="<?php echo htmlspecialchars($reservationType); ?>">
                    <input type="hidden" name="grandTotal" id="grandTotal">
                    <input type="hidden" name="roomTotal" id="roomTotal">
                    <input type="hidden" name="paxcharges" id="paxcharges" value="">
                    <div id="response-container"></div>

                    <button id="Continue" name="continue" type="submit" class="btn btn-primary w-100 mt-3" >Continue</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/vendor/bootstrap/js/jquery.min.js"></script>

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
var rooms = [];
const rateType = '<?php echo $rateType; ?>';

function toggleSummary() {
    const summarySection = document.getElementById('bookingSummary');
    const expandButton = document.querySelector('.expand-summary');

    if (summarySection.classList.contains('collapse')) {
        summarySection.classList.remove('collapse');
        summarySection.style.height = '90vh'; // Full screen height
        expandButton.textContent = 'Close';
    } else {
        summarySection.classList.add('collapse');
        summarySection.style.height = '60px'; // Reset to initial height
        expandButton.textContent = 'View Booking Summary';
    }
}
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

    $(document).ready(function() {
        $('input[name="adults"], input[name="children"], input[name="pwd"]').on('input', function() {
            let value = $(this).val();
            if (value.length > 2) {
                $(this).val(value.slice(0, 2)); // Limit to 2 digits
            }
        });

        function fetchBaseRate() {
            const dateIn = $('#date-in').val();
            const dateOut = $('#date-out').val();
            const checkOut = $('#check-out').val();
            let adults = parseInt($('input[name="adults"]').val()) || 0;
            let totalPax = adults;

            if (dateIn && dateOut) {
                $.ajax({
                    url: 'fetch_rate_user.php',
                    type: 'POST',
                    data: { dateIn: dateIn, dateOut: dateOut, checkOut: checkOut, totalPax: totalPax },
                    success: function(response) {
                        let result = JSON.parse(response);
                        let baseRate = result.baseRate;
                        let extraAdultRate = result.extraAdultRate;
                        let additional = result.additional;

                        $('input[name="base_rate"]').val(baseRate);
                        $('input[name="extra_adult_rate"]').val(extraAdultRate);
                        $('input[name="additional_rate"]').val(additional);
                        
                        recomputeTotalBill(); // Recompute total bill with new rates
                    }
                });
            }
        }

        function recomputeTotalBill() {
            let offeredCount = 0;
            let baseRate = parseInt($('input[name="base_rate"]').val()) || 0;
            let extraAdultRate = parseInt($('input[name="extra_adult_rate"]').val()) || 0;
            let adults = parseInt($('input[name="adults"]').val()) || 0;
            let children = parseInt($('input[name="children"]').val()) || 0;
            let pwd = parseInt($('input[name="pwd"]').val()) || 0;

            let totalPax = adults + children + pwd;
            let extraAdults = Math.max(0, adults - 10);
            let additionalCharge = extraAdults * extraAdultRate;

            // Get the date-in and date-out values
            let dateIn = $('#date-in').val();
            let dateOut = $('#date-out').val();
            let isOvernight = dateIn !== dateOut;

            // Total room price calculation
            let totalRoomPrice = 0;
            let freeRoomApplied = false; // Track if free room discount has been applied

            $('.room-item').each(function() {
                let roomPrice = parseInt($(this).data('price')) || 0;
                const offered = parseInt($(this).data('isOffered')) || 0;

                if (isOvernight && offered === 1 && !freeRoomApplied) {
                    freeRoomApplied = true; // Apply free room discount only once
                } else {
                    totalRoomPrice += roomPrice;
                }
            });
    
            // Total bill calculation including base rate and additional charges
            console.log(baseRate, additionalCharge, totalRoomPrice);
            let totalBill = baseRate + additionalCharge + totalRoomPrice;
            let original = baseRate + additionalCharge;
            
            $('#grand-total').val(totalBill);
            $('#grand-total').text('PHP ' + totalBill.toLocaleString());
            $('#rate').text('PHP ' + original.toLocaleString());
            $('#total-pax').text(totalPax);
            $('#paxcharges').val(additionalCharge);
        }

        $('input[name="adults"], input[name="children"], input[name="pwd"]').on('input', function() {
            fetchBaseRate();
            recomputeTotalBill();
        });

        fetchBaseRate();
    });


document.addEventListener("DOMContentLoaded", function () {
    // Timer duration in seconds (10 minutes)
    const timerDuration = 10 * 60;
    const redirectUrl = 'index1.php'; 

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

    document.querySelectorAll('.room-btn').forEach(button => {
        const isOffered = button.dataset.offered === "1";
        if (rateType === '2' && !isOffered) {
            button.style.display = 'none';
        }
    });

    document.querySelectorAll('.room-btn').forEach(button => {
        button.addEventListener('click', function() {
            
            const roomId = this.dataset.id;
            
            // Highlight the selected room
            document.querySelectorAll('.room-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            fetch(`getRoomDetails.php?room_id=${roomId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('room-details').innerHTML = `
                        <div id="success-alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
                            <span id="alert-message"></span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <img src="${data.image_path}" class="placeholder-img" style="width: 100%; height: 200px;">
                            </div>
                            <div class="col-md-6">
                                <h5>${data.room_name}</h5>
                                <p>${data.description}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6>Rate & Details</h6>
                            <div class="d-flex gap-2">
                                <div class="p-3 bg-light" style="flex: 1;">
                                    <p><strong>PHP ${data.price}</strong></p>
                                    <p>Good for: ${data.minpax}-${data.maxpax} pax</p>
                                    <a href="#" class="text-decoration-underline">Conditions</a>
                                </div>
                                <div class="p-3 bg-secondary text-white" style="flex: 1;">
                                    <h6>Includes:</h6>
                                    <ul class="list">
                                        ${data.inclusions.map(inclusion => `<li>${inclusion}</li>`).join('')}
                                    </ul>
                                    <button class="btn mt-2 add-room" onclick="addToSummary(${roomId}, '${data.room_name}', ${data.price}, ${data.minpax}, ${data.maxpax}, ${data.is_offered})">+ Book this room</button>
                                </div>
                            </div>
                        </div>
                    `;
                });
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const roomDropdown = document.getElementById('room-selection-dropdown');

    // Filter out non-offered rooms based on rateType
    for (let i = 0; i < roomDropdown.options.length; i++) {
        const option = roomDropdown.options[i];
        const isOffered = option.getAttribute('data-offered') === "1";
        if (rateType === '2' && !isOffered) {
            option.style.display = 'none';
        }
    }

    // Event listener for room selection from dropdown
    roomDropdown.addEventListener('change', function () {
        const selectedRoomId = this.value;

        // Fetch and display room details based on selected room ID
        fetch(`getRoomDetails.php?room_id=${selectedRoomId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('room-details').innerHTML = `
                    <div id="success-alert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
                        <span id="alert-message"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <img src="${data.image_path}" class="placeholder-img" style="width: 100%; height: 200px;">
                        </div>
                        <div class="col-md-6 mt-2">
                            <h5>${data.room_name}</h5>
                            <p>${data.description}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6>Rate & Details</h6>
                        <div class="d-flex gap-2">
                            <div class="p-3 bg-light" style="flex: 1;">
                                <p><strong>PHP ${data.price}</strong></p>
                                <p>Good for: ${data.minpax}-${data.maxpax} pax</p>
                                <a href="#" class="text-decoration-underline">Conditions</a>
                            </div>
                            <div class="p-3 bg-secondary text-white" style="flex: 1;">
                                <h6>Includes:</h6>
                                <ul class="list">
                                    ${data.inclusions.map(inclusion => `<li>${inclusion}</li>`).join('')}
                                </ul>
                                <button class="btn mt-2 add-room" onclick="addToSummary(${selectedRoomId}, '${data.room_name}', ${data.price}, ${data.minpax}, ${data.maxpax}, ${data.is_offered})">+ Book this room</button>
                            </div>
                        </div>
                    </div>
                `;
            });
    });
});


let offeredRoomAdded = false;

// Function to show all rooms
function showAllRooms() {
    document.querySelectorAll('.room-btn').forEach(button => {
        button.style.display = 'block'; 
    });

    const roomDropdown = document.getElementById('room-selection-dropdown');
    for (let i = 0; i < roomDropdown.options.length; i++) {
        const option = roomDropdown.options[i];
        option.style.display = 'block';
    }
}

// Function to show only is_offered rooms
function showOfferedRoomsOnly() {
    document.querySelectorAll('.room-btn').forEach(button => {
        if (button.dataset.isOffered === '1') {
            button.style.display = 'block'; 
        } else {
            button.style.display = 'none'; 
        }
    });
}

// Function to add selected room to the Booked Rooms summary
function addToSummary(roomId, roomName, price, minpax, maxpax, isOffered) {
    rooms.push(roomId);
    const bookedRoomsContainer = document.getElementById('booked-rooms');
    const noRoomsMessage = document.getElementById('no-rooms-message');

    if (document.getElementById(`room-${roomId}`)) {
        alert("This room is already added to the summary.");
        return;
    }

    if (noRoomsMessage) {
        noRoomsMessage.style.display = 'none';
    }

    // Create the room summary 
    const roomSummary = document.createElement('div');
    roomSummary.classList.add('p-3', 'mb-2', 'bg-light', 'text-dark', 'd-flex', 'justify-content-between', 'align-items-start', 'rounded', 'room-item');
    roomSummary.id = `room-${roomId}`;
    
    // Create the remove button
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'btn btn-link text-danger p-0';
    removeButton.innerHTML = '&times;';
    removeButton.setAttribute('aria-label', `Remove ${roomName}`);
    removeButton.onclick = () => removeRoom(roomId, price);

    if (isOffered === 1) {
        roomSummary.dataset.isOffered = '1';
        roomSummary.dataset.price = price;
    }else if(isOffered === 0){
        roomSummary.dataset.isOffered = '0';
        roomSummary.dataset.price = price;
    }
    
    // Add content to room summary
    roomSummary.innerHTML = `
        <div>
            <strong>${roomName}</strong>
            <p>Good for: ${minpax}-${maxpax} pax<br>Subtotal: PHP ${price}</p>
        </div>
    `;
    roomSummary.appendChild(removeButton);
    bookedRoomsContainer.appendChild(roomSummary);

    addRoomIdToForm(roomId);

    if (rateType === '2') {
        if (offeredRoomAdded) {
            updateTotal(price); 
        } else {
            offeredRoomAdded = true; 
        }
    } else {
        updateTotal(price);
    }

    showAllRooms(); 
    showSuccessAlert(`"${roomName}" is added successfully.`);
}

function showSuccessAlert(message) {
    const alertElement = document.getElementById('success-alert');
    const alertMessage = document.getElementById('alert-message');
    
    alertMessage.textContent = message;
    alertElement.style.display = 'block';
    alertElement.classList.add('show');
    alertElement.classList.remove('fade');

    setTimeout(() => {
        alertElement.classList.add('fade');
        alertElement.classList.remove('show');
        setTimeout(() => {
            alertElement.style.display = 'none';
        }, 150);
    }, 3000); // Time in milliseconds before fading out
}

function addRoomIdToForm(roomId) {
    const form = document.getElementById('secondForm');  // Get the form element by ID

    // Create a hidden input element for the roomId
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'roomIds[]';  // Use an array notation for multiple room IDs
    input.value = roomId;

    // Append the input field to the form
    form.appendChild(input);
}
function removeRoomIdFromForm(roomId) {
    const form = document.getElementById('secondForm');  // Get the form element by ID
    const inputs = form.querySelectorAll('input[name="roomIds[]"]');  // Get all hidden inputs with name 'roomIds[]'

    inputs.forEach(input => {
        if (input.value === String(roomId)) {
            const parent = input.parentNode;
            console.log(parent.removeChild(input)); // Get the parent node of the input element
            parent.removeChild(input);  // Remove the matching input from the form
            
        }
    });
}



function updateRemoveButtons() {
    const bookedRoomsContainer = document.getElementById('booked-rooms');
    const allRooms = Array.from(bookedRoomsContainer.querySelectorAll('.room-item'));
    const offeredRooms = allRooms.filter(room => room.dataset.isOffered === '1');
    let countOffered = 0;

    allRooms.forEach(room => {
        const isOffered = parseInt(room.dataset.isOffered) || 0;
        if (isOffered === 1) {
            countOffered++;
        }
    });


    if (rateType === '2') {
        allRooms.forEach(room => {
            const removeButton = room.querySelector('button');
            const offered = parseInt(room.dataset.offered) || 0;

            // Check if the removeButton exists
            if (!removeButton) {
                console.warn(`No remove button found for room: ${room.id}`);
                return;
            }
            console.log((countOffered === 1 || countOffered === 0) && allRooms.length === 1, countOffered, allRooms.length);
            if ((countOffered === 1 || countOffered === 0) && allRooms.length === 1) {
                removeButton.onclick = () => alert("You need at least one of the offered room for overnight stays.");
            } else {
                console.log('pumasok');
                removeButton.onclick = () => removeRoom(room.id.replace('room-', ''), parseFloat(room.querySelector('p').textContent.match(/PHP (\d+)/)?.[1] || 0));
            }
        });
    }
}

// Function to remove room from the summary
function removeRoom(roomId, price) {
    const bookedRoomsContainer = document.getElementById('booked-rooms');
    const noRoomsMessage = document.getElementById('no-rooms-message');
    const allRooms = Array.from(bookedRoomsContainer.querySelectorAll('.room-item')); // Move this line up
    const roomElement = document.getElementById(`room-${roomId}`);
    let countOffered = 0;

    allRooms.forEach(room => {
        const isOffered = parseInt(room.dataset.isOffered) || 0;
        if (isOffered === 1) {
            countOffered++;
        }
    });

    const dateIn = document.getElementById('date-in').value;
    const dateOut = document.getElementById('date-out').value;
    const isOvernight = dateIn !== dateOut;

    if (allRooms.length === 1 && (countOffered === 1 || countOffered === 0) && isOvernight) {
        alert("You need at least one of the offered room for overnight stays.");
    } else {
        roomElement.remove();
        updateTotal(-price);
        removeRoomIdFromForm(roomId);
    }

    if (bookedRoomsContainer.childElementCount === 1) {
        noRoomsMessage.style.display = 'block';
    }

    if (bookedRoomsContainer.childElementCount === 1 && rateType === '2') {
        // showOfferedRoomsOnly();
        offeredRoomAdded = false;
    }
}


// Function to update total
function updateTotal(priceChange) {
    const roomTotalElement = document.getElementById("room-total");
    const grandTotalElement = document.getElementById("grand-total");
    const rateElement = document.getElementById("rate");

    // Parse the current Room total and Grand total
    const currentRoomTotal = parseInt(roomTotalElement.textContent.replace(/PHP /, "").replace(/,/g, "")) || 0;
    const currentGrandTotal = parseInt(grandTotalElement.textContent.replace(/PHP /, "").replace(/,/g, "")) || 0;

    // Parse rate to PHP
    const rate = parseInt(rateElement.textContent.replace(/PHP /, "").replace(/,/g, "")) || 0;

    // Update Room total
    const newRoomTotal = currentRoomTotal + priceChange;
    roomTotalElement.textContent = `PHP ${newRoomTotal.toLocaleString()}`;

    // Calculate total
    const newGrandTotal = rate + newRoomTotal;
    grandTotalElement.textContent = `PHP ${newGrandTotal.toLocaleString()}`;

    document.getElementById("grandTotal").value = newGrandTotal;
    document.getElementById("roomTotal").value = newRoomTotal;
}
document.getElementById("secondForm").addEventListener("click", function(event) {
    //event.preventDefault(); // Prevent form from submitting normally
    var totalpax = document.getElementById("adults").value;
        var reservationType = document.getElementById("reservationType").value;
        const bookedRooms = document.getElementById("booked-rooms");
        const noRoomsMessage = document.getElementById("no-rooms-message");
        // Check if any room is selected
        
            if (totalpax == '' || totalpax == 0) {
                alert("Enter number of guest.");
                event.preventDefault();
            }
            if(!reservationType.trim()){
                alert("Enter a valid reservation type.");
                event.preventDefault();
            }
        if(rateType === '1'){
        // Gather the form data
    const formData = new FormData(document.querySelector("form"));

    // Send data using Fetch API
    fetch("booking-process.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text()) // Assuming you're returning text or HTML
    /*.then(data => {
        console.log(data); // Handle the response from the server
        // Optionally, you can update part of the page with the response
        document.getElementById("response-container").innerHTML = data;
    })*/
    .catch(error => {
        console.error("Error:", error);
    });
        } else if (rateType === '2' && bookedRooms.children.length === 1 && noRoomsMessage.style.display !== "none") {
            alert("Please select at least one room before continuing.");
            event.preventDefault();
        }
});


</script>
</body>
</html>
