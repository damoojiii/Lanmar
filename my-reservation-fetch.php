<?php
// Database connection using PDO
try {
    $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Function to fetch booking details by booking_id
function getBookingDetailsById($bookingId) {
    global $pdo;

    // Query to fetch booking details
    $bookingQuery = "SELECT 
        booking_tbl.booking_id, booking_tbl.dateIn, booking_tbl.dateOut, booking_tbl.checkin, booking_tbl.checkout,
        booking_tbl.hours, booking_tbl.additionals,
        users.firstname, users.lastname, users.contact_number, 
        reservationType_tbl.reservation_type,
        pax_tbl.adult, pax_tbl.child, pax_tbl.pwd,
        bill_tbl.total_bill, bill_tbl.balance, bill_tbl.pay_mode, bill_tbl.ref_num, image
    FROM booking_tbl
    LEFT JOIN users ON booking_tbl.user_id = users.user_id
    LEFT JOIN reservationType_tbl ON booking_tbl.reservation_id = reservationType_tbl.id
    LEFT JOIN pax_tbl ON booking_tbl.pax_id = pax_tbl.pax_id
    LEFT JOIN bill_tbl ON booking_tbl.bill_id = bill_tbl.bill_id
    WHERE booking_tbl.booking_id = :booking_id";
    $stmt = $pdo->prepare($bookingQuery);
    $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
    $stmt->execute();
    $bookingResult = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($bookingResult) {
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
        $roomsQuery = "SELECT room_name FROM room_tbl WHERE bill_id = (SELECT bill_id FROM booking_tbl WHERE booking_id = :booking_id);
";
        $stmtRooms = $pdo->prepare($roomsQuery);
        $stmtRooms->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
        if ($stmtRooms->execute()) {
            $roomsResult = $stmtRooms->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // If the query fails, get the error info
            $errorInfo = $stmtRooms->errorInfo();
            echo "Query failed: " . $errorInfo[2];
        }

        // Initialize an array to hold room details
        $rooms = [];
        
        // If no rooms are found, it will return an empty array
        if (!empty($roomsResult)) {
            foreach ($roomsResult as $room) {
                $rooms[] = [
                    'roomName' => $room['room_name']
                ];
            }
}

        // Add rooms to the booking details
        $bookingResult['dateRange'] = $dateRange;
        $bookingResult['timeRange'] = $timeRange;
        $bookingResult['rooms'] = $rooms;
        
        return $bookingResult;
    }

    return null;
}

// Check if booking_id is provided
if (isset($_GET['booking_id'])) {
    $bookingId = $_GET['booking_id'];

    // Get the booking details and rooms
    $bookingDetails = getBookingDetailsById($bookingId);

    if ($bookingDetails) {
        // Return booking details along with the rooms in JSON format
        echo json_encode([
            'bookingId' => $bookingDetails['booking_id'],
            'refNumber' => $bookingDetails['ref_num'],
            'name' => $bookingDetails['firstname'] . " " . $bookingDetails['lastname'],
            'contact' => $bookingDetails['contact_number'],
            'dateRange' => $bookingDetails['dateRange'],
            'timeRange' => $bookingDetails['timeRange'],
            'hours' => $bookingDetails['hours'],
            'adult' => $bookingDetails['adult'],
            'child' => $bookingDetails['child'],
            'pwds' => $bookingDetails['pwd'],
            'totalPax' => $bookingDetails['adult'] + $bookingDetails['child'] + $bookingDetails['pwd'],
            'type' => $bookingDetails['reservation_type'],
            'additional' => $bookingDetails['additionals'],
            'paymode' => $bookingDetails['pay_mode'] ?? null,
            'totalBill' => number_format($bookingDetails['total_bill']),
            'balance' => number_format($bookingDetails['balance']),
            'roomName' => $bookingDetails['rooms'], // Include the rooms in the response
            'imageProof' => $bookingDetails['image'],
        ]);
    } else {
        echo json_encode(['error' => 'Booking not found']);
    }
}
?>