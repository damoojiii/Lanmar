<?php
include 'connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$feedback_id = $data['feedback_id'];
$is_featured = $data['is_featured'];

try {
    if ($is_featured == 1) {
        $checkQuery = "SELECT COUNT(*) AS count FROM feedback_tbl WHERE is_featured = 1";
        $checkStmt = $pdo->query($checkQuery);
        $count = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'];

        if ($count >= 3) {
            echo json_encode(['success' => false, 'message' => 'Maximum limit of featured feedbacks reached.']);
            exit;
        }
    }

    // Update the feedback
    $updateQuery = "UPDATE feedback_tbl SET is_featured = :is_featured WHERE feedback_id = :feedback_id";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute([':is_featured' => $is_featured, ':feedback_id' => $feedback_id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
