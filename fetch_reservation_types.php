<?php
include 'connection.php';

$query = "SELECT id, reservation_type FROM reservationtype_tbl";
$result = mysqli_query($conn, $query);

$reservation_types = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reservation_types[] = $row;
}

echo json_encode($reservation_types);
?>
