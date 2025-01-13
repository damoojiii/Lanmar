<?php 
session_start();
include("connection.php");
include "role_access.php";
checkAccess('user');


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // New SQL query
    $sql = "SELECT COUNT(*) AS chat_count
            FROM message_tbl m
            JOIN users u ON m.sender_id = u.user_id
            WHERE u.role = 'admin' AND m.receiver_id = :userId 
            AND is_read_user = 0";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $chat_count = $result['chat_count'];

    // Return the chat count as a JSON response
    echo json_encode($chat_count);
} else {
    echo json_encode(0);
}

?>