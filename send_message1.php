<?php
include("connection.php");
try {

    $senderId = $_POST['user_id'];
    $adminId = 9;
    $message = $_POST['message'];
    $read = 0;

    $stmt = $pdo->prepare("
        INSERT INTO message_tbl (sender_id, receiver_id, msg, timestamp, is_read_admin)
        VALUES (:senderId,:adminId,:message, NOW(), :read)
    ");
    $stmt->execute([
        ':senderId' => $senderId,
        ':adminId' => $adminId,
        ':message' => $message,
        ':read' => $read
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
