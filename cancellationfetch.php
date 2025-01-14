<?php

include("connection.php");

function getBookingDetailsById($cancelId) {
        global $pdo;
    
        // Query to fetch booking details
        $bookingQuery = "SELECT  
                cancel_tbl.cancel_id, cancel_tbl.booking_id, cancel_tbl.cancellation_reason,
                booking_tbl.booking_id, booking_tbl.dateIn, booking_tbl.dateOut, booking_tbl.checkin, booking_tbl.checkout, booking_tbl.hours,
                users.firstname, users.lastname, users.contact_number
            FROM cancel_tbl
            INNER JOIN booking_tbl ON cancel_tbl.booking_id = booking_tbl.booking_id
            INNER JOIN users ON booking_tbl.user_id = users.user_id
            WHERE cancel_tbl.cancel_id = :cancelId";
        $stmt = $pdo->prepare($bookingQuery);
        $stmt->bindParam(':cancelId', $cancelId, PDO::PARAM_INT);
        $stmt->execute();
        $bookingResult = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($bookingResult) {
            $fullname = ucwords($bookingResult['firstname'] . " " . $bookingResult['lastname']);
            // Format date range (dateIn to dateOut)
            $dateIn = strtotime($bookingResult['dateIn']);
            $dateOut = strtotime($bookingResult['dateOut']);
            $dateRange = ($dateIn != $dateOut)
                ? date("F j", $dateIn) . ' to ' . date("F j, Y", $dateOut)
                : date("F j, Y", $dateIn);
    
            // Format time range (checkin to checkout)
            $checkin = strtotime($bookingResult['checkin']);
            $checkout = strtotime($bookingResult['checkout']);
            $timeRange = date("g:i A", $checkin) . ' to ' . date("g:i A", $checkout);
            // Query to fetch all rooms related to this booking_id
            
        $bookingResult['dateRange'] = $dateRange;
        $bookingResult['timeRange'] = $timeRange;
        $bookingResult['fullname'] = $fullname;
        
        return $bookingResult;
    }

    return null;
}
if (isset($_GET['cancel_id'])) {
    $cancelId = $_GET['cancel_id'];

    // Get the booking details and rooms
    $bookingDetails = getBookingDetailsById($cancelId);

    if ($bookingDetails) {
        // Return booking details along with the rooms in JSON format
        echo json_encode([
            'cancelId' => $bookingDetails['cancel_id'],
            'name' => $bookingDetails['fullname'],
            'contact' => $bookingDetails['contact_number'],
            'dateRange' => $bookingDetails['dateRange'],
            'timeRange' => $bookingDetails['timeRange'],
            'reason' => $bookingDetails['cancellation_reason'],
        ]);
    } else {
        echo json_encode(['error' => 'Booking not found']);
    }
}
?>