<?php 
    include("connection.php");


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Initialize variables
        $bookingId = $_POST['booking_id'];
        $status = $_POST['status'];
        $dateIn = $_POST['dateIn'];
        $dateOut = $_POST['dateOut'];
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
            $sql = "UPDATE booking_tbl SET dateIn = :dateIn, dateOut = :dateOut, checkin = :checkin, checkout = :checkout, hours = :numhours, reservation_id = :reservationType WHERE booking_id = :bookingId";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':dateIn' => $dateIn,
                ':dateOut' => $dateOut,
                ':checkin' => $checkin,
                ':checkout' => $checkout,
                ':numhours' => $numhours,
                ':reservationType' => $reservationType,
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
            if($status === "Pending"){
                echo "<script>  
                   window.location='pending_reservation.php'; 
                </script>";
            }elseif($status === "Approved"){
                echo "<script>  
                   window.location='approved_reservation.php'; 
                </script>";
            }
            
    
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<p class='text-danger'>An error occurred while updating the reservation: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

?>