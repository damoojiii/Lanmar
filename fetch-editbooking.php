<?php
    date_default_timezone_set('Asia/Manila');

    // Get the booking ID to exclude
    $id = $_GET['id'];
    include("connection.php");

    // Get today's date
    $today = date('Y-m-d');

    // SQL query to fetch all bookings except the one with the specified ID
    $sql = "SELECT dateIn, dateOut, checkin, checkout, hours 
            FROM booking_tbl 
            WHERE (dateIn >= '$today' OR dateOut >= '$today') AND booking_id != '$id'";

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

    // Output the bookings as a JSON array
    echo json_encode($bookings);

    // Close the database connection
    $conn->close();
?>
