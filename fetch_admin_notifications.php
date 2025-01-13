<?php
date_default_timezone_set('Asia/Manila'); 
include("connection.php");
try{

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

$sql = "
    (SELECT 
        n.notification_id AS id,
        'read' AS type,
        n.booking_id,
        n.timestamp,
        b.dateIn,
        b.dateOut,
        b.checkin,
        b.checkout,
        b.status,
        u.user_id,
        u.firstname,
        u.lastname
    FROM 
        notification_tbl n
    JOIN 
        booking_tbl b ON n.booking_id = b.booking_id
    JOIN 
        users u ON b.user_id = u.user_id
    WHERE 
        n.is_read_admin = 1)
UNION
    (SELECT 
        c.cancel_id AS id,
        'cancel' AS type,
        c.booking_id,
        c.timestamp,
        b.dateIn,
        b.dateOut,
        b.checkin,
        b.checkout,
        b.status,
        u.user_id,
        u.firstname,
        u.lastname
    FROM 
        cancel_tbl c
    LEFT JOIN 
        booking_tbl b ON c.booking_id = b.booking_id
    LEFT JOIN 
        users u ON b.user_id = u.user_id
    WHERE 
        c.is_read = 1)
ORDER BY 
    timestamp DESC";

$query = $pdo->prepare($sql);
$query->execute();
$notifications = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($notifications as $notification) {
    $fullName = ucwords($notification['firstname']) . ' ' . ucwords($notification['lastname']);
    $dateIn = date('F j, Y', strtotime($notification['dateIn']));
    $dateOut = date('F j, Y', strtotime($notification['dateOut']));
    $dateDisplay = ($dateIn === $dateOut) ? $dateIn : "$dateIn - $dateOut";
    $checkinTime = date('h:i A', strtotime($notification['checkin']));
    $checkoutTime = date('h:i A', strtotime($notification['checkout']));
    $timeAgo = timeAgo($notification['timestamp']);
    
    echo '<div class="notification-card ' . $notification['type'] . '">';
    echo '<div class="notification-content">';
    echo '<p><strong>From ' . htmlspecialchars($fullName) . '</strong></p>';
    echo '<p>Date & Time: <br>' . $dateDisplay . ' ' . $checkinTime . '-' . $checkoutTime . '</p>';
    echo '</div>';
    echo '<div class="notification-footer">';
    echo '<p class="time">' . htmlspecialchars($timeAgo) . '</p>';
    echo '<button class="btn btn-primary btn-sm view-button" data-booking-id="'.$notification['booking_id'].'" data-status="'.$notification['status'].'">View</button>';
    echo '</div>';
    echo '</div>';
}

}catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>