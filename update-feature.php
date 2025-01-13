<?php
include("connection.php");

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['feedback_id'], $data['is_featured'])) {
    $query = "UPDATE feedback_tbl SET is_featured = :is_featured WHERE feedback_id = :feedback_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':is_featured' => $data['is_featured'],
        ':feedback_id' => $data['feedback_id']
    ]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>
