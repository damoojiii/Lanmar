<?php
include 'connection.php';

try {
    $query = "SELECT COUNT(*) AS count FROM feedback_tbl WHERE is_featured = 1";
    $stmt = $pdo->query($query);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['count' => $result['count']]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
