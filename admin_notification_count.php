<?php 
session_start();
include("connection.php");
 
    // New SQL query
    $sql = "SELECT COUNT(*) as notification_count
            FROM notification_tbl n
            JOIN booking_tbl b ON n.booking_id = b.booking_id
            JOIN users u ON b.user_id = u.user_id
            WHERE n.is_read_admin = 0 AND (b.status = 'Pending' OR (n.status = 5 AND b.status = 'Approved'))";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the notification count as a JSON response
    foreach($result as $results){
        echo $results['notification_count'];
    }

?>