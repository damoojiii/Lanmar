<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];

    $conn->begin_transaction();

    try {
        $sql_delete_inclusions = "DELETE FROM room_inclusions WHERE room_id = ?";
        $stmt = $conn->prepare($sql_delete_inclusions);
        $stmt->bind_param('i', $room_id);
        $stmt->execute();

        $sql_delete_room = "DELETE FROM rooms WHERE room_id = ?";
        $stmt = $conn->prepare($sql_delete_room);
        $stmt->bind_param('i', $room_id);
        $stmt->execute();

        $conn->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
