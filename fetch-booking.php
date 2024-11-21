<?php
    date_default_timezone_set('Asia/Manila');

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "lanmar_db";

    $con = new mysqli($servername, $username, $password, $dbname);

    if($con->connect_error){
        die("Connection Failed".$con->connect_error);
    }

    $today = date('Y-m-d');
    $sql = "SELECT dateIn, dateOut, checkin, checkout, hours FROM booking_tbl WHERE dateIn >= '$today' OR dateOut >= '$today'";
    $result = $con->query($sql);

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