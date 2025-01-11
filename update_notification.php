<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notification_id']) && isset($_POST['role'])) {
    $notificationId = intval($_POST['notification_id']);
    $role = $_POST['role'];

    $fieldToUpdate = ($role === 'user') ? 'is_read_user' : 'is_read_admin';

    // Prepare the SQL statement
    $sql = "UPDATE notification_tbl SET $fieldToUpdate = 1 WHERE notification_id = :notification_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':notification_id', $notificationId, PDO::PARAM_INT);
    $query->execute();
} else {
    echo "<script>alert('Invalid request.')
            window.location='/lanmar/my-notification.php'; 
    </script>";
}
?>