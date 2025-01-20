<?php 
include 'connection.php';

if (isset($_POST['reservation_type'])) {
    $reservation_type = mysqli_real_escape_string($conn, $_POST['reservation_type']);

    $query = "INSERT INTO reservationtype_tbl (reservation_type) VALUES ('$reservation_type')";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Reservation type added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add reservation type.']);
    }
}
?>
