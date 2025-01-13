<?php
    date_default_timezone_set('Asia/Manila');

    session_start();
    include("connection.php");
    $userId = $_SESSION['user_id']; 

    $today = date('Y-m-d');
    $sql = "SELECT dateIn, dateOut, checkin, checkout, hours 
            FROM booking_tbl 
            WHERE (dateIn >= '$today' OR dateOut >= '$today') 
            AND (status NOT IN ('Cancelled', 'Rejected') 
            OR (status = 'Cancelled' AND user_id = '$userId'))";
            
    $result = $conn->query($sql);

    $bookings = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $booking = array(
                'dateIn' => $row['dateIn'],
                'dateOut' => $row['dateOut'],
                'checkin' => $row['checkin'],
                'checkout' => $row['checkout'],
                'hours' => $row['hours']
            );
            $bookings[] = $booking;
        }
    }

    echo json_encode($bookings);
?>
