<?php
include 'connection.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM faq_tbl WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "FAQ deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
