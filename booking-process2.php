<?php
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    session_start();
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
            background-color: #00214b; /* Blue background for completed steps */
            border-color: #00214b; /* Blue border */
            color: white; 
        }

        .step.completed, .step .circle {
            background-color: lightgrey;
            border-color: white; 
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
    </style>
    <style>
        .summary {
            background-color: #00214b;
            color: #fff;
            width: 25%;
            height: 100%;
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
            max-width: 75%;
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
        <li><a href="#" class="nav-link text-white">Notification</a></li>
        <li><a href="#" class="nav-link text-white">Chat with Lanmar</a></li>
        <li><a href="#" class="nav-link text-white">Feedback</a></li>
        <li><a href="#" class="nav-link text-white">Settings</a></li>
    </ul>
    <hr>
    <a href="#" class="nav-link text-white">Log out</a>
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
</div>
<!-- phpsyntax for temp storage to process 3-->
<?php
    if (isset($_GET['continue'])) {
        $_SESSION['origPrice'] = $_GET['origPrice'] ?? '';
    }
    if (isset($_GET['grandTotal'])&& isset($_GET['roomTotal'])) {
        $_SESSION['grandTotal'] = (int)$_GET['grandTotal'];
        $_SESSION['roomTotal'] = (int)$_GET['roomTotal'];
    }

    $dateIn = $_SESSION['dateIn'] ?? '';
    $dateOut = $_SESSION['dateOut'] ?? '';
    $checkin = $_SESSION['checkin'] ?? '';
    $checkout = $_SESSION['checkout'] ?? '';
    $numhours = $_SESSION['numhours'] ?? '';
    $adult = $_SESSION['adult'] ?? '';
    $adult = $_SESSION['child'] ?? '';
    $pwd = $_SESSION['pwd'] ?? '';
    $totalPax = $_SESSION['totalpax'] ?? '';
    $origPrice = $_SESSION['origPrice'] ?? '';
    $grandTotal = $_SESSION['grandTotal']  ?? '';
    $roomTotal = $_SESSION['roomTotal'];

    $sql = "SELECT * FROM users where user_id = 13";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
?>

<!-- Main content -->
<div id="main-content" class="container mt-4 pt-3">
    <div class="container1">
        <div class="row" style="justify-content:space-between;">
        <div class="col-md-6" style="width: 75%;">
                <div class="section-header">Personal Information</div>
                <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo $user["firstname"];?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo $user["lastname"];?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select id="gender" name="gender" class="form-control" require>
                                <option selected hidden>Choose...</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                                <option value="O">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="phonenum" class="form-label">Contact No.</label>
                            <input type="number" id="phonenum" name="phonenum" class="form-control" value="<?php echo $user["contact_number"];?>" readonly>
                        </div>
                    </div>
                </form>
                <div class="section-header">Additionals</div>
                <form action="">
                    <label for="" class="form-label">is there any special request?</label>
                    <input type="text" class="message-box" name="" placeholder="Type your message here...">
                </form>
                <div class="section-header">Payment Method</div>
                <form action="" id="radioForm">
                    <input type="radio" name="choice" value="Gcash" class="form-label">
                    <label for="payment" class="form-label" >GCash</label>
                    <input type="radio" name="choice" value="PayMaya" class="form-label">
                    <label for="payment" class="form-label">Pay Maya</label>
                </form>

                </div>

            <div class="col-md-6 p-3 summary">
                <div class="section-header">Booking Summary</div>

                <div class="bg-light p-2 rounded mb-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p>Date: <span id="date-input"><?php echo "$dateIn to $dateOut";?></span></p>
                            <p>Time: <span id="time-input"><?php echo "$checkin to $checkout";?></span></p>
                            <p>Total No. of Pax: <span id="total-pax"><?php echo "$totalPax";?></span></p>
                            <p>Reservation Type: <span id="reservation-type"><?php 
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
    
                        <div class="dropdown">
                            <button class="btn btn-link p-0" type="button" id="editDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Edit
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="editDropdown">
                                <li><a class="dropdown-item" href="#">Edit Date</a></li>
                                <li><a class="dropdown-item" href="#">Edit Time</a></li>
                            </ul>
                        </div>


                    </div>
                </div>

                <!-- Booked Rooms Section -->
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Charges</h5>
                    </div>
                </div>

                <!-- Total Calculation Section -->
                <table class="w-100 text-light">
                    <tr>
                        <td>Original Price:</td>
                        <td class="text-end"><?php echo "$origPrice";?></td>
                    </tr>
                    <tr>
                        <td>Room:</td>
                        <td class="text-end"><?php
                        if ($grandTotal > $origPrice){
                            echo $roomTotal;
                        }else{
                            echo "2000";
                        }
?></td>
                    </tr>
                    <tr>
                        <td><strong>Total:</strong></td>
                        <td class="text-end"><strong><?php
                        if ($grandTotal > $origPrice){
                            echo $grandTotal;
                        }else{
                            echo $origPrice;
                        }
?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>Downpayment:</strong></td>
                        <td class="text-end"><strong>
                            <?php
                                $sql = "SELECT * FROM prices_tbl where payment_name = 'downpayment'";
                                $result = $conn->query($sql);
                                $price = $result->fetch_assoc();
                                echo $price["price"];
                            ?>
                        </strong></td>
                    </tr>
                    <tr>
                        <td>Payment Method:</td>
                        <td class="text-end"><p id="PaymentChoice">none</p></td>
                    </tr>
                </table>

                <button type="button" class="btn btn-primary w-100 mt-3">Continue</button>
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
    
    const mainContent = document.getElementById('main-content');
    mainContent.classList.toggle('shifted');
});

// Function to update the choice value
function Choice() {
            const selectedOption = document.querySelector('input[name="choice"]:checked');
            const output = document.getElementById('PaymentChoice');
            output.textContent = selectedOption ? selectedOption.value : "None";
        }

        // Attach event listener to the form for automatic updates
        const form = document.getElementById('radioForm');
        form.addEventListener('input', Choice);
</script>
</body>
</html>