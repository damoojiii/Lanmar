<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])){
    $cancelId = intval($_POST['cancel_id']);

    // Prepare the SQL statement
    $sql = "UPDATE cancel_tbl SET is_read = 1 WHERE cancel_id = :cancel_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':cancel_id', $cancelId, PDO::PARAM_INT);
    $query->execute();
} else {
    echo "<script>alert('Invalid request.')
            window.location='/lanmar/my-notification.php'; 
    </script>";
}
?>