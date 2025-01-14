<?php
include 'connection.php'; // Include your database connection

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("SELECT value FROM bookingprocess_tbl WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($value);
    $stmt->fetch();

    echo $value;
    $stmt->close();
    $conn->close();
}
?>
