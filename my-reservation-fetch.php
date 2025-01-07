<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lanmartest";

$con = new mysqli($servername, $username, $password, $dbname);

if($con->connect_error){
    die("Connection Failed".$con->connect_error);
}
$orID = isset($_GET['id']) ? isset($_GET['id']) : 0;

$sql = "SELECT 
booking_tbl.booking_id,
room_tbl.bill_id, room_tbl.room_name
FROM booking_tbl
LEFT JOIN room_tbl ON booking_tbl.bill_id = room_tbl.bill_id
WHERE booking_tbl.booking_id = :id
";
$stmt = 
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
?>