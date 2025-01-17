<?php 
session_start();
include("connection.php");

// New SQL query combining notification_tbl and cancel_tbl
$sql = "
    SELECT 
        (SELECT COUNT(*) FROM notification_tbl n
         JOIN booking_tbl b ON n.booking_id = b.booking_id
         JOIN users u ON b.user_id = u.user_id
         WHERE n.is_read_admin = 0 AND (b.status = 'Pending' OR (n.status = 5 AND b.status = 'Approved'))
        ) AS notification_count,
        (SELECT COUNT(*) FROM cancel_tbl c
         WHERE c.is_read = 0
        ) AS cancellation_count
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Calculate total count
$total_count = $result['notification_count'] + $result['cancellation_count'];

// Return the total notification count as a JSON response
echo json_encode($total_count);
?>
