<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    session_start();

    $senderId = $_POST['user_id'];
    $adminId = $_SESSION['user_id'];
    $message = $_POST['message'];
    $read = 0;

    $stmt = $pdo->prepare("
        INSERT INTO message_tbl (sender_id, receiver_id, msg, timestamp, is_read)
        VALUES (:adminId,:senderId,:message, NOW(), :read)
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
