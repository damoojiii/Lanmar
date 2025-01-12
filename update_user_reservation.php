<?php 
    date_default_timezone_set('Asia/Manila'); 
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $rebook = '';
        $bookingId = $_POST['booking_id'];
        $isRebook = $_POST['isrebook'];
        $prevDateIn = $_POST['prevDateIn'];
        $prevDateOut = $_POST['prevDateOut'];
        $dateIn = $_POST['dateIn'] ?? '';
        $dateOut = $_POST['dateOut'] ?? '';
        if(empty($dateIn) || empty($dateOut)){
            $dateIn = $prevDateIn;
            $dateOut = $prevDateOut;  
            $rebook = 1;
        }
        if($dateIn !== $prevDateIn || $dateOut !== $prevDateOut){
            $rebook = 1;
        }
        $checkin = $_POST['checkin'];
        $checkout = $_POST['checkout'];
        $numhours = $_POST['numhours'];
        (!empty($rooms)) ? $rooms = $_POST['rooms'] : '';
        $adult = $_POST['adult'] ?? 0;
        $child = $_POST['child'] ?? 0;
        $pwd = $_POST['pwd'];
        $reservationType = $_POST['reservationType'];
        $totalbill = $_POST['totalbill'];
        $balance = $_POST['balance'];
        
    
        // Error trapping and validation
        $errors = [];
    
        if (empty($bookingId)) {
            $errors[] = "Booking ID is required.";
        }
        if (empty($dateIn) || empty($dateOut)) {
            $errors[] = "Both Date In and Date Out are required.";
        }
        if (empty($checkin) || empty($checkout)) {
            $errors[] = "Both Check-In and Check-Out times are required.";
        }
        if (empty($numhours)) {
            $errors[] = "Number of hours is required.";
        }
        if (!is_numeric($adult) || $adult <= 0) {
            $errors[] = "Invalid number of adults.";
        }
        if (empty($reservationType)) {
            $errors[] = "Reservation type is required.";
        }
        if (!is_numeric($totalbill) || $totalbill < 0) {
            $errors[] = "Invalid total bill amount.";
        }
        if (!is_numeric($balance) || $balance < 0) {
            $errors[] = "Invalid balance amount.";
        }
    
        if (!empty($errors)) {
            // Display errors and stop execution
            foreach ($errors as $error) {
                echo "<p class='text-danger'>$error</p>";
            }
            exit;
        }
    
        try {
            $pdo->beginTransaction();
    
            // Update booking details
            $sql = "UPDATE booking_tbl SET dateIn = :dateIn, dateOut = :dateOut, checkin = :checkin, checkout = :checkout, hours = :numhours, reservation_id = :reservationType, is_rebook = :is_rebook WHERE booking_id = :bookingId";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':dateIn' => $dateIn,
                ':dateOut' => $dateOut,
                ':checkin' => $checkin,
                ':checkout' => $checkout,
                ':numhours' => $numhours,
                ':reservationType' => $reservationType,
                ':is_rebook' => $rebook,
                ':bookingId' => $bookingId
            ]);
            
            
            if (!empty($rooms)) {
                echo 'NAKAPASOK!';
                // Update room assignments
                $sql = "DELETE FROM room_tbl WHERE bill_id = (SELECT bill_id FROM booking_tbl WHERE booking_id = :bookingId)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':bookingId' => $bookingId]);
        
                $sql = "INSERT INTO room_tbl (bill_id, room_Id, room_name) VALUES ((SELECT bill_id FROM booking_tbl WHERE booking_id = :bookingId), :roomId, (SELECT room_name FROM rooms WHERE room_id = :roomId))";
                $stmt = $pdo->prepare($sql);
                foreach ($rooms as $roomId) {
                    $stmt->execute([
                        ':bookingId' => $bookingId,
                        ':roomId' => $roomId
                    ]);
                }

            }
    
            // Update pax details
            $sql = "UPDATE pax_tbl SET adult = :adult, child = :child, pwd = :pwd WHERE pax_id = (SELECT pax_id FROM booking_tbl WHERE booking_id = :bookingId)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':adult' => $adult,
                ':child' => $child,
                ':pwd' => $pwd,
                ':bookingId' => $bookingId
            ]);

    
            // Update billing details
            $sql = "UPDATE bill_tbl SET total_bill = :totalbill, balance = :balance WHERE bill_id = (SELECT bill_id FROM booking_tbl WHERE booking_id = :bookingId)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':totalbill' => $totalbill,
                ':balance' => $balance,
                ':bookingId' => $bookingId
            ]);
    
            $pdo->commit();


            $notification_sql = "INSERT INTO notification_tbl (booking_id, status, is_read_user, is_read_admin, timestamp) 
                    VALUES (:booking_id, 5, 2, 0, NOW())";
            $stmt_notification = $pdo->prepare($notification_sql);
            $stmt_notification->execute([
            ':booking_id' => $bookingId
            ]);

            echo "<script>  
                   window.location='my-reservation.php'; 
                </script>";
            
    
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<p class='text-danger'>An error occurred while updating the reservation: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

?>