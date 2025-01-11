<?php
date_default_timezone_set('Asia/Manila'); 
try {
    $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userId = $_GET['user_id'];

    $stmt = $pdo->prepare("
        SELECT 
            m.msg, 
            m.timestamp, 
            u.role, u.profile 
        FROM message_tbl m
        JOIN users u ON m.sender_id = u.user_id
        WHERE ((u.role = 'admin' AND m.receiver_id = :userId) 
       OR (m.sender_id = :userId AND m.receiver_id IN (SELECT user_id FROM users WHERE role = 'admin')))
        ORDER BY m.timestamp ASC
    ");
    $stmt->execute([':userId' => $userId]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
