<?php 
try {
    $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
session_start();


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; 
    // New SQL query
    $sql = "SELECT COUNT(*) as notification_count
            FROM notification_tbl n
            JOIN booking_tbl b ON n.booking_id = b.booking_id
            JOIN users u ON b.user_id = u.user_id
            WHERE n.is_read_user = 0 AND n.status IN (1, 2, 3, 4)
            AND b.user_id = :userId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $notification_count = $result['notification_count'];

    // Return the notification count as a JSON response
    echo json_encode($notification_count);
} else {
    echo json_encode(0);
}

?>