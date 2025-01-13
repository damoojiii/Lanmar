<?php
date_default_timezone_set('Asia/Manila'); 
session_start();
include("connection.php");
$userId = $_SESSION['user_id'];
try {
    
    function timeAgo($timestamp) {
        $timeAgo = '';
        $currentTime = new DateTime();
        $notificationTime = new DateTime($timestamp);
        $interval = $currentTime->diff($notificationTime);
        
        if ($interval->y > 0) {
            $timeAgo = $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
        } elseif ($interval->m > 0) {
            $timeAgo = $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
        } elseif ($interval->d > 0) {
            $timeAgo = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
        } elseif ($interval->h > 0) {
            $timeAgo = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
        } elseif ($interval->i > 0) {
            $timeAgo = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
        } else {
            $timeAgo = 'Just now';
        }
        
        return $timeAgo;
    }
    
    $sql = "SELECT n.notification_id, n.status, n.is_read_user, n.timestamp, b.booking_id, b.user_id
            FROM 
                notification_tbl n
            JOIN 
                booking_tbl b ON n.booking_id = b.booking_id
            JOIN 
                users u ON b.user_id = u.user_id
            WHERE 
                n.is_read_user = 1 AND b.user_id = :userId
            ORDER BY 
                n.timestamp DESC LIMIT 9";

    $query = $pdo->prepare($sql);
    $query->execute(['userId' => $userId]);
    $notifications = $query->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($notifications as $notification) {
        $status = $notification['status'];
        $bookingId = $notification['booking_id'];
        $timeAgo = timeAgo($notification['timestamp']);
        $message = '';
        switch ($status) {
            case 1:
                $message = "Your Reservation #$bookingId has been approved.";
                break;
            case 2:
                $message = "Your Reservation #$bookingId has been rejected.";
                break;
            case 3:
                $message = "Your Reservation #$bookingId has been cancelled.";
                break;
            case 4:
                $message = "Your Cancellation for Reservation #$bookingId has been rejected.";
                break;
        }
        echo "
        <div class='notification-card read' data-notification-id='{$notification['notification_id']}'>
            <div class='notification-content'>
                <p>{$message}</p>
            </div>
            <div class='notification-footer'>
                <p class='time'>{$timeAgo}</p> <!-- Replace with dynamic time calculation -->
                <button class='btn btn-primary btn-sm view-button' data-booking-id='$bookingId'>View</button>
            </div>
        </div>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
