<?php
include 'connection.php';

if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $query = "DELETE FROM reservationtype_tbl WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Reservation type deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete reservation type.']);
    }
}
?>
