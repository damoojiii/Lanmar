<?php
include 'connection.php';

$countQuery = "SELECT COUNT(*) AS total FROM faq_tbl";
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalFAQs = $countRow['total'];

if ($totalFAQs >= 5) {
    echo "Limit reached! You can only have up to 5 FAQs.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $query = "INSERT INTO faq_tbl (question, answer) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $question, $answer);
    
    if ($stmt->execute()) {
        echo "FAQ added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
