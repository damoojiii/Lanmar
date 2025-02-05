<?php
include "role_access.php";
date_default_timezone_set('Asia/Manila');
include("connection.php");
checkAccess('user');

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedbackId = $_POST['feedback_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Validate input
    if (!is_numeric($rating) || $rating < 1 || $rating > 5 || empty(trim($comment))) {
        echo "Invalid input.";
        exit;
    }
    $currentDatetime = date('Y-m-d H:i:s');

    // Update feedback
    $query = "UPDATE feedback_tbl SET rating = :rating, comment = :comment, updated_at = :timestamp WHERE feedback_id = :feedback_id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':rating' => $rating,
        ':comment' => $comment,
        ':feedback_id' => $feedbackId,
        ':user_id' => $userId,
        ':timestamp' => $currentDatetime
    ]);

    header("Location: my-feedback.php");
    exit;
}
?>
