<?php 
session_start();
include("connection.php");

$sql = "
    SELECT 
        COUNT(*) AS unread_chat_count
    FROM 
        message_tbl m
    JOIN 
        users u ON m.sender_id = u.user_id
    WHERE 
        m.is_read_admin = 0 AND u.role = 'user'
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($result['unread_chat_count']);
?>
