<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=lanmartest", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

session_start();
include "role_access.php";
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

    // Update feedback
    $query = "UPDATE feedback_tbl SET rating = :rating, comment = :comment, updated_at = NOW() WHERE feedback_id = :feedback_id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':rating' => $rating,
        ':comment' => $comment,
        ':feedback_id' => $feedbackId,
        ':user_id' => $userId
    ]);

    header("Location: my-feedback.php");
    exit;
}
?>
