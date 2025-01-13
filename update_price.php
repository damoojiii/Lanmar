<?php
include("connection.php"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $payment_name = $_POST['payment_name'];
    $price = $_POST['price'];

    // Prepare and execute the update statement
    $stmt = $conn->prepare("UPDATE prices_tbl SET payment_name = ?, price = ? WHERE id = ?");
    $stmt->bind_param("ssi", $payment_name, $price, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}
?>
