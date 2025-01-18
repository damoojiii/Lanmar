<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $sql = "DELETE FROM inclusion_tbl WHERE inclusion_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    echo json_encode(['success' => $stmt->execute()]);
}
?>
