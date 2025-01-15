<?php 
session_start();
include("connection.php");
 
    // New SQL query
    $sql = "SELECT 
                u.user_id, 
                u.firstname, 
                u.lastname, 
                u.role, 
                u.profile, 
                MAX(m.timestamp) AS max_timestamp, 
                MAX(CASE WHEN m.sender_id = u.user_id THEN m.msg END) AS latest_msg, 
                COUNT(CASE WHEN m.is_read_admin = 0 AND m.sender_id = u.user_id THEN 1 END) AS unread_count
            FROM users u
            LEFT JOIN message_tbl m 
            ON u.user_id = m.sender_id OR u.user_id = m.receiver_id
            WHERE u.role = 'user' AND m.msg_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the notification count as a JSON response
    foreach($result as $results){
        echo $results['notification_count'];
    }

?>