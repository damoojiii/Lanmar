<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    $sql = "INSERT INTO inclusion_tbl (inclusion_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $name);

    echo json_encode(['success' => $stmt->execute()]);
}
?>
