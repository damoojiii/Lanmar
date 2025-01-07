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
    //if(!isset($_SESSION['dateIn'])&&!isset($_SESSION['dateOut'])){
    //    echo '<script>
    //               window.location="/lanmar/index1.php"; 
    //    </script>';
    //}
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
    <style>
        @font-face {
        font-family: 'nautigal';
        src: url(font/TheNautigal-Regular.ttf);
        }
        nav{
            background-color: #00214b;
            height: 80px;
            padding: 25px 100px;
        }
        nav a span{
            font-size: 150px;
            font-family: nautigal;
        }
        .progress-container {
            width: 100%;
            height: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background:lightgray;
        }

        .progress-bar {
            width: 100%;
            margin-left: auto;
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
            background-color: #00214b; /* Blue background for completed steps */
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
        .bill-message{
            display: flex;
            gap: 80px;
            margin-left: 100px;
        }
        .qrcode {
            padding: 5px 10px;
            border: 1px solid black;
            width: 300px;
            height: 300px;
            margin-bottom: 10px;
        }
        form p{
            margin-top: 5px;
            margin-bottom: -5px;
        }
        .summary {
            padding: 5px 10px;
            width: 50%;
            margin-right: 10%;
            border: 1px solid black;
        }
        .summary p{
            text-align: justify;
        }
        .btn-primary {
            background-color: #003366;
            border: none;
            font-size: 1rem;
        }
        #reference{
            width: 100%;
        }
        .submit-btn{
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .sabmit{
            width: 50%;
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

    .bill-message {
        gap: 40px;
        margin-left: 50px;
    }

    .qrcode {
        width: 250px;
        height: 250px;
    }

    .summary {
        width: 60%;
        margin-right: 5%;
    }
    .sabmit{
        width: 100%;
    }
}

    /* Mobile (Phone) - from 768px and below */
    @media (max-width: 768px) {
        nav {
            padding: 15px 30px;
            height: 70px !important;
        }

        nav a span {
            font-size: 80px;
        }

        .logo-font{
            font-size: 2rem !important;
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
        .section-header{
            text-align: center;
        }
        .bill-message {
            flex-direction: column;
            gap: 20px;
            margin-left: 0;
            align-items: center;
        }
        .qr{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 100%;
            height: 100%;
        }

        .qrcode {
            width: 300px;
            height: 300px;
        }

        .summary {
            width: 90%;
            margin-right: 0;
            margin-top: 15px;
        }

        input[type="file"], input[type="text"], button {
            width: 100%;
            font-size: 14px;
        }
        .submit-btn{
            justify-content: flex-start;
            width: 100%;
        }
    }

    /* Phone (Small screen) - 430px and below */
    @media (max-width: 430px) {
        nav {
            padding: 10px 20px;
            height: 60px;
        }

        nav a span {
            font-size: 60px;
        }

        .progress-bar {
            flex-direction: row;
            gap: 1rem;
        }

        .bill-message {
            flex-direction: column;
            gap: 10px;
            margin-left: 0;
            align-items: center;
        }

        .summary {
            width: 100%;
            margin-right: 0;
        }

        input[type="file"], input[type="text"], button {
            width: 100%;
            font-size: 12px;
        }

        button {
            margin-top: 10px;
        }
    }
    </style>
</head>
<body>
<nav>
    <a href="#" class="mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-2 logo-font">Lanmar Resort</span>
    </a>
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
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_POST['choice']) && !empty($_POST['choice'])) {
            // Store the selected choice in the session
            $_SESSION['payment_method'] = $_POST['choice'];
        } else {
            // If no choice is selected, display an error message
            $error_message = "No payment method selected. Please choose one.";
        }
    }
    $dateIn = $_SESSION['dateIn'] ?? '';
    $dateOut = $_SESSION['dateOut'] ?? '';
    $checkin = $_SESSION['checkin'] ?? '';
    $checkout = $_SESSION['checkout'] ?? '';
    $numhours = $_SESSION['numhours'] ?? '';
    $adults = $_SESSION['adult'] ?? '';
    $childs = $_SESSION['child'] ?? '';
    $pwd = $_SESSION['pwd'] ?? '';
    $totalPax = $_SESSION['totalpax'] ?? '';
    $reservationType = $_SESSION['reservationType'];
    $origPrice = $_SESSION['rate'] ?? '';
    $grandTotal = $_SESSION['grandTotal']  ?? '';
    $roomTotal = $_SESSION['roomTotal'];
    $roomIds = $_SESSION['roomIds'] ?? '';
    $paymode = $_SESSION['payment_method'] ?? '';
    $status = 'Pending';

    $sql = "SELECT * FROM prices_tbl where payment_name = 'downpayment'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $priceData = $stmt->fetch(PDO::FETCH_ASSOC);
    $price = $priceData['price'];
    $balance = $grandTotal - $price;

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && basename($_SERVER['PHP_SELF']) == 'booking-process2.1.php') {
    

    // Capture the reference ID from the form
    $ref_id = $_POST['ref_id'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Define the upload directory and file path
        $uploadDir = 'uploads/ref_proof/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            //echo "File uploaded successfully. Stored as: " . $uploadFile . "<br>";
        } else {
            echo "File upload failed.<br>";
        }
    } else {
        echo "<script>
            alert('No file uploaded or error occurred.');
            window.location.href = window.location.href; // This forces a page reload and starts from the beginning
            </script>";
    }

    try {
        // Insert into bill_tbl first
        $bill_sql = "INSERT INTO bill_tbl (image, ref_num, balance, total_bill, pay_mode) 
                    VALUES (:uploadFile, :ref_id, :balance, :grandTotal, :paymode)";
        $stmt = $pdo->prepare($bill_sql);
        $stmt->execute([
            ':uploadFile' => $uploadFile, 
            ':ref_id' => $ref_id,
            ':balance' => $balance,
            ':grandTotal' => $grandTotal,
            ':paymode' => $paymode
        ]);
        $bill_id = $pdo->lastInsertId();  // Get the last inserted bill_id
        //echo "New record created successfully in bill_tbl<br>";
    
        // Insert into pax_tbl next
        $pax_sql = "INSERT INTO pax_tbl (adult, child, pwd) 
                    VALUES (:adults, :childs, :pwd)";
        $stmt = $pdo->prepare($pax_sql);
        $stmt->execute([
            ':adults' => $adults, 
            ':childs' => $childs, 
            ':pwd' => $pwd
        ]);
        $pax_id = $pdo->lastInsertId();  // Get the last inserted pax_id
        //echo "New record created successfully in pax_tbl<br>";
    
        // Insert into room_tbl
        foreach ($roomIds as $roomId) {
        // Prepare the SQL to fetch room details based on room_id
        $sql = "SELECT room_name FROM rooms WHERE room_id = :roomId";
        $stmt = $pdo->prepare($sql);
    
        // Execute the statement with parameter binding
        $stmt->execute([':roomId' => $roomId]);
    
        // Fetch the room data
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($room) {
        $room_name = $room['room_name']; // Extract the room name from the result
        
        // Insert the data into room_tbl with parameter binding
        $room_sql = "INSERT INTO room_tbl (room_Id, room_name, bill_id) 
                     VALUES (:roomId, :room_name, :billId)";
        $stmt = $pdo->prepare($room_sql);
        $stmt->execute([
            ':roomId' => $roomId,
            ':billId' => $bill_id,
            ':room_name' => $room_name
        ]);
        
        // Get the last inserted room_id
        $last_room_id = $pdo->lastInsertId();  
        //echo "New record created successfully in room_tbl with room_id: $last_room_id<br>";
    } else {
        // Handle the case where no room was found for the roomId
        echo "No room found with room_id: $roomId<br>";
    }
    }

    
        // Insert into booking_tbl last
        $booking_sql = "INSERT INTO booking_tbl (user_id, dateIn, dateOut, checkin, checkout, hours, reservation_id, pax_id, bill_id, additionals, status) 
                        VALUES (:userId, :dateIn, :dateOut, :checkin, :checkout, :hours, :res_type, :pax_id, :bill_id, 'None', :status)";
        $stmt = $pdo->prepare($booking_sql);
        $stmt->execute([
            'userId' => $userId,
            ':dateIn' => $dateIn, 
            ':dateOut' => $dateOut, 
            ':checkin' => $checkin, 
            ':checkout' => $checkout, 
            ':hours' => $numhours, 
            ':res_type' => $reservationType,
            ':pax_id' => $pax_id, 
            ':bill_id' => $bill_id,
            ':status' => $status
        ]);

        $bookingId = $pdo->lastInsertId();

        // Insert into notification_tbl
        $notification_sql = "INSERT INTO notification_tbl (booking_id, status, is_read_user, is_read_admin, timestamp) 
                            VALUES (:booking_id, 0, 0, 0, NOW())";
        $stmt_notification = $pdo->prepare($notification_sql);
        $stmt_notification->execute([
            ':booking_id' => $bookingId
        ]);

        unset($_SESSION['dateIn']);
        unset($_SESSION['dateOut']);
        unset($_SESSION['checkin']);
        unset($_SESSION['checkout']);
        unset($_SESSION['numhours']);
        unset($_SESSION['adult']);
        unset($_SESSION['child']);
        unset($_SESSION['pwd']);
        unset($_SESSION['totalpax']);
        unset($_SESSION['reservationType']);
        unset($_SESSION['rate']);
        unset($_SESSION['grandTotal']);
        unset($_SESSION['roomTotal']);
        unset($_SESSION['roomIds']);
        unset($_SESSION['payment_method']);
        $ref_id = '';
        $balance = '';
        
        //echo "New record created successfully in booking_tbl<br>";
        echo '<script type="text/javascript">';
        echo 'window.location.href = "booking-process3.php";';
        echo '</script>';
        exit;
    
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    }

?>

<!-- Main content -->
<div id="main-content" class="container mt-4 pt-3">
    <div class="container1">
        <div class="row" style="justify-content:space-between;">
        <div class="bill-message" >
            <div class="qr">
                <h2 class="section-header">Scan Here</h2>
                <div class="qrcode"></div>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" >
                    <input type="file" name="image" accept="image/*">
                    <p>Reference ID</p>
                    <input type="text" name="ref_id" id="reference">
                    <div class="submit-btn">
                        <button type="submit" class="btn btn-primary mt-1 sabmit">Submit</button>
                    </div>
                </form>
            </div>
            <div class="summary">
                <h2>Instructions</h2>
                <li>1.</li>
                <li>2.</li>
                <li>3.</li>
                <li>4.</li>
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
</script>
</body>
</html>
