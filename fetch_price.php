<?php
include 'connection.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("SELECT price FROM prices_tbl WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();

    echo $price;
    $stmt->close();
    $conn->close();
}
?>