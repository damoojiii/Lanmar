<?php
session_start();
include("connection.php");
include "role_access.php";
checkAccess('user');

// Get the user ID from the session
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    // Validate inputs
    if ($rating < 1 || $rating > 5) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid rating.']);
        exit;
    }

    if (empty($comment)) {
        echo json_encode(['status' => 'error', 'message' => 'Comment cannot be empty.']);
        exit;
    }

    // Check if the user has already submitted feedback
    $checkSql = "SELECT COUNT(*) FROM feedback_tbl WHERE user_id = :user_id";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':user_id' => $userId]);
    $feedbackExists = $checkStmt->fetchColumn();

    if ($feedbackExists > 0) {
        echo json_encode(['status' => 'error', 'message' => 'You have already submitted a feedback.']);
        exit;
    }

    // Insert feedback into the database
    try {
        $sql = "INSERT INTO feedback_tbl (user_id, rating, comment, is_featured, created_at) 
                VALUES (:user_id, :rating, :comment, 0, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':rating' => $rating,
            ':comment' => $comment,
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Feedback submitted successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error submitting feedback: ' . $e->getMessage()]);
    }
    exit;
}
?>
